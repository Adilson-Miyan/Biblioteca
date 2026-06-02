<x-mail::message>
# Olá, {{ $cart->user->name }}!

Notámos que adicionou **{{ $cart->items->count() }} {{ $cart->items->count() === 1 ? 'livro' : 'livros' }}** ao seu carrinho há mais de uma hora, mas ainda não finalizou a encomenda.

@if($cart->items->isNotEmpty())
**No seu carrinho:**
@foreach($cart->items as $item)
- {{ $item->livro->nome }}
@endforeach
@endif

Precisa de alguma ajuda com o processo de compra ou teve algum problema?

<x-mail::button :url="route('cart.index')">
Retomar a Encomenda
</x-mail::button>

Se tiver alguma dúvida, não hesite em contactar-nos respondendo a este e-mail.

Obrigado,<br>
A Equipa da {{ config('app.name') }}
</x-mail::message>
