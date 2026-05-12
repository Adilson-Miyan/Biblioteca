<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/catalogo', \App\Livewire\Publico\Catalogo::class)->name('catalogo');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $stats = [
            'livros' => \App\Models\Livro::count(),
            'autores' => \App\Models\Autor::count(),
            'editoras' => \App\Models\Editora::count(),
        ];
        $recentBooks = \App\Models\Livro::with('editora', 'autores')->latest()->take(5)->get();
        return view('dashboard', compact('stats', 'recentBooks'));
    })->name('dashboard');

    Route::get('/editoras', \App\Livewire\Editoras\Index::class)->name('editoras.index');
    Route::get('/autores', \App\Livewire\Autores\Index::class)->name('autores.index');
    Route::get('/livros', \App\Livewire\Livros\Index::class)->name('livros.index');
    Route::get('/cidadaos', \App\Livewire\Cidadaos\Index::class)->name('cidadaos.index');
    Route::get('/requisicoes', \App\Livewire\Requisicoes\Index::class)->name('requisicoes.index');
});
