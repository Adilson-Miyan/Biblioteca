<div>
    <h1>Atualização do Estado da sua Avaliação</h1>
    <p>Olá {{ $review->requisicao->user->name }},</p>
    <p>A sua avaliação para o livro "{{ $review->requisicao->livro->nome }}" foi atualizada.</p>
    
    <p><strong>Estado atual:</strong> {{ ucfirst($review->status) }}</p>

    @if($review->status === 'recusado' && $review->justification)
        <p><strong>Justificação:</strong></p>
        <p>{{ $review->justification }}</p>
    @endif

    <p>Obrigado por utilizar a nossa biblioteca.</p>
</div>
