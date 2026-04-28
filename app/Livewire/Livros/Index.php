<?php

namespace App\Livewire\Livros;

use Livewire\Component;

class Index extends Component
{
    use \Livewire\WithFileUploads;

    public $search = '';
    public $sortField = 'nome';
    public $sortAsc = true;

    public $livroId;
    public $isbn;
    public $nome;
    public $editora_id;
    public $bibliografia;
    public $preco;
    public $imagem_capa;
    public $newImagemCapa;
    public $autor_ids = [];

    public $confirmingLivroAdd = false;
    public $confirmingLivroDeletion = false;

    public function render()
    {
        $livros = \App\Models\Livro::with(['editora', 'autores'])->get();

        if ($this->search) {
            $livros = $livros->filter(function($l) {
                return stripos($l->nome, $this->search) !== false 
                    || stripos($l->isbn, $this->search) !== false
                    || ($l->editora && stripos($l->editora->nome, $this->search) !== false);
            });
        }

        if ($this->sortField) {
            $livros = $this->sortAsc 
                ? $livros->sortBy($this->sortField) 
                : $livros->sortByDesc($this->sortField);
        }

        return view('livewire.livros.index', [
            'livros' => $livros,
            'editoras_options' => \App\Models\Editora::all(),
            'autores_options' => \App\Models\Autor::all()
        ])->layout('layouts.app');
    }

    public function confirmLivroAdd()
    {
        $this->reset(['livroId', 'isbn', 'nome', 'editora_id', 'bibliografia', 'preco', 'imagem_capa', 'newImagemCapa', 'autor_ids']);
        $this->confirmingLivroAdd = true;
    }

    public function confirmLivroEdit($id)
    {
        $this->reset(['livroId', 'isbn', 'nome', 'editora_id', 'bibliografia', 'preco', 'imagem_capa', 'newImagemCapa', 'autor_ids']);
        $livro = \App\Models\Livro::with('autores')->find($id);
        
        $this->livroId = $livro->id;
        $this->isbn = $livro->isbn;
        $this->nome = $livro->nome;
        $this->editora_id = $livro->editora_id;
        $this->bibliografia = $livro->bibliografia;
        $this->preco = $livro->preco;
        $this->imagem_capa = $livro->imagem_capa;
        $this->autor_ids = $livro->autores->pluck('id')->toArray();
        
        $this->confirmingLivroAdd = true;
    }

    public function saveLivro()
    {
        $this->validate([
            'isbn' => 'required|string',
            'nome' => 'required|string',
            'editora_id' => 'required|exists:editoras,id',
            'bibliografia' => 'nullable|string',
            'preco' => 'nullable|numeric|min:0',
            'newImagemCapa' => 'nullable|image|max:2048',
            'autor_ids' => 'required|array',
            'autor_ids.*' => 'exists:autores,id'
        ]);

        $path = $this->imagem_capa;
        if ($this->newImagemCapa) {
            if ($path && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
            }
            $path = $this->newImagemCapa->store('capas_livros', 'public');
        }

        if ($this->livroId) {
            $livro = \App\Models\Livro::findOrFail($this->livroId);
            $livro->update([
                'isbn' => $this->isbn,
                'nome' => $this->nome,
                'editora_id' => $this->editora_id,
                'bibliografia' => $this->bibliografia,
                'preco' => $this->preco === '' ? null : $this->preco,
                'imagem_capa' => $path
            ]);
        } else {
            $livro = \App\Models\Livro::create([
                'isbn' => $this->isbn,
                'nome' => $this->nome,
                'editora_id' => $this->editora_id,
                'bibliografia' => $this->bibliografia,
                'preco' => $this->preco === '' ? null : $this->preco,
                'imagem_capa' => $path
            ]);
        }

        $livro->autores()->sync($this->autor_ids);

        $this->confirmingLivroAdd = false;
    }

    public function confirmLivroDeletion($id)
    {
        $this->livroId = $id;
        $this->confirmingLivroDeletion = true;
    }

    public function deleteLivro()
    {
        $livro = \App\Models\Livro::find($this->livroId);
        if ($livro->imagem_capa && \Illuminate\Support\Facades\Storage::disk('public')->exists($livro->imagem_capa)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($livro->imagem_capa);
        }
        $livro->delete();
        $this->confirmingLivroDeletion = false;
    }

    public function exportExcel()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\LivrosExport, 'livros.xlsx');
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
