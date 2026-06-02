<?php

namespace App\Livewire;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CartComponent extends Component
{
    public int $step = 1;

    public string $address = '';

    public function mount(): void
    {
        $this->address = Auth::user()->delivery_address ?? '';
    }

    public function getCartProperty(): Cart
    {
        return Cart::resolveActiveForUser(Auth::id())
            ->load(['items.livro.autores']);
    }

    public function removerDoCarrinho(int $itemId): void
    {
        $this->cart->items()->where('id', $itemId)->delete();
        $this->cart->touch();
        $this->dispatch('cartUpdated');

        if ($this->cart->items()->count() === 0) {
            $this->step = 1;
        }

        session()->flash('success', 'Livro removido do carrinho.');
    }

    public function irParaMorada(): void
    {
        if ($this->cart->items->isEmpty()) {
            session()->flash('error', 'O seu carrinho está vazio.');
            return;
        }

        $this->step = 2;
    }

    public function irParaPagamento(): void
    {
        $this->validate([
            'address' => 'required|string|min:10',
        ], [
            'address.required' => 'A morada de entrega é obrigatória.',
            'address.min' => 'Indique a morada completa (mínimo 10 caracteres).',
        ]);

        session(['checkout.address' => $this->address]);
        Auth::user()->update(['delivery_address' => $this->address]);

        $this->step = 3;
    }

    public function voltar(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function render()
    {
        $cart = $this->cart;
        $total = $cart->items->sum(fn ($item) => (float) ($item->livro->preco ?? 0));

        return view('livewire.cart-component', compact('cart', 'total'))
            ->layout('layouts.app');
    }
}
