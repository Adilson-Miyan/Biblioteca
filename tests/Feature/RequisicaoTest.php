<?php

use App\Models\User;
use App\Models\Livro;
use App\Models\Editora;
use App\Models\Requisicao;
use Livewire\Livewire;
use App\Livewire\Publico\Catalogo;
use App\Livewire\Requisicoes\Index as RequisicoesIndex;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutExceptionHandling();
    $this->editora = Editora::create(['nome' => 'Porto Editora']);
});

it('can create a book request', function () {
    \Illuminate\Support\Facades\Mail::fake();

    $user = User::factory()->create();
    $livro = Livro::create([
        'isbn' => '1234567890',
        'nome' => 'Livro Teste',
        'editora_id' => $this->editora->id,
        'preco' => 10.00,
    ]);

    Livewire::actingAs($user)
        ->test(Catalogo::class)
        ->call('requisitar', $livro->id)
        ->assertHasNoErrors();

    expect(Requisicao::where('user_id', $user->id)->where('livro_id', $livro->id)->exists())->toBeTrue();
});

it('cannot create a request without a valid book', function () {
    $user = User::factory()->create();
    
    Livewire::actingAs($user)
        ->test(Catalogo::class)
        ->call('requisitar', 999) // Invalid ID
        ->assertHasErrors(); // Wait, in Livewire usually it throws an Exception or 404 for findOrFail. We can just expect it to fail, or we can check status.
})->throws(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

it('can return a book', function () {
    \Illuminate\Support\Facades\Mail::fake();

    $user = User::factory()->create();
    $livro = Livro::create([
        'isbn' => '1234567891',
        'nome' => 'Livro Teste 2',
        'editora_id' => $this->editora->id,
        'preco' => 10.00,
    ]);

    $requisicao = Requisicao::create([
        'user_id' => $user->id,
        'livro_id' => $livro->id,
        'data_requisicao' => now(),
        'data_fim_prevista' => now()->addDays(5),
        'status' => 'pendente',
    ]);

    Livewire::actingAs($user)
        ->test(RequisicoesIndex::class)
        ->call('confirmRececao', $requisicao->id)
        ->set('dataRececao', now()->format('Y-m-d'))
        ->call('markAsReceived')
        ->assertHasNoErrors();

    expect($requisicao->fresh()->status)->toBe('entregue');
});

it('lists requests correctly for a specific user', function () {
    $user1 = User::factory()->create(['role' => 'cidadao']);
    $user2 = User::factory()->create(['role' => 'cidadao']);
    
    $livro1 = Livro::create(['isbn' => 'A1', 'nome' => 'L1', 'editora_id' => $this->editora->id, 'preco' => 10]);
    $livro2 = Livro::create(['isbn' => 'A2', 'nome' => 'L2', 'editora_id' => $this->editora->id, 'preco' => 10]);

    Requisicao::create([
        'user_id' => $user1->id,
        'livro_id' => $livro1->id,
        'data_requisicao' => now(),
        'data_fim_prevista' => now()->addDays(5),
        'status' => 'pendente',
    ]);

    Requisicao::create([
        'user_id' => $user2->id,
        'livro_id' => $livro2->id,
        'data_requisicao' => now(),
        'data_fim_prevista' => now()->addDays(5),
        'status' => 'pendente',
    ]);

    Livewire::actingAs($user1)
        ->test(RequisicoesIndex::class)
        ->assertViewHas('requisicoes', function ($requisicoes) use ($livro1) {
            return $requisicoes->contains('livro_id', $livro1->id) && $requisicoes->count() === 1;
        });
});

it('cannot request a book without stock (already borrowed)', function () {
    \Illuminate\Support\Facades\Mail::fake();

    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    
    $livro = Livro::create([
        'isbn' => '1234567899',
        'nome' => 'Livro Sem Stock',
        'editora_id' => $this->editora->id,
        'preco' => 10.00,
    ]);

    // User 1 requests the book (takes the stock)
    Requisicao::create([
        'user_id' => $user1->id,
        'livro_id' => $livro->id,
        'data_requisicao' => now(),
        'data_fim_prevista' => now()->addDays(5),
        'status' => 'pendente',
    ]);

    // User 2 tries to request
    Livewire::actingAs($user2)
        ->test(Catalogo::class)
        ->call('requisitar', $livro->id)
        ->assertHasNoErrors();
        
    expect(Requisicao::where('user_id', $user2->id)->where('livro_id', $livro->id)->exists())->toBeFalse();
});
