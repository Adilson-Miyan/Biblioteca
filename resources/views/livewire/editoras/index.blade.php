<div>
    <div class="min-h-screen bg-[#1c1816] text-white font-sans py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-[#b58f5c] tracking-tight">Editoras</h2>
                    <p class="text-gray-400 mt-1">Gerencie as parcerias editoriais do seu acervo.</p>
                </div>
            </div>

            <div class="bg-[#2d2019] overflow-hidden shadow-2xl rounded-3xl p-6 border border-[#3e2b1e]">
                
                <div class="flex justify-between items-center mb-6">
                    <input type="text" wire:model.live="search" placeholder="Pesquisar por nome..." class="input input-bordered w-full sm:w-1/3 bg-[#1c1816] border-[#3e2b1e] text-white focus:border-[#b58f5c] focus:ring-[#b58f5c]" />
                    <button wire:click="confirmEditoraAdd" class="btn border-none font-bold shadow-lg" style="background-color: #b58f5c; color: #1c1816;">Adicionar Editora</button>
                </div>

                <div class="overflow-x-auto rounded-xl">
                    <table class="table w-full text-left">
                        <thead class="text-[#b58f5c] bg-[#1c1816] border-b-2 border-[#3e2b1e]">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider cursor-pointer hover:bg-[#2d2019] transition-colors" wire:click="sortBy('id')">
                                    ID @if($sortField === 'id') @if($sortAsc) &uarr; @else &darr; @endif @endif
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                                    Logótipo
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider cursor-pointer hover:bg-[#2d2019] transition-colors" wire:click="sortBy('nome')">
                                    Nome @if($sortField === 'nome') @if($sortAsc) &uarr; @else &darr; @endif @endif
                                </th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#3e2b1e] bg-[#2d2019]">
                            @forelse($editoras as $editora)
                                <tr class="hover:bg-[#3e2b1e] transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        {{ $editora->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        @if($editora->logotipo)
                                            <img src="{{ Storage::url($editora->logotipo) }}" alt="{{ $editora->nome }}" class="w-12 h-12 rounded-full object-cover shadow-md border border-[#3e2b1e]">
                                        @else
                                            <div class="w-12 h-12 rounded-full bg-[#1c1816] flex items-center justify-center text-gray-500 border border-[#3e2b1e]">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-semibold">
                                        {{ $editora->nome }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button wire:click="confirmEditoraEdit({{ $editora->id }})" class="btn btn-sm btn-ghost text-[#b58f5c] hover:bg-[#1c1816] mr-2">Editar</button>
                                        <button wire:click="confirmEditoraDeletion({{ $editora->id }})" class="btn btn-sm btn-ghost text-red-400 hover:bg-[#1c1816]">Apagar</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 whitespace-nowrap text-center text-sm text-gray-500">
                                        Nenhuma editora encontrada.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <x-dialog-modal wire:model.live="confirmingEditoraAdd">
            <x-slot name="title">
                <span class="text-[#b58f5c] font-bold text-xl">{{ isset($this->editoraId) ? 'Editar Editora' : 'Adicionar Editora' }}</span>
            </x-slot>

            <x-slot name="content">
                <div class="col-span-6 sm:col-span-4 mb-4">
                    <x-label for="nome" value="Nome da Editora" class="text-gray-300 font-semibold" />
                    <x-input id="nome" type="text" class="mt-2 block w-full bg-[#1c1816] border-[#3e2b1e] text-white focus:border-[#b58f5c] focus:ring-[#b58f5c] rounded-lg" wire:model="nome" />
                    <x-input-error for="nome" class="mt-2 text-red-400" />
                </div>

                <div class="col-span-6 sm:col-span-4">
                    <x-label for="newLogotipo" value="Logótipo (Opcional)" class="text-gray-300 font-semibold" />
                    <input type="file" id="newLogotipo" class="mt-2 block w-full text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#3e2b1e] file:text-[#b58f5c] hover:file:bg-[#2d2019] transition-colors" wire:model="newLogotipo" />
                    <x-input-error for="newLogotipo" class="mt-2 text-red-400" />
                    
                    @if ($newLogotipo)
                        <div class="mt-4">
                            <img src="{{ $newLogotipo->temporaryUrl() }}" class="w-24 h-24 object-cover rounded-xl shadow-lg border border-[#3e2b1e]">
                        </div>
                    @elseif ($logotipo)
                        <div class="mt-4">
                            <img src="{{ Storage::url($logotipo) }}" class="w-24 h-24 object-cover rounded-xl shadow-lg border border-[#3e2b1e]">
                        </div>
                    @endif
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('confirmingEditoraAdd')" wire:loading.attr="disabled" class="bg-[#3e2b1e] text-white border-none hover:bg-[#2d2019]">
                    Cancelar
                </x-secondary-button>

                <x-button class="ms-3 bg-[#b58f5c] text-[#1c1816] hover:bg-[#d4a86f] border-none font-bold" wire:click="saveEditora" wire:loading.attr="disabled">
                    Guardar
                </x-button>
            </x-slot>
        </x-dialog-modal>
        <x-confirmation-modal wire:model.live="confirmingEditoraDeletion">
            <x-slot name="title">
                <span class="text-red-400 font-bold text-xl">Apagar Editora</span>
            </x-slot>

            <x-slot name="content">
                <span class="text-gray-300">Tem a certeza que deseja apagar esta editora? Esta ação não pode ser desfeita.</span>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('confirmingEditoraDeletion')" wire:loading.attr="disabled" class="bg-[#3e2b1e] text-white border-none hover:bg-[#2d2019]">
                    Cancelar
                </x-secondary-button>

                <x-danger-button class="ms-3 bg-red-600 hover:bg-red-700 border-none font-bold" wire:click="deleteEditora" wire:loading.attr="disabled">
                    Apagar
                </x-danger-button>
            </x-slot>
        </x-confirmation-modal>
    </div>
</div>
