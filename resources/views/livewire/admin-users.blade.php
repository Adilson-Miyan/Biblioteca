<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#1c1816] overflow-hidden shadow-xl sm:rounded-lg border border-[#3e2b1e]">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-[#e8d5c4]">Equipa & Utilizadores</h2>
                        <button wire:click="$set('showCreateModal', true)" class="bg-[#b58f5c] text-[#1c1816] px-4 py-2 rounded-md font-bold hover:bg-[#cba77d] transition">
                            + Novo Utilizador
                        </button>
                    </div>
    
                    @if(session('success'))
                        <div class="bg-green-900 border border-green-700 text-green-100 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
    
                    <div class="mb-4">
                        <input wire:model.live="search" type="text" placeholder="Pesquisar por nome ou email..." class="w-full bg-[#2d2019] text-[#e8d5c4] border border-[#3e2b1e] rounded-md shadow-sm focus:border-[#b58f5c] focus:ring focus:ring-[#b58f5c] focus:ring-opacity-50">
                    </div>
    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-[#3e2b1e]">
                            <thead class="bg-[#2d2019]">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Avatar</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Nome / Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Permissão</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#3e2b1e] bg-[#1c1816]">
                                @foreach($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <img class="size-10 rounded-full object-cover" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-[#e8d5c4]">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-400">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-red-900 text-red-200' : 'bg-gray-800 text-gray-300' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($user->isOnline())
                                                <span class="inline-flex items-center text-sm text-green-400">
                                                    <span class="w-2 h-2 mr-2 bg-green-500 rounded-full"></span> Online
                                                </span>
                                            @else
                                                <span class="inline-flex items-center text-sm text-gray-500">
                                                    <span class="w-2 h-2 mr-2 bg-gray-600 rounded-full"></span> Offline
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Modal Novo Utilizador -->
        <x-dialog-modal wire:model.live="showCreateModal">
            <x-slot name="title">
                <h3 class="text-lg font-medium text-[#e8d5c4]">Novo Utilizador</h3>
            </x-slot>
    
            <x-slot name="content">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300">Nome</label>
                        <input wire:model="name" type="text" class="mt-1 block w-full bg-[#2d2019] border border-[#3e2b1e] rounded-md text-[#e8d5c4]">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300">Email</label>
                        <input wire:model="email" type="email" class="mt-1 block w-full bg-[#2d2019] border border-[#3e2b1e] rounded-md text-[#e8d5c4]">
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300">Password</label>
                        <input wire:model="password" type="password" class="mt-1 block w-full bg-[#2d2019] border border-[#3e2b1e] rounded-md text-[#e8d5c4]">
                        @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300">Permissão</label>
                        <select wire:model="role" class="mt-1 block w-full bg-[#2d2019] border border-[#3e2b1e] rounded-md text-[#e8d5c4]">
                            <option value="cidadao">Cidadão</option>
                            <option value="admin">Administrador</option>
                        </select>
                        @error('role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </x-slot>
    
            <x-slot name="footer">
                <button wire:click="$set('showCreateModal', false)" class="mr-3 px-4 py-2 border border-gray-600 rounded-md text-gray-300 hover:bg-gray-800">
                    Cancelar
                </button>
                <button wire:click="createUser" class="bg-[#b58f5c] text-[#1c1816] px-4 py-2 rounded-md font-bold hover:bg-[#cba77d]">
                    Guardar
                </button>
            </x-slot>
        </x-dialog-modal>
    </div>
</div>
