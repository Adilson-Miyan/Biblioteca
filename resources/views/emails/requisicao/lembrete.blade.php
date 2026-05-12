<x-mail::message>
# Lembrete de devolução #{{ str_pad((string) $requisicao->id, 5, '0', STR_PAD_LEFT) }}

Olá **{{ $requisicao->user->name }}**,

Este é um lembrete automático: a data limite para devolução da obra **{{ $requisicao->livro->nome }}** é **amanhã**, **{{ \Carbon\Carbon::parse($requisicao->data_fim_prevista)->format('d/m/Y') }}**.

@php
    $capaPath = $requisicao->livro->imagem_capa ?? null;
    $capaExiste = $capaPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($capaPath);
@endphp
@if ($capaExiste)
<x-mail::panel>
<img src="{{ $message->embed(\Illuminate\Support\Facades\Storage::disk('public')->path($capaPath)) }}" alt="Capa do livro" style="display:block;max-width:220px;width:100%;height:auto;border-radius:8px;margin-left:auto;margin-right:auto;">
</x-mail::panel>
@endif

Por favor efetue a devolução dentro do prazo para que outros leitores também possam requisitar a obra.

<x-mail::button :url="route('requisicoes.index')">
Ver as minhas requisições
</x-mail::button>

Cumprimentos,<br>
{{ config('app.name') }}
</x-mail::message>
