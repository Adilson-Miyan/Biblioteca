<?php

namespace App\Livewire;

use Livewire\Component;

use Livewire\WithPagination;
use App\Models\ActionLog;

class AdminLogs extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = ActionLog::with('user')->latest();

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('modulo', 'like', '%' . $this->search . '%')
                  ->orWhere('alteracao', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function($uq) {
                      $uq->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        $logs = $query->paginate(20);

        return view('livewire.admin-logs', compact('logs'))->layout('layouts.app');
    }
}
