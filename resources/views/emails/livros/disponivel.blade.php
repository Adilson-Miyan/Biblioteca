<div>
    <h1>O livro que pediu já se encontra disponível!</h1>
    <p>Olá,</p>
    <p>Temos boas notícias! O livro "{{ $livro->nome }}" já se encontra disponível na biblioteca.</p>
    
    <p>Pode agora proceder à sua requisição através da nossa plataforma.</p>
    <a href="{{ route('catalogo') }}">Aceder ao Catálogo</a>

    <p>Obrigado por utilizar a nossa biblioteca.</p>
</div>
