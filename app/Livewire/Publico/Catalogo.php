<?php

namespace App\Livewire\Publico;

use Livewire\Component;

use Livewire\WithPagination;
use App\Models\Livro;
use App\Models\Requisicao;
use Illuminate\Support\Facades\Auth;
use App\Mail\RequisicaoCriada;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Carbon\Carbon;

class Catalogo extends Component
{
    use WithPagination;

    public $search = '';
    public $livroSelecionado = null;
    public $showModal = false;

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function requisitar($livroId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // check limit of 3 active requests
        $activeRequestsCount = Requisicao::where('user_id', $user->id)
            ->whereIn('status', ['pendente', 'atrasado'])
            ->count();

        if ($activeRequestsCount >= 3) {
            session()->flash('error', 'Não é possivel porque o limite de livros requisitados é de 3');
            return;
        }

        $livro = Livro::findOrFail($livroId);

        if (!$livro->isDisponivel()) {
            session()->flash('error', 'Este livro não está disponível para requisição.');
            return;
        }

        $requisicao = Requisicao::create([
            'user_id' => $user->id,
            'livro_id' => $livro->id,
            'data_requisicao' => Carbon::now(),
            'data_fim_prevista' => Carbon::now()->addDays(5),
            'status' => 'pendente',
        ]);

        $requisicao->load(['user', 'livro.autores']);

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new RequisicaoCriada($requisicao, forAdmin: true));
        }
        Mail::to($user->email)->send(new RequisicaoCriada($requisicao, forAdmin: false));

        session()->flash('success', 'Requisição efetuada com sucesso!');
    }

    public function openDetalhe($livroId)
    {
        $this->livroSelecionado = Livro::with('autores', 'editora', 'requisicaos.user')->findOrFail($livroId);
        $this->showModal = true;
    }

    public function render()
    {
        $todosLivros = Livro::with('editora', 'autores')->get();

        $livrosFiltrados = $todosLivros->filter(function ($livro) {
            if (empty($this->search)) {
                return true;
            }
            
            $searchStr = mb_strtolower($this->search, 'UTF-8');
            
            if (str_contains(mb_strtolower($livro->nome, 'UTF-8'), $searchStr)) return true;
            if (str_contains(mb_strtolower($livro->isbn, 'UTF-8'), $searchStr)) return true;
            
            foreach ($livro->autores as $autor) {
                if (str_contains(mb_strtolower($autor->nome, 'UTF-8'), $searchStr)) return true;
            }
            
            return false;
        });

        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $perPage = 12;
        $currentItems = $livrosFiltrados->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $livros = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems, 
            $livrosFiltrados->count(), 
            $perPage, 
            $currentPage, 
            ['path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath()]
        );

        return view('livewire.publico.catalogo', [
            'livros' => $livros,
        ])->layout('layouts.app');
    }
}
