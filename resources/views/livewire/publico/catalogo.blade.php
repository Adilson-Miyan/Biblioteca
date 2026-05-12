<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#cba77d] leading-tight font-serif tracking-wider">
            {{ __('Catálogo Público') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Fixed Toast Notifications (High Z-Index) -->
            <div class="fixed top-4 right-4 z-[9999] space-y-2">
                @if (session()->has('success'))
                    <div class="bg-green-900 border border-green-500 text-green-300 px-6 py-4 rounded-lg shadow-2xl flex items-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="font-bold">{{ session('success') }}</span>
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="bg-red-900 border border-red-500 text-red-300 px-6 py-4 rounded-lg shadow-2xl flex items-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        <span class="font-bold">{{ session('error') }}</span>
                    </div>
                @endif
            </div>

            <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-8">
                <div>
                    <h2 class="text-3xl font-serif font-bold text-[#cba77d] tracking-wide drop-shadow-md">Obras Disponíveis</h2>
                    <p class="text-gray-400 mt-1 text-sm">Pesquise e requisite os seus livros favoritos.</p>
                </div>
                <div class="w-full md:w-1/3">
                    <input type="text" wire:model.live="search" placeholder="Pesquisar por ISBN ou Nome..." class="w-full bg-[#1c1816] border border-[#3e2b1e] text-white focus:border-[#b58f5c] focus:ring-[#b58f5c] rounded-full px-6 py-3 shadow-lg" />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @forelse ($livros as $livro)
                    <div class="bg-[#2d2019] border border-[#3e2b1e] rounded-2xl overflow-hidden shadow-xl hover:shadow-[#b58f5c]/20 hover:-translate-y-2 transition-all duration-300 flex flex-col group relative">
                        
                        @if($livro->isDisponivel())
                            <div class="absolute top-4 right-4 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md z-10">Disponível</div>
                        @else
                            <div class="absolute top-4 right-4 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md z-10">Indisponível</div>
                        @endif

                        <div class="h-64 overflow-hidden relative cursor-pointer" wire:click="openDetalhe({{ $livro->id }})">
                            @if($livro->imagem_capa)
                                <img src="{{ Storage::url($livro->imagem_capa) }}" alt="{{ $livro->nome }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 opacity-90 group-hover:opacity-100">
                            @else
                                <div class="w-full h-full bg-[#1c1816] flex items-center justify-center text-gray-500">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-[#2d2019] via-transparent to-transparent opacity-80"></div>
                        </div>
                        
                        <div class="p-6 flex flex-col flex-grow">
                            <h3 class="text-xl font-bold text-white mb-2 line-clamp-1" title="{{ $livro->nome }}">{{ $livro->nome }}</h3>
                            <p class="text-sm text-[#b58f5c] mb-4 line-clamp-1 font-semibold">{{ $livro->autores->pluck('nome')->implode(', ') }}</p>
                            
                            <div class="mt-auto pt-4 border-t border-[#3e2b1e] flex justify-between items-center">
                                <span class="text-xs text-gray-400 font-mono">ISBN: {{ $livro->isbn }}</span>
                                <button wire:click="openDetalhe({{ $livro->id }})" class="text-[#b58f5c] hover:text-white font-bold text-sm uppercase tracking-wider transition-colors flex items-center">
                                    Detalhes
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-gray-500 bg-[#2d2019] rounded-2xl border border-[#3e2b1e]">
                        <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        Nenhum livro encontrado no catálogo.
                    </div>
                @endforelse
            </div>

            <div class="mt-12">
                {{ $livros->links() }}
            </div>
        </div>
    </div>

    @if($livroSelecionado)
    <x-dialog-modal wire:model.live="showModal" maxWidth="2xl">
        <x-slot name="title">
            <div class="font-serif text-[#cba77d] text-2xl border-b border-[#3e2b1e] pb-4">
                Detalhes da Obra
            </div>
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-left mt-4">
                <!-- Left side: Cover & Action -->
                <div class="col-span-1 flex flex-col gap-5">
                    <div class="w-full max-w-[200px] mx-auto md:max-w-full">
                        @if($livroSelecionado->imagem_capa)
                            <img src="{{ Storage::url($livroSelecionado->imagem_capa) }}" alt="{{ $livroSelecionado->nome }}" class="w-full aspect-[2/3] object-cover rounded-xl shadow-lg border border-[#3e2b1e]">
                        @else
                            <div class="w-full aspect-[2/3] rounded-xl bg-[#1c1816] flex items-center justify-center text-gray-500 border border-[#3e2b1e]">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-2">
                        @auth
                            @if($livroSelecionado->isDisponivel())
                                <button wire:click="requisitar({{ $livroSelecionado->id }})" class="w-full bg-[#b58f5c] hover:bg-[#cba77d] text-[#1c1816] py-3 rounded-xl font-bold tracking-wide transition-colors uppercase text-sm">
                                    Requisitar Livro
                                </button>
                            @else
                                <button disabled class="w-full bg-red-900/50 text-red-300 border border-red-500/50 py-3 rounded-xl font-bold tracking-wide uppercase text-sm cursor-not-allowed">
                                    Indisponível
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="block text-center w-full bg-[#1c1816] border border-[#3e2b1e] hover:border-[#b58f5c] text-white py-3 rounded-xl tracking-wide transition-colors uppercase text-sm font-bold">
                                Login para Requisitar
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Right side: Info & History -->
                <div class="col-span-1 md:col-span-2 space-y-6">
                    <div>
                        <h2 class="text-3xl font-serif font-bold text-white leading-tight mb-2">{{ $livroSelecionado->nome }}</h2>
                        <p class="text-lg text-[#b58f5c] font-medium">{{ $livroSelecionado->autores->pluck('nome')->implode(', ') }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm bg-[#1c1816] p-4 rounded-xl border border-[#3e2b1e]">
                        <div><span class="text-gray-500 block text-xs uppercase tracking-wide mb-1">Editora</span><span class="text-gray-300 font-semibold">{{ $livroSelecionado->editora->nome ?? 'N/D' }}</span></div>
                        <div><span class="text-gray-500 block text-xs uppercase tracking-wide mb-1">ISBN</span><span class="text-gray-300 font-mono">{{ $livroSelecionado->isbn }}</span></div>
                    </div>

                    <div class="bg-[#1c1816] p-4 rounded-xl border border-[#3e2b1e]">
                        <h3 class="text-sm font-bold text-gray-300 uppercase tracking-wide mb-2">Sinopse</h3>
                        <p class="text-sm text-gray-400 leading-relaxed">{{ $livroSelecionado->bibliografia ?? 'Sem sinopse disponível.' }}</p>
                    </div>

                    <!-- Histórico de Requisições -->
                    <div>
                        <h3 class="text-sm font-bold text-[#cba77d] uppercase tracking-wide mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Histórico de Requisições
                        </h3>
                        
                        @php
                            $historico = collect();
                            if(Auth::check()) {
                                if(Auth::user()->isAdmin()) {
                                    $historico = $livroSelecionado->requisicaos->sortByDesc('created_at');
                                } else {
                                    $historico = $livroSelecionado->requisicaos->where('user_id', Auth::id())->sortByDesc('created_at');
                                }
                            }
                        @endphp

                        @if($historico->isEmpty())
                            <p class="text-gray-500 text-sm italic bg-[#1c1816] p-4 rounded-xl border border-[#3e2b1e] text-center">
                                @if(Auth::check() && !Auth::user()->isAdmin())
                                    Ainda não requisitou este livro.
                                @else
                                    Nenhum histórico disponível para este livro.
                                @endif
                            </p>
                        @else
                            <div class="bg-[#1c1816] border border-[#3e2b1e] rounded-xl max-h-40 overflow-y-auto">
                                <table class="w-full text-left text-sm">
                                    <thead class="text-xs text-gray-400 uppercase bg-[#1c1816] sticky top-0 border-b border-[#3e2b1e]">
                                        <tr>
                                            <th class="px-4 py-3 font-medium">Leitor</th>
                                            <th class="px-4 py-3 font-medium">Data</th>
                                            <th class="px-4 py-3 font-medium text-right">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#3e2b1e]">
                                        @foreach($historico as $req)
                                            <tr class="hover:bg-[#2d2019] transition-colors">
                                                <td class="px-4 py-3 text-sm text-gray-300 font-medium">
                                                    @if(Auth::user()->isAdmin())
                                                        {{ $req->user->name }}
                                                    @else
                                                        Você
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-400">{{ \Carbon\Carbon::parse($req->data_requisicao)->format('d/m/Y') }}</td>
                                                <td class="px-4 py-3 text-right">
                                                    @if($req->status === 'pendente')
                                                        <span class="text-yellow-400 text-xs px-2 py-1 bg-yellow-900/30 rounded-full border border-yellow-700/50">Ativa</span>
                                                    @elseif($req->status === 'entregue')
                                                        <span class="text-green-400 text-xs px-2 py-1 bg-green-900/30 rounded-full border border-green-700/50">Entregue</span>
                                                    @else
                                                        <span class="text-red-400 text-xs px-2 py-1 bg-red-900/30 rounded-full border border-red-700/50">Atrasado</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showModal', false)" wire:loading.attr="disabled">
                {{ __('Fechar') }}
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>
    @endif
</div>
