<div>
    <div class="min-h-screen bg-[#1c1816] text-white font-sans py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-[#b58f5c] tracking-tight">Autores</h2>
                    <p class="text-gray-400 mt-1">Gerencie os autores do seu acervo.</p>
                </div>
            </div>

            <div class="bg-[#2d2019] overflow-hidden shadow-2xl rounded-3xl p-6 border border-[#3e2b1e]">
                
                <div class="flex justify-between items-center mb-6">
                    <input type="text" wire:model.live="search" placeholder="Pesquisar por nome..." class="input input-bordered w-full sm:w-1/3 bg-[#1c1816] border-[#3e2b1e] text-white focus:border-[#b58f5c] focus:ring-[#b58f5c]" />
                    <button wire:click="confirmAutorAdd" class="btn border-none font-bold shadow-lg" style="background-color: #b58f5c; color: #1c1816;">Adicionar Autor</button>
                </div>

                <div class="overflow-x-auto rounded-xl">
                    <table class="table w-full text-left">
                        <thead class="text-[#b58f5c] bg-[#1c1816] border-b-2 border-[#3e2b1e]">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider cursor-pointer hover:bg-[#2d2019] transition-colors" wire:click="sortBy('id')">
                                    ID @if($sortField === 'id') @if($sortAsc) &uarr; @else &darr; @endif @endif
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                                    Foto
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
                            @forelse($autores as $autor)
                                <tr class="hover:bg-[#3e2b1e] transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        {{ $autor->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        @if($autor->foto)
                                            <img src="{{ Storage::url($autor->foto) }}" alt="{{ $autor->nome }}" class="w-12 h-12 rounded-full object-cover shadow-md border border-[#3e2b1e]">
                                        @else
                                            <div class="w-12 h-12 rounded-full bg-[#1c1816] flex items-center justify-center text-gray-500 border border-[#3e2b1e]">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-semibold">
                                        {{ $autor->nome }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button wire:click="confirmAutorEdit({{ $autor->id }})" class="btn btn-sm btn-ghost text-[#b58f5c] hover:bg-[#1c1816] mr-2">Editar</button>
                                        <button wire:click="confirmAutorDeletion({{ $autor->id }})" class="btn btn-sm btn-ghost text-red-400 hover:bg-[#1c1816]">Apagar</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 whitespace-nowrap text-center text-sm text-gray-500">
                                        Nenhum autor encontrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <x-dialog-modal wire:model.live="confirmingAutorAdd">
            <x-slot name="title">
                <span class="text-[#b58f5c] font-bold text-xl">{{ isset($this->autorId) ? 'Editar Autor' : 'Adicionar Autor' }}</span>
            </x-slot>

            <x-slot name="content">
                <div class="col-span-6 sm:col-span-4 mb-4">
                    <x-label for="nome" value="Nome do Autor" class="text-gray-300 font-semibold" />
                    <x-input id="nome" type="text" class="mt-2 block w-full bg-[#1c1816] border-[#3e2b1e] text-white focus:border-[#b58f5c] focus:ring-[#b58f5c] rounded-lg" wire:model="nome" />
                    <x-input-error for="nome" class="mt-2 text-red-400" />
                </div>

                <div class="col-span-6 sm:col-span-4">
                    <x-label for="newFoto" value="Foto (Opcional)" class="text-gray-300 font-semibold" />
                    <input type="file" id="newFoto" class="mt-2 block w-full text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#3e2b1e] file:text-[#b58f5c] hover:file:bg-[#2d2019] transition-colors" wire:model="newFoto" />
                    <x-input-error for="newFoto" class="mt-2 text-red-400" />
                    
                    @if ($newFoto)
                        <div class="mt-4">
                            <img src="{{ $newFoto->temporaryUrl() }}" class="w-24 h-24 object-cover rounded-xl shadow-lg border border-[#3e2b1e]">
                        </div>
                    @elseif ($foto)
                        <div class="mt-4">
                            <img src="{{ Storage::url($foto) }}" class="w-24 h-24 object-cover rounded-xl shadow-lg border border-[#3e2b1e]">
                        </div>
                    @endif
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('confirmingAutorAdd')" wire:loading.attr="disabled" class="bg-[#3e2b1e] text-white border-none hover:bg-[#2d2019]">
                    Cancelar
                </x-secondary-button>

                <x-button class="ms-3 bg-[#b58f5c] text-[#1c1816] hover:bg-[#d4a86f] border-none font-bold" wire:click="saveAutor" wire:loading.attr="disabled">
                    Guardar
                </x-button>
            </x-slot>
        </x-dialog-modal>
        <x-confirmation-modal wire:model.live="confirmingAutorDeletion">
            <x-slot name="title">
                <span class="text-red-400 font-bold text-xl">Apagar Autor</span>
            </x-slot>

            <x-slot name="content">
                <span class="text-gray-300">Tem a certeza que deseja apagar este autor? Esta ação não pode ser desfeita.</span>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('confirmingAutorDeletion')" wire:loading.attr="disabled" class="bg-[#3e2b1e] text-white border-none hover:bg-[#2d2019]">
                    Cancelar
                </x-secondary-button>

                <x-danger-button class="ms-3 bg-red-600 hover:bg-red-700 border-none font-bold" wire:click="deleteAutor" wire:loading.attr="disabled">
                    Apagar
                </x-danger-button>
            </x-slot>
        </x-confirmation-modal>
    </div>
</div>
