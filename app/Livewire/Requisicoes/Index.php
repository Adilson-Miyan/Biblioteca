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

        // Process alerts
        $livro = $requisicao->livro;
        if ($livro->isDisponivel()) {
            $alerts = \App\Models\LivroAlert::with('user')->where('livro_id', $livro->id)->where('is_notified', false)->get();
            foreach ($alerts as $alert) {
                \Illuminate\Support\Facades\Mail::to($alert->user->email)->send(new \App\Mail\LivroDisponivelMail($livro));
                $alert->update(['is_notified' => true]);
            }
        }

        $this->confirmingRececao = false;
        $this->requisicaoIdToConfirm = null;
        
        \App\Services\LogService::register('Requisições', "Confirmou a devolução/receção da requisição #{$requisicao->id}", $requisicao->id);

        session()->flash('success', 'Receção confirmada com sucesso.');
    }

    public $reviewing = false;
    public $requisicaoIdToReview = null;
    public $reviewRating = 5;
    public $reviewComment = '';

    public function openReview($id)
    {
        $this->requisicaoIdToReview = $id;
        $this->reviewRating = 5;
        $this->reviewComment = '';
        $this->reviewing = true;
    }

    public function submitReview()
    {
        $this->validate([
            'reviewRating' => 'required|integer|min:1|max:5',
            'reviewComment' => 'required|string|max:1000',
        ]);

        $requisicao = Requisicao::findOrFail($this->requisicaoIdToReview);

        if ($requisicao->user_id !== Auth::id()) {
            abort(403);
        }

        if ($requisicao->review) {
            session()->flash('error', 'Já existe uma avaliação para esta requisição.');
            $this->reviewing = false;
            return;
        }

        $review = $requisicao->review()->create([
            'rating' => $this->reviewRating,
            'comment' => $this->reviewComment,
            'status' => 'suspenso',
        ]);

        $review->load('requisicao.user', 'requisicao.livro');
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            \Illuminate\Support\Facades\Mail::to($admin->email)->send(new \App\Mail\ReviewCreatedMail($review));
        }

        $this->reviewing = false;
        $this->requisicaoIdToReview = null;
        
        session()->flash('success', 'Avaliação submetida com sucesso! Encontra-se pendente de aprovação.');
    }

    public function render()
    {
        $user = Auth::user();
        
        $query = Requisicao::with('user', 'livro', 'review')
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
