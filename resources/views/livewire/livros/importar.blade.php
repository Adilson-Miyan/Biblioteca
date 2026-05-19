<div>
    <div class="min-h-screen bg-[#1c1816] text-white font-sans py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-[#b58f5c] tracking-tight">Importar Livros</h2>
                    <p class="text-gray-400 mt-1">Pesquise e importe livros da Google Books API.</p>
                </div>
                <div>
                    <a href="{{ route('livros.index') }}" wire:navigate class="btn btn-outline border-[#3e2b1e] text-gray-300 hover:bg-[#2d2019] hover:text-white">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Voltar aos Livros
                    </a>
                </div>
            </div>

            @if (session()->has('success'))
                <div class="alert alert-success bg-green-900/50 border border-green-500 text-green-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-error bg-red-900/50 border border-red-500 text-red-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-[#2d2019] shadow-2xl rounded-3xl p-6 border border-[#3e2b1e]">
                <form wire:submit.prevent="searchBooks" class="flex flex-col sm:flex-row gap-4 mb-6">
                    <input type="text" wire:model="searchQuery" placeholder="Insira o título, autor ou ISBN..." class="input input-bordered flex-1 bg-[#1c1816] border-[#3e2b1e] text-white focus:border-[#b58f5c] focus:ring-[#b58f5c]" required>
                    <button type="submit" class="btn border-none font-bold shadow-lg w-full sm:w-auto hover:bg-[#d4a86f]" style="background-color: #b58f5c; color: #1c1816;" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="searchBooks">
                            <svg class="w-5 h-5 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Pesquisar API
                        </span>
                        <span wire:loading wire:target="searchBooks">
                            <span class="loading loading-spinner loading-sm mr-1"></span>
                            A pesquisar...
                        </span>
                    </button>
                </form>

                @if(count($results) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($results as $index => $book)
                            @php
                                $info = $book['volumeInfo'];
                                $title = $info['title'] ?? 'Sem Título';
                                $authors = isset($info['authors']) ? implode(', ', $info['authors']) : 'Autor Desconhecido';
                                $publisher = $info['publisher'] ?? 'Editora Desconhecida';
                                $thumbnail = $info['imageLinks']['thumbnail'] ?? null;
                                
                                $isbn = 'N/A';
                                if (isset($info['industryIdentifiers'])) {
                                    foreach ($info['industryIdentifiers'] as $identifier) {
                                        if ($identifier['type'] === 'ISBN_13') {
                                            $isbn = $identifier['identifier'];
                                            break;
                                        } elseif ($identifier['type'] === 'ISBN_10') {
                                            $isbn = $identifier['identifier'];
                                        }
                                    }
                                }
                            @endphp
                            
                            <div class="bg-[#1c1816] border border-[#3e2b1e] rounded-xl overflow-hidden flex flex-col h-full hover:border-[#b58f5c] transition-colors">
                                <div class="p-4 flex gap-4 flex-1">
                                    <div class="flex-shrink-0 w-24">
                                        @if($thumbnail)
                                            <img src="{{ str_replace('http:', 'https:', $thumbnail) }}" alt="{{ $title }}" class="w-full h-auto rounded shadow-md border border-[#3e2b1e]">
                                        @else
                                            <div class="w-full h-32 rounded bg-[#2d2019] flex items-center justify-center text-gray-500 border border-[#3e2b1e]">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col justify-start w-full overflow-hidden">
                                        <h3 class="font-bold text-lg text-white leading-tight mb-1 line-clamp-2" title="{{ $title }}">{{ $title }}</h3>
                                        <p class="text-sm text-[#b58f5c] font-medium mb-1 truncate" title="{{ $authors }}">{{ $authors }}</p>
                                        <div class="text-xs text-gray-400 mt-auto space-y-1">
                                            <p><span class="font-semibold text-gray-500">ISBN:</span> {{ $isbn }}</p>
                                            <p class="truncate" title="{{ $publisher }}"><span class="font-semibold text-gray-500">Edt:</span> {{ $publisher }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 bg-[#2d2019] border-t border-[#3e2b1e] mt-auto">
                                    <button wire:click="importBook({{ $index }})" class="btn btn-sm w-full border-none font-bold text-[#1c1816] hover:bg-[#d4a86f]" style="background-color: #b58f5c;" wire:loading.attr="disabled" wire:target="importBook({{ $index }})">
                                        <span wire:loading.remove wire:target="importBook({{ $index }})">Importar Livro</span>
                                        <span wire:loading wire:target="importBook({{ $index }})">
                                            <span class="loading loading-spinner loading-xs mr-1"></span> a importar...
                                        </span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @elseif($searchQuery !== '' && empty($results))
                    <div class="text-center py-12 text-gray-400">
                        <svg class="w-16 h-16 mx-auto text-[#3e2b1e] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-lg">Nenhum livro encontrado para a pesquisa "{{ $searchQuery }}".</p>
                    </div>
                @else
                    <div class="text-center py-12 text-gray-400">
                        <svg class="w-16 h-16 mx-auto text-[#3e2b1e] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <p class="text-lg">Pesquise por livros utilizando o campo acima.</p>
                    </div>
                @endif
            </div>
            
        </div>
    </div>
</div>
