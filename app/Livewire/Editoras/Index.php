<?php

namespace App\Livewire\Editoras;

use Livewire\Component;

class Index extends Component
{
    use \Livewire\WithFileUploads;

    public $search = '';
    public $sortField = 'nome';
    public $sortAsc = true;

    public $editoraId;
    public $nome;
    public $logotipo;
    public $newLogotipo;

    public $confirmingEditoraAdd = false;
    public $confirmingEditoraDeletion = false;

    public function render()
    {
        $editoras = \App\Models\Editora::all();

        if ($this->search) {
            $editoras = $editoras->filter(function($e) {
                return stripos($e->nome, $this->search) !== false;
            });
        }

        if ($this->sortField) {
            $editoras = $this->sortAsc 
                ? $editoras->sortBy($this->sortField) 
                : $editoras->sortByDesc($this->sortField);
        }

        return view('livewire.editoras.index', [
            'editoras' => $editoras
        ])->layout('layouts.app');
    }

    public function confirmEditoraAdd()
    {
        $this->reset(['editoraId', 'nome', 'logotipo', 'newLogotipo']);
        $this->confirmingEditoraAdd = true;
    }

    public function confirmEditoraEdit($id)
    {
        $this->reset(['editoraId', 'nome', 'logotipo', 'newLogotipo']);
        $editora = \App\Models\Editora::find($id);
        $this->editoraId = $editora->id;
        $this->nome = $editora->nome;
        $this->logotipo = $editora->logotipo;
        
        $this->confirmingEditoraAdd = true;
    }

    public function saveEditora()
    {
        $this->validate([
            'nome' => 'required|string',
            'newLogotipo' => 'nullable|image|max:2048'
        ]);

        $path = $this->logotipo;
        if ($this->newLogotipo) {
            if ($path && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
            }
            $path = $this->newLogotipo->store('logotipos', 'public');
        }

        if ($this->editoraId) {
            $editora = \App\Models\Editora::findOrFail($this->editoraId);
            $editora->update(['nome' => $this->nome, 'logotipo' => $path]);
        } else {
            \App\Models\Editora::create(['nome' => $this->nome, 'logotipo' => $path]);
        }

        $this->confirmingEditoraAdd = false;
    }

    public function confirmEditoraDeletion($id)
    {
        $this->editoraId = $id;
        $this->confirmingEditoraDeletion = true;
    }

    public function deleteEditora()
    {
        $editora = \App\Models\Editora::find($this->editoraId);
        if ($editora->logotipo && \Illuminate\Support\Facades\Storage::disk('public')->exists($editora->logotipo)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($editora->logotipo);
        }
        $editora->delete();
        $this->confirmingEditoraDeletion = false;
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortAsc = true;
        }
        $this->sortField = $field;
    }
}
