<div>
    <h1>Nova Avaliação Submetida</h1>
    <p>O cidadão {{ $review->requisicao->user->name }} submeteu uma nova avaliação para o livro "{{ $review->requisicao->livro->nome }}".</p>
    
    <h2>Detalhes da Avaliação:</h2>
    <ul>
        <li><strong>Classificação:</strong> {{ $review->rating }} / 5</li>
        <li><strong>Comentário:</strong> {{ $review->comment }}</li>
    </ul>

    <p>Por favor, aceda à plataforma para aprovar ou recusar esta avaliação.</p>
    <a href="{{ route('reviews.index') }}">Gerir Avaliações</a>
</div>
