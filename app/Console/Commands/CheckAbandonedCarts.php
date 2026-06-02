<?php

namespace App\Console\Commands;

use App\Mail\CartReminder;
use App\Models\Cart;
use Carbon\Carbon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

#[Signature('app:check-abandoned-carts')]
#[Description('Verifica carrinhos inativos há mais de 5 minutos e envia um email de notificação. (ajustado para testes)')]
class CheckAbandonedCarts extends Command
{
    public function handle(): int
    {
        $oneHourAgo = Carbon::now()->subMinutes(5);

        $carts = Cart::query()
            ->with(['user', 'items.livro'])
            ->where('status', 'active')
            ->whereNull('reminder_sent_at')
            ->where('updated_at', '<=', $oneHourAgo)
            ->whereHas('items')
            ->get();

        foreach ($carts as $cart) {
            if (! $cart->user?->email) {
                continue;
            }

            Mail::to($cart->user->email)->send(new CartReminder($cart));

            $cart->update([
                'status' => 'abandoned',
                'reminder_sent_at' => now(),
            ]);

            $this->info("Lembrete enviado para {$cart->user->email}");
        }

        $this->info("Processados {$carts->count()} carrinho(s).");

        return self::SUCCESS;
    }
}
