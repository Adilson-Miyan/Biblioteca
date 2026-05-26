<?php

namespace App\Livewire\Reviews;

use Livewire\Component;

class Index extends Component
{
    use \Livewire\WithPagination;

    public $statusFilter = '';
    public $managingReview = false;
    public $reviewIdToManage = null;
    public $newStatus = 'ativo';
    public $justification = '';

    public function manage($id)
    {
        $review = \App\Models\Review::findOrFail($id);
        $this->reviewIdToManage = $review->id;
        $this->newStatus = $review->status === 'suspenso' ? 'ativo' : $review->status;
        $this->justification = $review->justification ?? '';
        $this->managingReview = true;
    }

    public function saveStatus()
    {
        $this->validate([
            'newStatus' => 'required|in:ativo,recusado',
            'justification' => 'required_if:newStatus,recusado',
        ]);

        $review = \App\Models\Review::findOrFail($this->reviewIdToManage);
        $review->update([
            'status' => $this->newStatus,
            'justification' => $this->newStatus === 'recusado' ? $this->justification : null,
        ]);

        $review->load('requisicao.user', 'requisicao.livro');
        \Illuminate\Support\Facades\Mail::to($review->requisicao->user->email)->send(new \App\Mail\ReviewStatusUpdatedMail($review));

        $this->managingReview = false;
        $this->reviewIdToManage = null;

        session()->flash('success', 'Estado da avaliação atualizado com sucesso.');
    }

    public function render()
    {
        if (!\Illuminate\Support\Facades\Auth::user()->isAdmin()) {
            abort(403);
        }

        $query = \App\Models\Review::with('requisicao.user', 'requisicao.livro')->orderBy('created_at', 'desc');

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        return view('livewire.reviews.index', [
            'reviews' => $query->paginate(10)
        ])->layout('layouts.app');
    }
}
