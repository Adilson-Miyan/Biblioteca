<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class AdminOrders extends Component
{
    use WithPagination;

    public string $search = '';

    public string $status = '';

    protected $queryString = ['search', 'status'];

    public function mount(): void
    {
        if (! auth()->user()->isAdmin()) {
            abort(403);
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function cancelarEncomenda(int $orderId): void
    {
        $order = Order::find($orderId);

        if ($order && $order->status === 'pendente') {
            $order->update(['status' => 'cancelada']);
            session()->flash('success', 'Encomenda cancelada com sucesso.');
        }
    }

    public function render()
    {
        $query = Order::with('user', 'items.livro')->latest();

        if ($this->search !== '') {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->status !== '') {
            $query->where('status', $this->status);
        }

        $orders = $query->paginate(15);

        return view('livewire.admin-orders', compact('orders'))
            ->layout('layouts.app');
    }
}
