<div>
    <div class="min-h-screen bg-[#1c1816] text-white font-sans py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-[#b58f5c] tracking-tight">Avaliações</h2>
                    <p class="text-gray-400 mt-1">Gerencie as avaliações deixadas pelos leitores.</p>
                </div>
            </div>

            @if (session()->has('success'))
                <div class="bg-green-900/50 border border-green-500 text-green-300 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-[#2d2019] overflow-hidden shadow-2xl rounded-3xl p-6 border border-[#3e2b1e]">
                
                <div class="flex justify-between items-center mb-6">
                    <select wire:model.live="statusFilter" class="input input-bordered w-full sm:w-1/4 bg-[#1c1816] border-[#3e2b1e] text-white focus:border-[#b58f5c] focus:ring-[#b58f5c]">
                        <option value="">Todos os Estados</option>
                        <option value="suspenso">Suspensos</option>
                        <option value="ativo">Ativos</option>
                        <option value="recusado">Recusados</option>
                    </select>
                </div>

                <div class="overflow-x-auto rounded-xl">
                    <table class="table w-full text-left">
                        <thead class="text-[#b58f5c] bg-[#1c1816] border-b-2 border-[#3e2b1e]">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Leitor</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Livro</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Classificação</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#3e2b1e] bg-[#2d2019]">
                            @forelse($reviews as $review)
                                <tr class="hover:bg-[#3e2b1e] transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white flex items-center gap-3">
                                        <div class="font-semibold">{{ $review->requisicao->user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-300">
                                        <div class="font-bold text-white">{{ $review->requisicao->livro->nome }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-yellow-400 font-bold">
                                        {{ $review->rating }} / 5
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($review->status === 'suspenso')
                                            <span class="text-yellow-400 text-xs px-3 py-1 bg-yellow-900/30 rounded-full border border-yellow-700/50 font-bold uppercase tracking-wider">Suspenso</span>
                                        @elseif($review->status === 'ativo')
                                            <span class="text-green-400 text-xs px-3 py-1 bg-green-900/30 rounded-full border border-green-700/50 font-bold uppercase tracking-wider">Ativo</span>
                                        @else
                                            <span class="text-red-400 text-xs px-3 py-1 bg-red-900/30 rounded-full border border-red-700/50 font-bold uppercase tracking-wider">Recusado</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button wire:click="manage({{ $review->id }})" class="btn btn-sm btn-ghost text-blue-400 hover:bg-[#1c1816]">Gerir</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 whitespace-nowrap text-center text-sm text-gray-500">
                                        Nenhuma avaliação encontrada.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $reviews->links() }}
                </div>
            </div>
        </div>

        <x-dialog-modal wire:model.live="managingReview">
            <x-slot name="title">
                <span class="text-blue-400 font-bold text-xl">Gerir Avaliação</span>
            </x-slot>

            <x-slot name="content">
                <div class="mt-4">
                    @php
                        $activeReview = $reviews->firstWhere('id', $reviewIdToManage);
                    @endphp
                    @if($activeReview)
                        <div class="bg-[#1c1816] p-4 rounded-lg mb-4 border border-[#3e2b1e]">
                            <p class="text-gray-300"><strong>Comentário:</strong></p>
                            <p class="text-white italic mt-1">"{{ $activeReview->comment }}"</p>
                        </div>
                    @endif

                    <div class="mb-4">
                        <x-label for="newStatus" value="Estado" class="text-gray-300 font-semibold" />
                        <select id="newStatus" wire:model.live="newStatus" class="mt-2 block w-full bg-[#2d2019] border-[#3e2b1e] text-white focus:border-[#b58f5c] focus:ring-[#b58f5c] rounded-lg">
                            <option value="ativo">Ativo</option>
                            <option value="recusado">Recusado</option>
                        </select>
                        <x-input-error for="newStatus" class="mt-2 text-red-400" />
                    </div>

                    @if($newStatus === 'recusado')
                        <div>
                            <x-label for="justification" value="Justificação da Recusa (obrigatório)" class="text-gray-300 font-semibold" />
                            <textarea id="justification" wire:model="justification" rows="3" class="mt-2 block w-full bg-[#2d2019] border-[#3e2b1e] text-white focus:border-[#b58f5c] focus:ring-[#b58f5c] rounded-lg"></textarea>
                            <x-input-error for="justification" class="mt-2 text-red-400" />
                        </div>
                    @endif
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('managingReview')" wire:loading.attr="disabled" class="bg-[#3e2b1e] text-white border-none hover:bg-[#2d2019]">
                    Cancelar
                </x-secondary-button>

                <x-button class="ms-3 bg-blue-600 hover:bg-blue-700 border-none font-bold text-white" wire:click="saveStatus" wire:loading.attr="disabled">
                    Guardar
                </x-button>
            </x-slot>
        </x-dialog-modal>
    </div>
</div>
