<x-mail::message>
@if ($forAdmin)
# Nova requisição #{{ str_pad((string) $requisicao->id, 5, '0', STR_PAD_LEFT) }}

Foi registada uma nova requisição na biblioteca.

@else
# Requisição confirmada #{{ str_pad((string) $requisicao->id, 5, '0', STR_PAD_LEFT) }}

Olá **{{ $requisicao->user->name }}**,

Este email confirma que a sua requisição foi recebida.

@endif

@php
$livro = $requisicao->livro;
$autoresLista = ($livro->relationLoaded('autores') && $livro->autores->isNotEmpty())
    ? $livro->autores->pluck('nome')->join(', ')
    : null;
@endphp
**{{ $livro->nome }}** @if ($autoresLista) · {{ $autoresLista }} @endif

@php
    $capaPath = $livro->imagem_capa;
    $capaExiste = $capaPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($capaPath);
@endphp
@if ($capaExiste)
<x-mail::panel>
<img src="{{ $message->embed(\Illuminate\Support\Facades\Storage::disk('public')->path($capaPath)) }}" alt="Capa do livro" style="display:block;max-width:220px;width:100%;height:auto;border-radius:8px;margin-left:auto;margin-right:auto;">
</x-mail::panel>
@endif

**Detalhes da requisição**
- Data da requisição: {{ \Carbon\Carbon::parse($requisicao->data_requisicao)->format('d/m/Y') }}
- Data limite de entrega: {{ \Carbon\Carbon::parse($requisicao->data_fim_prevista)->format('d/m/Y') }}
@if ($forAdmin)
- Cidadão: {{ $requisicao->user->name }} ({{ $requisicao->user->email }})
@endif

@if ($forAdmin)
<x-mail::button :url="route('requisicoes.index')">
Ver requisições
</x-mail::button>
@else
<x-mail::button :url="route('requisicoes.index')">
As minhas requisições
</x-mail::button>
@endif

Obrigado,<br>
{{ config('app.name') }}
</x-mail::message>
