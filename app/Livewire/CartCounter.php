<?php

namespace App\Livewire;

use Livewire\Component;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class CartCounter extends Component
{
    public $count = 0;

    public function mount()
    {
        $this->updateCount();
    }

    #[On('cartUpdated')]
    public function updateCount()
    {
        if (Auth::check()) {
            $cart = \App\Models\Cart::where('user_id', Auth::id())->where('status', 'active')->first();
            $this->count = $cart ? $cart->items()->count() : 0;
        }
    }

    public function render()
    {
        return view('livewire.cart-counter');
    }
}
