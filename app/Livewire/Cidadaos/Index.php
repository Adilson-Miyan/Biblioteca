<?php

namespace App\Livewire\Cidadaos;

use Livewire\Component;

use Livewire\WithPagination;
use App\Models\User;
use App\Models\Requisicao;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $cidadaoSelecionado = null;
    public $showModal = false;

    protected $queryString = ['search'];

    public function openDetalhe($id)
    {
        $this->cidadaoSelecionado = User::with(['requisicaos.livro'])->findOrFail($id);
        $this->showModal = true;
    }

    public function render()
    {
        // Admin only protection
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $cidadaos = User::where('role', 'cidadao')
            ->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.cidadaos.index', [
            'cidadaos' => $cidadaos,
        ])->layout('layouts.app');
    }
}
