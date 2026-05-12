<div>
    <div class="min-h-screen bg-[#1c1816] text-white font-sans py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-[#b58f5c] tracking-tight">Cidadãos</h2>
                    <p class="text-gray-400 mt-1">Gerencie os leitores e os seus históricos.</p>
                </div>
            </div>

            <div class="bg-[#2d2019] overflow-hidden shadow-2xl rounded-3xl p-6 border border-[#3e2b1e]">
                <div class="flex justify-between items-center mb-6">
                    <input type="text" wire:model.live="search" placeholder="Pesquisar por nome ou email..." class="input input-bordered w-full sm:w-1/2 bg-[#1c1816] border-[#3e2b1e] text-white focus:border-[#b58f5c] focus:ring-[#b58f5c]" />
                </div>

                <div class="overflow-x-auto rounded-xl">
                    <table class="table w-full text-left">
                        <thead class="text-[#b58f5c] bg-[#1c1816] border-b-2 border-[#3e2b1e]">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Leitor</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Email</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Data de Registo</th>
                                <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#3e2b1e] bg-[#2d2019]">
                            @forelse($cidadaos as $cidadao)
                                <tr class="hover:bg-[#3e2b1e] transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white flex items-center gap-3">
                                        @if($cidadao->profile_photo_path)
                                            <img src="{{ Storage::url($cidadao->profile_photo_path) }}" class="w-10 h-10 rounded-full object-cover border border-[#3e2b1e]">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-[#1c1816] text-[#b58f5c] flex items-center justify-center font-bold border border-[#3e2b1e] text-lg">{{ substr($cidadao->name, 0, 1) }}</div>
                                        @endif
                                        <div class="font-semibold text-lg">{{ $cidadao->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-400">
                                        {{ $cidadao->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                        {{ \Carbon\Carbon::parse($cidadao->created_at)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button wire:click="openDetalhe({{ $cidadao->id }})" class="btn btn-sm btn-ghost text-[#b58f5c] hover:bg-[#1c1816]">Ver Histórico</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 whitespace-nowrap text-center text-sm text-gray-500">
                                        Nenhum cidadão encontrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $cidadaos->links() }}
                </div>
            </div>
        </div>

        @if($showModal && $cidadaoSelecionado)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
            <div class="bg-[#2d2019] rounded-3xl max-w-4xl w-full max-h-[90vh] overflow-y-auto border border-[#b58f5c] shadow-[0_0_50px_rgba(181,143,92,0.15)] relative">
                <div class="p-6 sm:p-10">
                    <button wire:click="$set('showModal', false)" class="absolute top-6 right-6 text-gray-400 hover:text-white bg-[#1c1816] rounded-full p-2 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>

                    <div class="flex items-center gap-6 mb-8 border-b border-[#3e2b1e] pb-6">
                        @if($cidadaoSelecionado->profile_photo_path)
                            <img src="{{ Storage::url($cidadaoSelecionado->profile_photo_path) }}" class="w-24 h-24 rounded-full object-cover border-2 border-[#b58f5c] shadow-lg">
                        @else
                            <div class="w-24 h-24 rounded-full bg-[#1c1816] text-[#b58f5c] flex items-center justify-center font-bold border-2 border-[#b58f5c] text-3xl shadow-lg">{{ substr($cidadaoSelecionado->name, 0, 1) }}</div>
                        @endif
                        <div>
                            <h2 class="text-3xl font-serif font-bold text-white">{{ $cidadaoSelecionado->name }}</h2>
                            <p class="text-gray-400 mt-1">{{ $cidadaoSelecionado->email }}</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-[#cba77d] mb-4">Histórico de Requisições</h3>
                        <div class="bg-[#1c1816] border border-[#3e2b1e] rounded-xl overflow-hidden max-h-96 overflow-y-auto">
                            @if($cidadaoSelecionado->requisicaos->isEmpty())
                                <p class="p-6 text-gray-500 text-center">Nenhum histórico de requisições encontrado para este leitor.</p>
                            @else
                                <table class="w-full text-left">
                                    <thead class="text-xs text-gray-400 uppercase bg-[#2d2019] border-b border-[#3e2b1e]">
                                        <tr>
                                            <th class="px-6 py-4">Livro</th>
                                            <th class="px-6 py-4">Requisitado a</th>
                                            <th class="px-6 py-4">Entrega Prev.</th>
                                            <th class="px-6 py-4">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#3e2b1e]">
                                        @foreach($cidadaoSelecionado->requisicaos->sortByDesc('created_at') as $req)
                                            <tr class="hover:bg-[#2d2019]">
                                                <td class="px-6 py-4">
                                                    <div class="font-bold text-white">{{ $req->livro->nome }}</div>
                                                    <div class="text-xs text-gray-500 font-mono">{{ $req->livro->isbn }}</div>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-400">{{ \Carbon\Carbon::parse($req->data_requisicao)->format('d/m/Y') }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-400">{{ \Carbon\Carbon::parse($req->data_fim_prevista)->format('d/m/Y') }}</td>
                                                <td class="px-6 py-4">
                                                    @if($req->status === 'pendente')
                                                        <span class="text-yellow-400 text-xs px-2 py-1 bg-yellow-900/30 rounded-full border border-yellow-700/50">Ativa</span>
                                                    @elseif($req->status === 'entregue')
                                                        <span class="text-green-400 text-xs px-2 py-1 bg-green-900/30 rounded-full border border-green-700/50">Entregue ({{ $req->dias_decorrentes }} dias)</span>
                                                    @else
                                                        <span class="text-red-400 text-xs px-2 py-1 bg-red-900/30 rounded-full border border-red-700/50">Atrasado</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
