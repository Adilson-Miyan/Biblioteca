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
    Route::get('/livros/importar', \App\Livewire\Livros\Importar::class)->name('livros.importar');
    Route::get('/cidadaos', \App\Livewire\Cidadaos\Index::class)->name('cidadaos.index');
    Route::get('/requisicoes', \App\Livewire\Requisicoes\Index::class)->name('requisicoes.index');
    Route::get('/reviews', \App\Livewire\Reviews\Index::class)->name('reviews.index');
    Route::get('/admin/orders', \App\Livewire\AdminOrders::class)->name('admin.orders.index');
    Route::get('/carrinho', \App\Livewire\CartComponent::class)->name('cart.index');
    Route::post('/checkout/process', [\App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cancel', [\App\Http\Controllers\CheckoutController::class, 'cancel'])->name('checkout.cancel');
});
