<?php

namespace App\Livewire\Autores;

use Livewire\Component;

class Index extends Component
{
    use \Livewire\WithFileUploads;

    public $search = '';
    public $sortField = 'nome';
    public $sortAsc = true;

    public $autorId;
    public $nome;
    public $foto;
    public $newFoto;

    public $confirmingAutorAdd = false;
    public $confirmingAutorDeletion = false;

    public function render()
    {
        $autores = \App\Models\Autor::all();

        if ($this->search) {
            $autores = $autores->filter(function($a) {
                return stripos($a->nome, $this->search) !== false;
            });
        }

        if ($this->sortField) {
            $autores = $this->sortAsc 
                ? $autores->sortBy($this->sortField) 
                : $autores->sortByDesc($this->sortField);
        }

        return view('livewire.autores.index', [
            'autores' => $autores
        ])->layout('layouts.app');
    }

    public function confirmAutorAdd()
    {
        $this->reset(['autorId', 'nome', 'foto', 'newFoto']);
        $this->confirmingAutorAdd = true;
    }

    public function confirmAutorEdit($id)
    {
        $this->reset(['autorId', 'nome', 'foto', 'newFoto']);
        $autor = \App\Models\Autor::find($id);
        $this->autorId = $autor->id;
        $this->nome = $autor->nome;
        $this->foto = $autor->foto;
        
        $this->confirmingAutorAdd = true;
    }

    public function saveAutor()
    {
        $this->validate([
            'nome' => 'required|string',
            'newFoto' => 'nullable|image|max:2048'
        ]);

        $path = $this->foto;
        if ($this->newFoto) {
            if ($path && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
            }
            $path = $this->newFoto->store('fotos_autores', 'public');
        }

        if ($this->autorId) {
            $autor = \App\Models\Autor::findOrFail($this->autorId);
            $autor->update(['nome' => $this->nome, 'foto' => $path]);
        } else {
            \App\Models\Autor::create(['nome' => $this->nome, 'foto' => $path]);
        }

        $this->confirmingAutorAdd = false;
    }

    public function confirmAutorDeletion($id)
    {
        $this->autorId = $id;
        $this->confirmingAutorDeletion = true;
    }

    public function deleteAutor()
    {
        $autor = \App\Models\Autor::find($this->autorId);
        if ($autor->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($autor->foto)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($autor->foto);
        }
        $autor->delete();
        $this->confirmingAutorDeletion = false;
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
