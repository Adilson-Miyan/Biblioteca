<x-app-layout>
    <div class="min-h-screen bg-[#1c1816] text-white font-sans py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-[#b58f5c] tracking-tight">Visão Geral</h2>
                    <p class="text-gray-300 mt-1">Bem-vindo ao Painel de Controlo da Biblioteca Digital.</p>
                </div>
            </div>
            <div class="hero rounded-2xl overflow-hidden shadow-xl" style="background: linear-gradient(to right, #453225, #261912);">
                <div class="hero-content text-center py-16 px-8 flex-col">
                    <div class="max-w-3xl">
                        <h1 class="mb-8 text-4xl md:text-5xl font-serif font-bold text-[#cba77d] leading-tight tracking-wide">
                            Bem Vindo a Biblioteca<br>Digital
                        </h1>
                        <a href="{{ route('livros.index') }}" class="inline-flex items-center justify-center px-8 py-2.5 bg-[#ad8557] text-[#261912] font-semibold rounded-full hover:bg-[#cba77d] transition-colors text-sm shadow-md">
                            Explorar Catálogo <span class="ml-2 font-normal">&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="card bg-[#2d2019] shadow-xl hover:shadow-2xl transition-all duration-300 border border-[#3e2b1e] group">
                    <div class="card-body flex-row items-center p-6">
                        <div class="p-4 rounded-2xl bg-[#3e2b1e] text-[#b58f5c] group-hover:scale-110 group-hover:bg-[#b58f5c] group-hover:text-[#3e2b1e] transition-all duration-300 shadow-inner">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-8 h-8 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <div class="ml-6">
                            <div class="text-gray-400 text-sm font-medium uppercase tracking-wider">Total de Livros</div>
                            <div class="text-3xl font-bold text-white mt-1">{{ $stats['livros'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
                <div class="card bg-[#2d2019] shadow-xl hover:shadow-2xl transition-all duration-300 border border-[#3e2b1e] group">
                    <div class="card-body flex-row items-center p-6">
                        <div class="p-4 rounded-2xl bg-[#3e2b1e] text-[#b58f5c] group-hover:scale-110 group-hover:bg-[#b58f5c] group-hover:text-[#3e2b1e] transition-all duration-300 shadow-inner">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-8 h-8 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div class="ml-6">
                            <div class="text-gray-400 text-sm font-medium uppercase tracking-wider">Autores Registados</div>
                            <div class="text-3xl font-bold text-white mt-1">{{ $stats['autores'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
                <div class="card bg-[#2d2019] shadow-xl hover:shadow-2xl transition-all duration-300 border border-[#3e2b1e] group">
                    <div class="card-body flex-row items-center p-6">
                        <div class="p-4 rounded-2xl bg-[#3e2b1e] text-[#b58f5c] group-hover:scale-110 group-hover:bg-[#b58f5c] group-hover:text-[#3e2b1e] transition-all duration-300 shadow-inner">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-8 h-8 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div class="ml-6">
                            <div class="text-gray-400 text-sm font-medium uppercase tracking-wider">Parcerias Editoriais</div>
                            <div class="text-3xl font-bold text-white mt-1">{{ $stats['editoras'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card bg-[#2d2019] shadow-2xl rounded-3xl border border-[#3e2b1e]">
                <div class="card-body p-8">
                    <div class="flex items-center justify-between mb-8 pb-4 border-b border-[#3e2b1e]">
                        <h2 class="text-2xl font-bold text-[#e8c39e] flex items-center">
                            <svg class="w-7 h-7 mr-3 text-[#b58f5c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                            Últimas Adições
                        </h2>
                        <a href="{{ route('livros.index') }}" class="btn btn-sm btn-ghost hover:bg-[#3e2b1e] text-[#b58f5c] border border-[#b58f5c] hover:border-[#b58f5c] rounded-full px-6">
                            Ver todos <span class="ml-2">&rarr;</span>
                        </a>
                    </div>
                    
                    @if(isset($recentBooks) && $recentBooks->count() > 0)
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6">
                            @foreach($recentBooks as $livro)
                                <div class="group relative rounded-2xl overflow-hidden bg-[#3e2b1e] shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 cursor-pointer border border-[#2a1c13] hover:border-[#b58f5c]">
                                    <figure class="aspect-[2/3] w-full overflow-hidden relative bg-[#2a1c13]">
                                        @if($livro->imagem_capa)
                                            <img src="{{ Storage::url($livro->imagem_capa) }}" alt="Capa" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 opacity-90 group-hover:opacity-100" />
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center text-[#5c4033] transition-transform duration-700 group-hover:scale-110">
                                                <svg class="w-16 h-16 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                                <span class="text-sm font-bold tracking-widest uppercase opacity-40">Sem Capa</span>
                                            </div>
                                        @endif
                                        <div class="absolute inset-0 bg-gradient-to-t from-[#1a120c] via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                        
                                        <div class="absolute top-3 right-3">
                                            <span class="badge badge-sm border-none bg-[#b58f5c] text-[#3e2b1e] font-bold shadow-lg shadow-black/50">Novo</span>
                                        </div>
                                    </figure>
                                    
                                    <div class="p-4 relative z-10 bg-gradient-to-t from-[#3e2b1e] via-[#3e2b1e] to-transparent -mt-10 pt-12">
                                        <h3 class="text-sm font-bold text-white truncate mb-1" title="{{ $livro->nome }}">{{ $livro->nome }}</h3>
                                        <div class="flex items-center text-xs text-[#b58f5c] font-medium truncate mb-2" title="{{ $livro->autores->pluck('nome')->implode(', ') }}">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            {{ $livro->autores->pluck('nome')->implode(', ') }}
                                        </div>
                                        <div class="flex items-center text-xs text-gray-400 truncate" title="{{ $livro->editora->nome ?? 'N/A' }}">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            {{ $livro->editora->nome ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-20 bg-[#3e2b1e] rounded-2xl shadow-inner border border-[#2a1c13]">
                            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-[#2a1c13] mb-6">
                                <svg class="h-12 w-12 text-[#b58f5c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <h3 class="text-3xl font-bold text-[#e8c39e] mb-3">O acervo está vazio</h3>
                            <p class="text-lg text-gray-400 max-w-md mx-auto leading-relaxed">Ainda não existem livros registados no sistema. Comece a criar a sua biblioteca digital hoje mesmo.</p>
                            <div class="mt-8">
                                <a href="{{ route('livros.index') }}" class="btn bg-[#b58f5c] hover:bg-[#d4a86f] text-[#3e2b1e] border-none shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 px-8 py-3 rounded-full font-bold">
                                    Adicionar o primeiro livro
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
