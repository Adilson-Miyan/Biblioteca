<?php

namespace App\Livewire\Requisicoes;

use Livewire\Component;

use Livewire\WithPagination;
use App\Models\Requisicao;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingRececao = false;
    public $requisicaoIdToConfirm = null;
    public $dataRececao;

    protected $queryString = ['search'];

    public function confirmRececao($id)
    {
        $this->requisicaoIdToConfirm = $id;
        $this->dataRececao = Carbon::now()->format('Y-m-d');
        $this->confirmingRececao = true;
    }

    public function markAsReceived()
    {
        $this->validate([
            'dataRececao' => 'required|date',
        ]);

        $requisicao = Requisicao::findOrFail($this->requisicaoIdToConfirm);
        
        $dataRequisicao = Carbon::parse($requisicao->data_requisicao);
        $dataRececao = Carbon::parse($this->dataRececao);
        
        $diasDecorrentes = $dataRequisicao->diffInDays($dataRececao);

        $requisicao->update([
            'data_rececao' => $this->dataRececao,
            'dias_decorrentes' => $diasDecorrentes,
            'status' => 'entregue',
        ]);

        $this->confirmingRececao = false;
        $this->requisicaoIdToConfirm = null;
        
        session()->flash('success', 'Receção confirmada com sucesso.');
    }

    public function render()
    {
        $user = Auth::user();
        
        $query = Requisicao::with('user', 'livro')
            ->where(function($q) {
                $q->whereHas('user', function($q2) {
                    $q2->where('name', 'like', '%' . $this->search . '%');
                })->orWhereHas('livro', function($q2) {
                    $q2->where('nome', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc');

        if ($user->isCidadao()) {
            $query->where('user_id', $user->id);
        }

        $requisicoes = $query->paginate(10);

        // Indicadores (top indicators)
        $baseQuery = Requisicao::query();
        if ($user->isCidadao()) {
            $baseQuery->where('user_id', $user->id);
        }

        $activeRequests = (clone $baseQuery)->whereIn('status', ['pendente', 'atrasado'])->count();
        $requestsLast30Days = (clone $baseQuery)->where('data_requisicao', '>=', Carbon::now()->subDays(30))->count();
        $deliveredToday = (clone $baseQuery)->where('status', 'entregue')->whereDate('data_rececao', Carbon::today())->count();

        return view('livewire.requisicoes.index', [
            'requisicoes' => $requisicoes,
            'activeRequests' => $activeRequests,
            'requestsLast30Days' => $requestsLast30Days,
            'deliveredToday' => $deliveredToday,
        ])->layout('layouts.app');
    }
}
