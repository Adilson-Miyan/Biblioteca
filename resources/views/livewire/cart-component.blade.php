<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#cba77d] leading-tight font-serif tracking-wider">
            {{ __('Carrinho de Compras') }}
        </h2>
    </x-slot>

    <div class="py-12 min-h-screen bg-[#1c1816]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Indicador de passos --}}
            <ol class="flex items-center justify-center gap-2 sm:gap-4 mb-8 text-xs sm:text-sm">
                @foreach ([1 => 'Livros', 2 => 'Morada', 3 => 'Pagamento'] as $num => $label)
                    <li class="flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full font-bold {{ $step >= $num ? 'bg-[#b58f5c] text-[#1c1816]' : 'bg-[#3e2b1e] text-gray-400' }}">{{ $num }}</span>
                        <span class="{{ $step >= $num ? 'text-[#cba77d]' : 'text-gray-500' }} hidden sm:inline">{{ $label }}</span>
                    </li>
                    @if ($num < 3)
                        <span class="text-gray-600">›</span>
                    @endif
                @endforeach
            </ol>

            <div class="bg-[#2d2019] rounded-2xl border border-[#3e2b1e] p-6 shadow-xl">

                {{-- Passo 1: Listagem --}}
                @if ($step === 1)
                    <h3 class="text-2xl font-serif font-bold text-[#cba77d] mb-6">1. Os seus livros</h3>

                    @if ($cart->items->isEmpty())
                        <div class="text-center py-12">
                            <svg width="48" height="48" class="mx-auto text-gray-500 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <p class="text-gray-400 text-lg">O seu carrinho está vazio.</p>
                            <a href="{{ route('catalogo') }}" class="mt-4 inline-block text-[#b58f5c] hover:text-[#cba77d] font-bold">Continuar no catálogo →</a>
                        </div>
                    @else
                        <div class="space-y-4 mb-6">
                            @foreach ($cart->items as $item)
                                <div class="flex items-center gap-4 p-4 bg-[#1c1816] rounded-xl border border-[#3e2b1e]">
                                    <div class="w-16 h-24 flex-shrink-0">
                                        @if ($item->livro->imagem_capa)
                                            <img src="{{ Storage::url($item->livro->imagem_capa) }}" alt="" class="w-full h-full object-cover rounded-lg">
                                        @else
                                            <div class="w-full h-full bg-[#2d2019] rounded-lg flex items-center justify-center border border-[#3e2b1e]">
                                                <svg width="24" height="24" class="text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <h4 class="font-bold text-white truncate">{{ $item->livro->nome }}</h4>
                                        <p class="text-sm text-[#b58f5c]">{{ $item->livro->autores->pluck('nome')->implode(', ') }}</p>
                                    </div>
                                    <div class="text-lg font-bold text-white whitespace-nowrap">{{ number_format($item->livro->preco ?? 0, 2, ',', '.') }} €</div>
                                    <button type="button" wire:click="removerDoCarrinho({{ $item->id }})" class="text-red-500 hover:text-red-400 p-2">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <div class="flex justify-between items-center border-t border-[#3e2b1e] pt-4">
                            <span class="text-gray-300">Total ({{ $cart->items->count() }} {{ $cart->items->count() === 1 ? 'livro' : 'livros' }})</span>
                            <span class="text-2xl font-bold text-white">{{ number_format($total, 2, ',', '.') }} €</span>
                        </div>
                        <button type="button" wire:click="irParaMorada" class="mt-6 w-full bg-[#b58f5c] hover:bg-[#cba77d] text-[#1c1816] py-3 rounded-xl font-bold uppercase tracking-wide">
                            Continuar para morada →
                        </button>
                    @endif
                @endif

                {{-- Passo 2: Morada --}}
                @if ($step === 2)
                    <h3 class="text-2xl font-serif font-bold text-[#cba77d] mb-6">2. Morada de entrega</h3>
                    <label class="block text-sm font-bold text-gray-300 mb-2 uppercase tracking-wide">Morada completa</label>
                    <textarea wire:model="address" rows="4" class="w-full bg-[#1c1816] border border-[#3e2b1e] text-white focus:border-[#b58f5c] focus:ring-[#b58f5c] rounded-xl px-4 py-3" placeholder="Rua, número, código postal, localidade..."></textarea>
                    @error('address') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    <div class="flex gap-3 mt-6">
                        <button type="button" wire:click="voltar" class="flex-1 border border-[#3e2b1e] text-gray-300 py-3 rounded-xl font-bold hover:border-[#b58f5c]">← Voltar</button>
                        <button type="button" wire:click="irParaPagamento" class="flex-1 bg-[#b58f5c] hover:bg-[#cba77d] text-[#1c1816] py-3 rounded-xl font-bold">Continuar →</button>
                    </div>
                @endif

                {{-- Passo 3: Pagamento --}}
                @if ($step === 3)
                    <h3 class="text-2xl font-serif font-bold text-[#cba77d] mb-6">3. Pagamento</h3>
                    <div class="bg-[#1c1816] border border-[#3e2b1e] rounded-xl p-4 mb-6 space-y-2 text-sm text-gray-300">
                        <p><span class="text-gray-500">Itens:</span> {{ $cart->items->count() }}</p>
                        <p><span class="text-gray-500">Total:</span> <strong class="text-white text-lg">{{ number_format($total, 2, ',', '.') }} €</strong></p>
                        <p><span class="text-gray-500">Morada:</span> {{ $address }}</p>
                    </div>
                    <p class="text-gray-400 text-sm mb-4">Será redirecionado para o <strong class="text-[#cba77d]">Stripe</strong> (ambiente de testes). Use um cartão de teste, por exemplo <code class="text-xs bg-[#1c1816] px-1 rounded">4242 4242 4242 4242</code>.</p>
                    <div class="flex gap-3">
                        <button type="button" wire:click="voltar" class="flex-1 border border-[#3e2b1e] text-gray-300 py-3 rounded-xl font-bold hover:border-[#b58f5c]">← Voltar</button>
                        <form method="POST" action="{{ route('checkout.process') }}" class="flex-1">
                            @csrf
                            <button
                                type="submit"
                                class="w-full bg-[#b58f5c] hover:bg-[#cba77d] text-[#1c1816] py-3 rounded-xl font-bold"
                            >
                                Pagar com Stripe
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
