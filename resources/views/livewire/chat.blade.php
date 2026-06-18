<div class="flex fixed top-[97px] bottom-0 left-0 right-0 bg-[#1c1816] text-[#e8d5c4] font-sans antialiased overflow-hidden" wire:poll.2s="loadMessages">
    <!-- Sidebar -->
    <div class="w-1/4 border-r border-[#3e2b1e] bg-[#2d2019] flex flex-col">
        <div class="p-4 border-b border-[#3e2b1e] flex justify-between items-center">
            <h2 class="text-lg font-bold text-[#e8d5c4]">Campfire</h2>
            @if(auth()->user()->isAdmin())
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="text-[#b58f5c] hover:text-[#cba77d] transition">
                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-[#1c1816] rounded-md shadow-lg border border-[#3e2b1e] z-10">
                        <button wire:click="$set('showCreateRoomModal', true)" @click="open = false" class="block w-full text-left px-4 py-2 text-sm text-[#e8d5c4] hover:bg-[#3e2b1e]">Criar Sala</button>
                    </div>
                </div>
            @endif
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-4">
            <!-- Salas -->
            <div>
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Salas</h3>
                <div class="space-y-1">
                    @foreach($conversations->where('is_direct', false) as $conv)
                        <button wire:click="selectConversation({{ $conv->id }})" class="w-full text-left px-3 py-2 rounded-md transition {{ $currentConversation && $currentConversation->id === $conv->id ? 'bg-[#3e2b1e] text-[#e8d5c4]' : 'text-gray-400 hover:bg-[#3e2b1e] hover:text-[#e8d5c4]' }}">
                            # {{ $conv->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Pessoas / Equipa -->
            <div>
                <div class="flex items-center justify-between mt-6 mb-2">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pessoas</h3>
                </div>
                <div class="mb-3">
                    <input wire:model.live="searchUser" type="text" placeholder="Pesquisar..." class="w-full bg-[#1c1816] text-[#e8d5c4] text-sm border border-[#3e2b1e] rounded px-3 py-1.5 focus:border-[#b58f5c] focus:ring focus:ring-[#b58f5c] focus:ring-opacity-50">
                </div>
                <div class="space-y-1">
                    @forelse($displayUsers as $u)
                        @php
                            // Check if there's an active DM to highlight it
                            $activeDM = $conversations->where('is_direct', true)->filter(function($c) use ($u) {
                                return $c->users->contains('id', $u->id);
                            })->first();
                            $isActive = $activeDM && $currentConversation && $currentConversation->id === $activeDM->id;
                        @endphp
                        <button wire:click="startDirectMessage({{ $u->id }})" class="w-full flex items-center px-3 py-2 rounded-md transition {{ $isActive ? 'bg-[#3e2b1e] text-[#e8d5c4]' : 'text-gray-400 hover:bg-[#3e2b1e] hover:text-[#e8d5c4]' }}">
                            <div class="relative">
                                <img src="{{ $u->profile_photo_url }}" class="size-6 rounded-full mr-2">
                                <span class="absolute bottom-0 right-1 size-2 rounded-full border border-[#2d2019] {{ $u->isOnline() ? 'bg-green-500' : 'bg-gray-500' }}"></span>
                            </div>
                            <span class="truncate">{{ $u->name }}</span>
                        </button>
                    @empty
                        <p class="text-xs text-gray-500 px-3 py-2">Nenhum utilizador encontrado.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Main Chat Area -->
    <div class="flex-1 flex flex-col bg-[#1c1816]">
        @if($currentConversation)
            <!-- Header -->
            <div class="p-4 border-b border-[#3e2b1e] flex items-center justify-between shadow-sm">
                <div class="flex items-center">
                    @if(!$currentConversation->is_direct)
                        <h3 class="text-lg font-bold text-[#e8d5c4]"># {{ $currentConversation->name }}</h3>
                        <span class="ml-4 text-xs text-gray-500">{{ $currentConversation->users->count() }} membros</span>
                    @else
                        @php $otherUser = $currentConversation->users->where('id', '!=', auth()->id())->first(); @endphp
                        @if($otherUser)
                            <img src="{{ $otherUser->profile_photo_url }}" class="size-8 rounded-full mr-3">
                            <h3 class="text-lg font-bold text-[#e8d5c4]">{{ $otherUser->name }}</h3>
                        @endif
                    @endif
                </div>

                @if(!$currentConversation->is_direct && auth()->user()->isAdmin())
                    <div class="relative" x-data="{ menuOpen: false }">
                        <button @click="menuOpen = !menuOpen" class="text-gray-400 hover:text-[#e8d5c4] transition">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                            </svg>
                        </button>
                        <div x-show="menuOpen" @click.away="menuOpen = false" class="absolute right-0 mt-2 w-48 bg-[#2d2019] rounded-md shadow-lg border border-[#3e2b1e] z-10">
                            <button wire:click="$set('showAddUserModal', true)" @click="menuOpen = false" class="block w-full text-left px-4 py-2 text-sm text-[#e8d5c4] hover:bg-[#3e2b1e]">Adicionar Membros</button>
                            <button wire:click="$set('showManageMembersModal', true)" @click="menuOpen = false" class="block w-full text-left px-4 py-2 text-sm text-[#e8d5c4] hover:bg-[#3e2b1e]">Gerir Membros</button>
                            <button wire:click="deleteRoom({{ $currentConversation->id }})" @click="menuOpen = false" wire:confirm="Tem a certeza que deseja eliminar esta sala? Todas as mensagens serão perdidas." class="block w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-[#3e2b1e]">Eliminar Sala</button>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Messages -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6" id="chat-messages" x-data x-init="$el.scrollTop = $el.scrollHeight" @refresh-messages.window="$el.scrollTop = $el.scrollHeight">
                @forelse($messages as $msg)
                    <div class="flex {{ $msg->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div class="flex max-w-2xl {{ $msg->user_id === auth()->id() ? 'flex-row-reverse' : 'flex-row' }}">
                            <img src="{{ $msg->user->profile_photo_url }}" class="size-10 rounded-full {{ $msg->user_id === auth()->id() ? 'ml-3' : 'mr-3' }}">
                            <div class="flex flex-col {{ $msg->user_id === auth()->id() ? 'items-end' : 'items-start' }}">
                                <div class="flex items-baseline mb-1">
                                    <span class="font-semibold text-sm text-[#e8d5c4] {{ $msg->user_id === auth()->id() ? 'ml-2' : 'mr-2' }}">{{ $msg->user->name }}</span>
                                    <span class="text-xs text-gray-500">{{ $msg->created_at->format('H:i') }}</span>
                                </div>
                                <div class="px-4 py-2 rounded-2xl {{ $msg->user_id === auth()->id() ? 'bg-[#b58f5c] text-[#1c1816] rounded-tr-none' : 'bg-[#2d2019] text-[#e8d5c4] rounded-tl-none border border-[#3e2b1e]' }}">
                                    {!! nl2br(e($msg->body)) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-gray-500 space-y-4">
                        <svg class="size-16 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <p>Nenhuma mensagem ainda. Inicie a conversa!</p>
                    </div>
                @endforelse
            </div>

            <!-- Input -->
            <div class="p-4 border-t border-[#3e2b1e] bg-[#1c1816]">
                <form wire:submit.prevent="sendMessage" class="flex gap-2 relative">
                    <textarea wire:model="newMessage" wire:keydown.enter.prevent="sendMessage" rows="1" placeholder="Escreva uma mensagem..." class="w-full bg-[#2d2019] text-[#e8d5c4] border border-[#3e2b1e] rounded-3xl pl-6 pr-14 py-3 shadow-inner focus:border-[#b58f5c] focus:ring focus:ring-[#b58f5c] focus:ring-opacity-50 resize-none overflow-hidden" x-data x-init="$watch('newMessage', val => { $el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px' })"></textarea>
                    <button type="submit" class="absolute right-2 bottom-2 bg-[#b58f5c] text-[#1c1816] p-2.5 rounded-full hover:bg-[#cba77d] transition">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </form>
            </div>
        @else
            <div class="flex flex-col items-center justify-center h-full text-gray-500">
                <svg class="size-20 mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                </svg>
                <h3 class="text-xl font-bold mb-2">Bem-vindo ao Campfire</h3>
                <p>Selecione uma sala ou pessoa para começar a falar.</p>
            </div>
        @endif
    </div>

    <!-- Modal Nova Sala -->
    <x-dialog-modal wire:model.live="showCreateRoomModal">
        <x-slot name="title">
            <h3 class="text-lg font-medium text-[#e8d5c4]">Criar Nova Sala</h3>
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300">Nome da Sala</label>
                    <input wire:model="roomName" type="text" class="mt-1 block w-full bg-[#2d2019] border border-[#3e2b1e] rounded-md text-[#e8d5c4]">
                    @error('roomName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Convidar Utilizadores</label>
                    <div class="max-h-48 overflow-y-auto space-y-2 border border-[#3e2b1e] rounded-md p-3 bg-[#2d2019]">
                        @foreach($allUsersForModal as $u)
                            <label class="flex items-center gap-3 text-sm text-[#e8d5c4] cursor-pointer hover:bg-[#3e2b1e] p-1.5 rounded transition">
                                <input type="checkbox" wire:model="selectedUsers" value="{{ $u->id }}" class="rounded bg-[#1c1816] border-[#3e2b1e] text-[#b58f5c] focus:ring-[#b58f5c]">
                                <span class="leading-none mt-0.5">{{ $u->name }} <span class="text-xs text-gray-500">({{ $u->email }})</span></span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <button wire:click="$set('showCreateRoomModal', false)" class="mr-3 px-4 py-2 border border-gray-600 rounded-md text-gray-300 hover:bg-gray-800">Cancelar</button>
            <button wire:click="createRoom" class="bg-[#b58f5c] text-[#1c1816] px-4 py-2 rounded-md font-bold hover:bg-[#cba77d]">Criar Sala</button>
        </x-slot>
    </x-dialog-modal>

    <!-- Modal Adicionar Utilizadores à Sala -->
    <x-dialog-modal wire:model.live="showAddUserModal">
        <x-slot name="title">
            <h3 class="text-lg font-medium text-[#e8d5c4]">Adicionar Membros a #{{ $currentConversation ? $currentConversation->name : '' }}</h3>
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Utilizadores Disponíveis</label>
                    @if(isset($usersNotInRoom) && $usersNotInRoom->count() > 0)
                        <div class="max-h-48 overflow-y-auto space-y-2 border border-[#3e2b1e] rounded-md p-3 bg-[#2d2019]">
                            @foreach($usersNotInRoom as $u)
                                <label class="flex items-center gap-3 text-sm text-[#e8d5c4] cursor-pointer hover:bg-[#3e2b1e] p-1.5 rounded transition">
                                    <input type="checkbox" wire:model="usersToAdd" value="{{ $u->id }}" class="rounded bg-[#1c1816] border-[#3e2b1e] text-[#b58f5c] focus:ring-[#b58f5c]">
                                    <span class="leading-none mt-0.5">{{ $u->name }} <span class="text-xs text-gray-500">({{ $u->email }})</span></span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">Todos os utilizadores já estão nesta sala.</p>
                    @endif
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <button wire:click="$set('showAddUserModal', false)" class="mr-3 px-4 py-2 border border-gray-600 rounded-md text-gray-300 hover:bg-gray-800">Cancelar</button>
            <button wire:click="addUsersToRoom" class="bg-[#b58f5c] text-[#1c1816] px-4 py-2 rounded-md font-bold hover:bg-[#cba77d]" {{ (isset($usersNotInRoom) && $usersNotInRoom->count() == 0) ? 'disabled' : '' }}>Adicionar</button>
        </x-slot>
    </x-dialog-modal>

    <!-- Modal Gerir Membros da Sala -->
    <x-dialog-modal wire:model.live="showManageMembersModal">
        <x-slot name="title">
            <h3 class="text-lg font-medium text-[#e8d5c4]">Membros em #{{ $currentConversation ? $currentConversation->name : '' }}</h3>
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div class="max-h-64 overflow-y-auto space-y-2">
                    @if($currentConversation)
                        @foreach($currentConversation->users as $u)
                            <div class="flex items-center justify-between p-2 border border-[#3e2b1e] rounded-md bg-[#2d2019]">
                                <div class="flex items-center space-x-3">
                                    <div class="relative">
                                        <img src="{{ $u->profile_photo_url }}" class="size-8 rounded-full">
                                        <span class="absolute bottom-0 right-0 size-2.5 rounded-full border border-[#2d2019] {{ $u->isOnline() ? 'bg-green-500' : 'bg-gray-500' }}"></span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-[#e8d5c4]">{{ $u->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $u->email }}</p>
                                    </div>
                                </div>
                                <button wire:click="removeUserFromRoom({{ $u->id }})" wire:confirm="Tem a certeza que deseja remover este utilizador da sala?" class="text-red-400 hover:text-red-300 text-sm font-medium px-2 py-1 bg-red-900/30 rounded border border-red-900/50 hover:bg-red-900/50 transition">
                                    Remover
                                </button>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <button wire:click="$set('showManageMembersModal', false)" class="bg-[#b58f5c] text-[#1c1816] px-4 py-2 rounded-md font-bold hover:bg-[#cba77d]">Fechar</button>
        </x-slot>
    </x-dialog-modal>
</div>
