<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;
use App\Services\LogService;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        $address = session('checkout.address');

        if (! $address || strlen(trim($address)) < 10) {
            return redirect()->route('cart.index')
                ->with('error', 'Indique a morada de entrega antes de pagar.');
        }

        $user = Auth::user();
        $cart = Cart::with('items.livro')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'O seu carrinho está vazio.');
        }

        $secret = config('services.stripe.secret');
        if (! $secret) {
            return redirect()->route('cart.index')
                ->with('error', 'Pagamento indisponível. Configure STRIPE_SECRET no ficheiro .env.');
        }

        Stripe::setApiKey($secret);

        $lineItems = [];
        $total = 0;

        foreach ($cart->items as $item) {
            $preco = (float) ($item->livro->preco ?? 0);
            if ($preco <= 0) {
                return redirect()->route('cart.index')
                    ->with('error', 'O livro «'.$item->livro->nome.'» não tem preço definido.');
            }

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item->livro->nome,
                    ],
                    'unit_amount' => (int) round($preco * 100),
                ],
                'quantity' => 1,
            ];
            $total += $preco;
        }

        Order::query()
            ->where('user_id', $user->id)
            ->where('status', 'pendente')
            ->update(['status' => 'cancelada']);

        $checkoutSession = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel'),
            'customer_email' => $user->email,
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'total' => $total,
            'address' => $address,
            'status' => 'pendente',
            'stripe_session_id' => $checkoutSession->id,
        ]);

        foreach ($cart->items as $item) {
            $order->items()->create([
                'livro_id' => $item->livro_id,
                'price' => $item->livro->preco,
            ]);
        }

        $user->update(['delivery_address' => $address]);

        session(['checkout.pending_order_id' => $order->id]);

        return redirect($checkoutSession->url);
    }

    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (! $sessionId) {
            return redirect()->route('catalogo');
        }

        $order = Order::where('stripe_session_id', $sessionId)
            ->where('user_id', Auth::id())
            ->first();

        if (! $order) {
            return redirect()->route('catalogo')->with('error', 'Encomenda não encontrada.');
        }

        if ($order->status === 'pendente') {
            $secret = config('services.stripe.secret');
            if ($secret) {
                Stripe::setApiKey($secret);
                $session = StripeSession::retrieve($sessionId);

                if ($session->payment_status !== 'paid') {
                    return redirect()->route('cart.index')
                        ->with('error', 'O pagamento ainda não foi confirmado.');
                }
            }

            $order->update(['status' => 'paga']);

            LogService::register('Encomendas', "Efetou o pagamento da encomenda #{$order->id} no valor de {$order->total}€", $order->id);

            $cart = Cart::where('user_id', Auth::id())->where('status', 'active')->first();
            if ($cart) {
                $cart->items()->delete();
                $cart->update(['status' => 'completed']);
            }
        }

        session()->forget(['checkout.address', 'checkout.pending_order_id']);

        return redirect()->route('catalogo')
            ->with('success', 'Pagamento concluído! A sua encomenda #'.$order->id.' foi registada.');
    }

    public function cancel()
    {
        $orderId = session('checkout.pending_order_id');

        if ($orderId) {
            Order::query()
                ->where('id', $orderId)
                ->where('user_id', Auth::id())
                ->where('status', 'pendente')
                ->update(['status' => 'cancelada']);
        }

        session()->forget('checkout.pending_order_id');

        return redirect()->route('cart.index')
            ->with('error', 'O pagamento foi cancelado. Pode tentar novamente.');
    }
}
