<?php

namespace App\Livewire\Livros;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Livro;
use App\Models\Editora;
use App\Models\Autor;

class Importar extends Component
{
    public $searchQuery = '';
    public $results = [];
    public $isLoading = false;

    public function render()
    {
        return view('livewire.livros.importar')->layout('layouts.app');
    }

    public function searchBooks()
    {
        $this->validate([
            'searchQuery' => 'required|string|min:3'
        ]);

        $this->isLoading = true;
        
        try {
            $params = [
                'q' => $this->searchQuery,
                'maxResults' => 12
            ];

            $apiKey = env('GOOGLE_BOOKS_API_KEY');
            if (!empty($apiKey)) {
                $params['key'] = $apiKey;
            }

            $response = Http::withoutVerifying()->get('https://www.googleapis.com/books/v1/volumes', $params);

            if ($response->successful()) {
                $this->results = $response->json('items') ?? [];
            } else {
                session()->flash('error', 'Erro da API (Código ' . $response->status() . '): ' . $response->body());
                $this->results = [];
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Erro de Conexão: ' . $e->getMessage());
            $this->results = [];
        }

        $this->isLoading = false;
    }

    public function importBook($bookIndex)
    {
        if (!isset($this->results[$bookIndex])) {
            session()->flash('error', 'Livro não encontrado nos resultados.');
            return;
        }

        $bookData = $this->results[$bookIndex]['volumeInfo'];

        // Extrair ISBN
        $isbn = null;
        if (isset($bookData['industryIdentifiers'])) {
            foreach ($bookData['industryIdentifiers'] as $identifier) {
                if ($identifier['type'] === 'ISBN_13') {
                    $isbn = $identifier['identifier'];
                    break;
                } elseif ($identifier['type'] === 'ISBN_10' && !$isbn) {
                    $isbn = $identifier['identifier'];
                }
            }
        }
        
        if (!$isbn) {
            $isbn = 'N/A'; // Ou gerar um aleatório
        }

        // Verifica se o livro já existe
        if ($isbn !== 'N/A' && Livro::where('isbn', $isbn)->exists()) {
            session()->flash('error', 'Este livro (ISBN: ' . $isbn . ') já existe na base de dados.');
            return;
        }

        // Processar Editora
        $editoraNome = $bookData['publisher'] ?? 'Editora Desconhecida';
        $editora = Editora::firstOrCreate(['nome' => $editoraNome]);

        // Processar Autores
        $autoresIds = [];
        if (isset($bookData['authors'])) {
            foreach ($bookData['authors'] as $autorNome) {
                $autor = Autor::firstOrCreate(['nome' => $autorNome]);
                $autoresIds[] = $autor->id;
            }
        }

        // Processar Imagem
        $imagemPath = null;
        if (isset($bookData['imageLinks']['thumbnail'])) {
            $imageUrl = str_replace('http:', 'https:', $bookData['imageLinks']['thumbnail']);
            try {
                $imageContents = Http::withoutVerifying()->get($imageUrl)->body();
                $filename = 'capas_livros/' . Str::random(40) . '.jpg';
                Storage::disk('public')->put($filename, $imageContents);
                $imagemPath = $filename;
            } catch (\Exception $e) {
                // Falha ao baixar imagem, ignorar
            }
        }

        // Criar Livro
        $livro = Livro::create([
            'isbn' => $isbn,
            'nome' => $bookData['title'] ?? 'Sem Título',
            'editora_id' => $editora->id,
            'bibliografia' => $bookData['description'] ?? null,
            'imagem_capa' => $imagemPath,
            'preco' => null, // Google Books geralmente não dá preço
        ]);

        if (!empty($autoresIds)) {
            $livro->autores()->sync($autoresIds);
        }

        session()->flash('success', 'Livro "' . $livro->nome . '" importado com sucesso!');
        
        // Remove do array de resultados para o utilizador não o importar de novo acidentalmente
        unset($this->results[$bookIndex]);
        $this->results = array_values($this->results);
    }
}
