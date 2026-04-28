<div>
    <div class="min-h-screen bg-[#1c1816] text-white font-sans py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-[#b58f5c] tracking-tight">Livros</h2>
                    <p class="text-gray-400 mt-1">Gerencie o acervo de livros da biblioteca.</p>
                </div>
            </div>

            <div class="bg-[#2d2019] overflow-hidden shadow-2xl rounded-3xl p-6 border border-[#3e2b1e]">
                
                <div class="flex justify-between items-center mb-6">
                    <input type="text" wire:model.live="search" placeholder="Pesquisar por ISBN, Nome ou Editora..." class="input input-bordered w-full sm:w-1/2 bg-[#1c1816] border-[#3e2b1e] text-white focus:border-[#b58f5c] focus:ring-[#b58f5c]" />
                    <div class="flex gap-4">
                        <button wire:click="exportExcel" class="btn btn-outline border-[#b58f5c] text-[#b58f5c] hover:bg-[#b58f5c] hover:text-[#1c1816] hover:border-[#b58f5c]">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Exportar Excel
                        </button>
                        <button wire:click="confirmLivroAdd" class="btn border-none font-bold shadow-lg" style="background-color: #b58f5c; color: #1c1816;">Adicionar Livro</button>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-xl">
                    <table class="table w-full text-left">
                        <thead class="text-[#b58f5c] bg-[#1c1816] border-b-2 border-[#3e2b1e]">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider cursor-pointer hover:bg-[#2d2019] transition-colors" wire:click="sortBy('isbn')">
                                    ISBN @if($sortField === 'isbn') @if($sortAsc) &uarr; @else &darr; @endif @endif
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                                    Capa
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider cursor-pointer hover:bg-[#2d2019] transition-colors" wire:click="sortBy('nome')">
                                    Livro @if($sortField === 'nome') @if($sortAsc) &uarr; @else &darr; @endif @endif
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                                    Editora & Autores
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider cursor-pointer hover:bg-[#2d2019] transition-colors" wire:click="sortBy('preco')">
                                    Preço @if($sortField === 'preco') @if($sortAsc) &uarr; @else &darr; @endif @endif
                                </th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#3e2b1e] bg-[#2d2019]">
                            @forelse($livros as $livro)
                                <tr class="hover:bg-[#3e2b1e] transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 font-mono">
                                        {{ $livro->isbn }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        @if($livro->imagem_capa)
                                            <img src="{{ Storage::url($livro->imagem_capa) }}" alt="{{ $livro->nome }}" class="w-12 h-16 object-cover rounded shadow-md border border-[#3e2b1e]">
                                        @else
                                            <div class="w-12 h-16 rounded bg-[#1c1816] flex items-center justify-center text-gray-500 border border-[#3e2b1e]">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-white">
                                        <div class="font-bold text-base mb-1">{{ $livro->nome }}</div>
                                        <div class="text-xs text-gray-400 line-clamp-2 max-w-xs" title="{{ $livro->bibliografia }}">{{ Str::limit($livro->bibliografia, 60) }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-300">
                                        <div class="mb-1 text-[#b58f5c]"><span class="font-semibold text-gray-400">Edt:</span> {{ $livro->editora->nome ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-400 max-w-xs truncate" title="{{ $livro->autores->pluck('nome')->implode(', ') }}">
                                            <span class="font-semibold text-gray-500">Autores:</span>
                                            {{ $livro->autores->pluck('nome')->implode(', ') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-[#b58f5c] font-bold">
                                        {{ $livro->preco ? number_format($livro->preco, 2, ',', ' ') . ' €' : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button wire:click="confirmLivroEdit({{ $livro->id }})" class="btn btn-sm btn-ghost text-[#b58f5c] hover:bg-[#1c1816] mr-2">Editar</button>
                                        <button wire:click="confirmLivroDeletion({{ $livro->id }})" class="btn btn-sm btn-ghost text-red-400 hover:bg-[#1c1816]">Apagar</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 whitespace-nowrap text-center text-sm text-gray-500">
                                        Nenhum livro encontrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <x-dialog-modal wire:model.live="confirmingLivroAdd">
            <x-slot name="title">
                <span class="text-[#b58f5c] font-bold text-xl">{{ isset($this->livroId) ? 'Editar Livro' : 'Adicionar Livro' }}</span>
            </x-slot>

            <x-slot name="content">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-1 border border-[#3e2b1e] p-5 rounded-2xl bg-[#1c1816]">
                        <x-label for="isbn" value="ISBN" class="text-gray-300 font-semibold" />
                        <x-input id="isbn" type="text" class="mt-2 block w-full bg-[#2d2019] border-[#3e2b1e] text-white focus:border-[#b58f5c] focus:ring-[#b58f5c] rounded-lg" wire:model="isbn" />
                        <x-input-error for="isbn" class="mt-2 text-red-400" />

                        <x-label for="nome" value="Nome do Livro" class="mt-4 text-gray-300 font-semibold" />
                        <x-input id="nome" type="text" class="mt-2 block w-full bg-[#2d2019] border-[#3e2b1e] text-white focus:border-[#b58f5c] focus:ring-[#b58f5c] rounded-lg" wire:model="nome" />
                        <x-input-error for="nome" class="mt-2 text-red-400" />

                        <x-label for="preco" value="Preço (€)" class="mt-4 text-gray-300 font-semibold" />
                        <x-input id="preco" type="number" step="0.01" class="mt-2 block w-full bg-[#2d2019] border-[#3e2b1e] text-white focus:border-[#b58f5c] focus:ring-[#b58f5c] rounded-lg" wire:model="preco" />
                        <x-input-error for="preco" class="mt-2 text-red-400" />

                        <x-label for="newImagemCapa" value="Capa do Livro" class="mt-4 text-gray-300 font-semibold" />
                        <input type="file" id="newImagemCapa" class="mt-2 block w-full text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#3e2b1e] file:text-[#b58f5c] hover:file:bg-[#2d2019] transition-colors" wire:model="newImagemCapa" />
                        <x-input-error for="newImagemCapa" class="mt-2 text-red-400" />
                        
                        @if ($newImagemCapa)
                            <div class="mt-4 text-center">
                                <img src="{{ $newImagemCapa->temporaryUrl() }}" class="h-32 object-cover inline-block rounded-xl shadow-lg border border-[#3e2b1e]">
                            </div>
                        @elseif ($imagem_capa)
                            <div class="mt-4 text-center">
                                <img src="{{ Storage::url($imagem_capa) }}" class="h-32 object-cover inline-block rounded-xl shadow-lg border border-[#3e2b1e]">
                            </div>
                        @endif
                    </div>

                    <div class="col-span-1 border border-[#3e2b1e] p-5 rounded-2xl bg-[#1c1816]">
                        <x-label for="editora_id" value="Editora" class="text-gray-300 font-semibold" />
                        <select id="editora_id" wire:model="editora_id" class="mt-2 block w-full bg-[#2d2019] border-[#3e2b1e] text-white focus:border-[#b58f5c] focus:ring-[#b58f5c] rounded-lg shadow-sm">
                            <option value="">Selecione uma editora...</option>
                            @foreach($editoras_options as $ed)
                                <option value="{{ $ed->id }}">{{ $ed->nome }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="editora_id" class="mt-2 text-red-400" />

                        <x-label for="autor_ids" value="Autores" class="mt-4 text-gray-300 font-semibold" />
                        <select id="autor_ids" wire:model="autor_ids" multiple class="mt-2 block w-full bg-[#2d2019] border-[#3e2b1e] text-white focus:border-[#b58f5c] focus:ring-[#b58f5c] rounded-lg shadow-sm min-h-[120px]">
                            @foreach($autores_options as $aut)
                                <option value="{{ $aut->id }}">{{ $aut->nome }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-2">Mantenha Ctrl/Cmd pressionado para selecionar vários.</p>
                        <x-input-error for="autor_ids" class="mt-2 text-red-400" />

                        <x-label for="bibliografia" value="Bibliografia / Sinopse" class="mt-4 text-gray-300 font-semibold" />
                        <textarea id="bibliografia" wire:model="bibliografia" rows="4" class="mt-2 block w-full bg-[#2d2019] border-[#3e2b1e] text-white focus:border-[#b58f5c] focus:ring-[#b58f5c] rounded-lg shadow-sm"></textarea>
                        <x-input-error for="bibliografia" class="mt-2 text-red-400" />
                    </div>
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('confirmingLivroAdd')" wire:loading.attr="disabled" class="bg-[#3e2b1e] text-white border-none hover:bg-[#2d2019]">
                    Cancelar
                </x-secondary-button>

                <x-button class="ms-3 bg-[#b58f5c] text-[#1c1816] hover:bg-[#d4a86f] border-none font-bold" wire:click="saveLivro" wire:loading.attr="disabled">
                    Guardar
                </x-button>
            </x-slot>
        </x-dialog-modal>
        <x-confirmation-modal wire:model.live="confirmingLivroDeletion">
            <x-slot name="title">
                <span class="text-red-400 font-bold text-xl">Apagar Livro</span>
            </x-slot>

            <x-slot name="content">
                <span class="text-gray-300">Tem a certeza que deseja apagar este livro? Esta ação não pode ser desfeita.</span>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('confirmingLivroDeletion')" wire:loading.attr="disabled" class="bg-[#3e2b1e] text-white border-none hover:bg-[#2d2019]">
                    Cancelar
                </x-secondary-button>

                <x-danger-button class="ms-3 bg-red-600 hover:bg-red-700 border-none font-bold" wire:click="deleteLivro" wire:loading.attr="disabled">
                    Apagar
                </x-danger-button>
            </x-slot>
        </x-confirmation-modal>
    </div>
</div>
