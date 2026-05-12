<div>
    <div class="min-h-screen bg-[#1c1816] text-white font-sans py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-[#b58f5c] tracking-tight">Requisições</h2>
                    <p class="text-gray-400 mt-1">Gerencie e consulte o histórico de requisições.</p>
                </div>
            </div>

            <!-- Indicadores -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-[#2d2019] rounded-2xl p-6 border border-[#3e2b1e] shadow-lg flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400 uppercase tracking-wider font-bold mb-1">Ativas</p>
                        <h3 class="text-3xl font-serif text-[#cba77d]">{{ $activeRequests }}</h3>
                    </div>
                    <div class="p-4 bg-[#1c1816] rounded-full text-[#b58f5c]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="bg-[#2d2019] rounded-2xl p-6 border border-[#3e2b1e] shadow-lg flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400 uppercase tracking-wider font-bold mb-1">Últimos 30 Dias</p>
                        <h3 class="text-3xl font-serif text-[#cba77d]">{{ $requestsLast30Days }}</h3>
                    </div>
                    <div class="p-4 bg-[#1c1816] rounded-full text-[#b58f5c]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <div class="bg-[#2d2019] rounded-2xl p-6 border border-[#3e2b1e] shadow-lg flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400 uppercase tracking-wider font-bold mb-1">Entregues Hoje</p>
                        <h3 class="text-3xl font-serif text-[#cba77d]">{{ $deliveredToday }}</h3>
                    </div>
                    <div class="p-4 bg-[#1c1816] rounded-full text-[#b58f5c]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>

            @if (session()->has('success'))
                <div class="bg-green-900/50 border border-green-500 text-green-300 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-[#2d2019] overflow-hidden shadow-2xl rounded-3xl p-6 border border-[#3e2b1e]">
                
                <div class="flex justify-between items-center mb-6">
                    <input type="text" wire:model.live="search" placeholder="Pesquisar por leitor ou livro..." class="input input-bordered w-full sm:w-1/2 bg-[#1c1816] border-[#3e2b1e] text-white focus:border-[#b58f5c] focus:ring-[#b58f5c]" />
                </div>

                <div class="overflow-x-auto rounded-xl">
                    <table class="table w-full text-left">
                        <thead class="text-[#b58f5c] bg-[#1c1816] border-b-2 border-[#3e2b1e]">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">#</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Leitor</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Livro</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Data Req.</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Fim Previsto</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Estado</th>
                                @if(Auth::user()->isAdmin())
                                    <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider">Ações</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#3e2b1e] bg-[#2d2019]">
                            @forelse($requisicoes as $req)
                                <tr class="hover:bg-[#3e2b1e] transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                        {{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white flex items-center gap-3">
                                        @if($req->user->profile_photo_path)
                                            <img src="{{ Storage::url($req->user->profile_photo_path) }}" class="w-8 h-8 rounded-full object-cover border border-[#3e2b1e]">
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-[#1c1816] text-[#b58f5c] flex items-center justify-center font-bold border border-[#3e2b1e]">{{ substr($req->user->name, 0, 1) }}</div>
                                        @endif
                                        <div class="font-semibold">{{ $req->user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-300">
                                        <div class="font-bold text-white">{{ $req->livro->nome }}</div>
                                        <div class="text-xs text-gray-500 font-mono">ISBN: {{ $req->livro->isbn }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        {{ \Carbon\Carbon::parse($req->data_requisicao)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        {{ \Carbon\Carbon::parse($req->data_fim_prevista)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($req->status === 'pendente')
                                            <span class="text-yellow-400 text-xs px-3 py-1 bg-yellow-900/30 rounded-full border border-yellow-700/50 font-bold uppercase tracking-wider">Ativa</span>
                                        @elseif($req->status === 'entregue')
                                            <span class="text-green-400 text-xs px-3 py-1 bg-green-900/30 rounded-full border border-green-700/50 font-bold uppercase tracking-wider">Entregue</span>
                                        @else
                                            <span class="text-red-400 text-xs px-3 py-1 bg-red-900/30 rounded-full border border-red-700/50 font-bold uppercase tracking-wider">Atrasado</span>
                                        @endif
                                    </td>
                                    @if(Auth::user()->isAdmin())
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if($req->status !== 'entregue')
                                                <button wire:click="confirmRececao({{ $req->id }})" class="btn btn-sm btn-ghost text-green-400 hover:bg-[#1c1816]">Confirmar Receção</button>
                                            @else
                                                <span class="text-gray-500 text-xs italic">{{ \Carbon\Carbon::parse($req->data_rececao)->format('d/m/Y') }} ({{ $req->dias_decorrentes }} dias)</span>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ Auth::user()->isAdmin() ? '7' : '6' }}" class="px-6 py-8 whitespace-nowrap text-center text-sm text-gray-500">
                                        Nenhuma requisição encontrada.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $requisicoes->links() }}
                </div>
            </div>
        </div>

        <x-dialog-modal wire:model.live="confirmingRececao">
            <x-slot name="title">
                <span class="text-green-400 font-bold text-xl">Confirmar Receção de Livro</span>
            </x-slot>

            <x-slot name="content">
                <div class="mt-4">
                    <p class="text-gray-300 mb-4">Insira a data real de receção do livro.</p>
                    <x-label for="dataRececao" value="Data de Receção" class="text-gray-300 font-semibold" />
                    <x-input id="dataRececao" type="date" class="mt-2 block w-full bg-[#2d2019] border-[#3e2b1e] text-white focus:border-[#b58f5c] focus:ring-[#b58f5c] rounded-lg" wire:model="dataRececao" />
                    <x-input-error for="dataRececao" class="mt-2 text-red-400" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('confirmingRececao')" wire:loading.attr="disabled" class="bg-[#3e2b1e] text-white border-none hover:bg-[#2d2019]">
                    Cancelar
                </x-secondary-button>

                <x-button class="ms-3 bg-green-600 hover:bg-green-700 border-none font-bold text-white" wire:click="markAsReceived" wire:loading.attr="disabled">
                    Confirmar Entrega
                </x-button>
            </x-slot>
        </x-dialog-modal>
    </div>
</div>
