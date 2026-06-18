<?php

namespace App\Livewire;

use Livewire\Component;

class Chat extends Component
{
    public $conversations = [];
    public $currentConversation = null;
    public $messages = [];
    public $newMessage = '';
    public $showCreateRoomModal = false;
    public $roomName = '';
    public $selectedUsers = [];

    public $showAddUserModal = false;
    public $showManageMembersModal = false;
    public $usersToAdd = [];

    public $searchUser = '';

    protected $listeners = ['refreshMessages' => '$refresh'];

    public function mount()
    {
        $this->loadConversations();
    }

    public function loadConversations()
    {
        $this->conversations = auth()->user()->conversations()->with('users')->get();
    }

    public function selectConversation($id)
    {
        $this->currentConversation = \App\Models\Conversation::with('users')->findOrFail($id);
        $this->loadMessages();
    }

    public function loadMessages()
    {
        if ($this->currentConversation) {
            $this->messages = $this->currentConversation->messages()->with('user')->orderBy('created_at', 'asc')->get();
        }
    }

    public function sendMessage()
    {
        if (!$this->currentConversation || empty(trim($this->newMessage))) return;

        $this->currentConversation->messages()->create([
            'user_id' => auth()->id(),
            'body' => $this->newMessage,
        ]);

        $this->newMessage = '';
        $this->loadMessages();
    }

    public function createRoom()
    {
        if (!auth()->user()->isAdmin()) return;
        
        $this->validate([
            'roomName' => 'required|string|max:255',
        ]);

        $conversation = \App\Models\Conversation::create([
            'name' => $this->roomName,
            'is_direct' => false,
            'admin_id' => auth()->id(),
        ]);

        // Adiciona o admin à sala
        $conversation->users()->attach(auth()->id());
        
        // Adiciona os users selecionados
        if (!empty($this->selectedUsers)) {
            $conversation->users()->attach($this->selectedUsers);
        }

        $this->showCreateRoomModal = false;
        $this->roomName = '';
        $this->selectedUsers = [];
        $this->loadConversations();
        $this->selectConversation($conversation->id);
    }

    public function deleteRoom($id)
    {
        if (!auth()->user()->isAdmin()) return;

        $conversation = \App\Models\Conversation::findOrFail($id);
        
        if ($conversation->is_direct) return; // Nao apagar DMs por agora

        $conversation->delete();
        
        if ($this->currentConversation && $this->currentConversation->id === $id) {
            $this->currentConversation = null;
            $this->messages = [];
        }
        
        $this->loadConversations();
    }

    public function addUsersToRoom()
    {
        if (!auth()->user()->isAdmin() || !$this->currentConversation || $this->currentConversation->is_direct) return;

        if (!empty($this->usersToAdd)) {
            $this->currentConversation->users()->syncWithoutDetaching($this->usersToAdd);
        }

        $this->showAddUserModal = false;
        $this->usersToAdd = [];
        $this->loadConversations();
        $this->selectConversation($this->currentConversation->id); // reload users
    }

    public function removeUserFromRoom($userId)
    {
        if (!auth()->user()->isAdmin() || !$this->currentConversation || $this->currentConversation->is_direct) return;

        $this->currentConversation->users()->detach($userId);

        // Se o admin se remover a si mesmo e a sala ficar vazia ou se quisermos recarregar
        if ($userId == auth()->id()) {
            $this->showManageMembersModal = false;
            $this->currentConversation = null;
            $this->messages = [];
        } else {
            $this->selectConversation($this->currentConversation->id); // reload
        }

        $this->loadConversations();
    }

    public function startDirectMessage($userId)
    {
        // Check if DM already exists
        $existing = auth()->user()->conversations()->where('is_direct', true)
            ->whereHas('users', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->first();

        if ($existing) {
            $this->selectConversation($existing->id);
            return;
        }

        if (!auth()->user()->isAdmin()) return; // Non-admins can't start NEW DMs, only open existing ones.

        $conversation = \App\Models\Conversation::create([
            'is_direct' => true,
            'admin_id' => auth()->id(),
        ]);

        $conversation->users()->attach([auth()->id(), $userId]);
        $this->loadConversations();
        $this->selectConversation($conversation->id);
    }

    public function render()
    {
        if (auth()->user()->isAdmin()) {
            // Admins can see and search all users
            $query = \App\Models\User::where('id', '!=', auth()->id());
            if (!empty($this->searchUser)) {
                $query->where('name', 'like', '%' . $this->searchUser . '%');
            }
            $displayUsers = $query->get();
        } else {
            // Non-admins only see users they already have a DM with
            $dmUserIds = auth()->user()->conversations()
                ->where('is_direct', true)
                ->with('users')
                ->get()
                ->flatMap->users
                ->pluck('id')
                ->reject(fn($id) => $id == auth()->id())
                ->unique();
                
            $query = \App\Models\User::whereIn('id', $dmUserIds);
            if (!empty($this->searchUser)) {
                $query->where('name', 'like', '%' . $this->searchUser . '%');
            }
            $displayUsers = $query->get();
        }
        
        $allUsersForModal = auth()->user()->isAdmin() ? \App\Models\User::where('id', '!=', auth()->id())->get() : collect();
        
        // Users not in the current room
        $usersNotInRoom = collect();
        if ($this->currentConversation && !$this->currentConversation->is_direct && auth()->user()->isAdmin()) {
            $roomUserIds = $this->currentConversation->users->pluck('id')->toArray();
            $usersNotInRoom = \App\Models\User::whereNotIn('id', $roomUserIds)->get();
        }
        
        // Auto refresh
        $this->loadMessages();

        return view('livewire.chat', [
            'displayUsers' => $displayUsers,
            'allUsersForModal' => $allUsersForModal,
            'usersNotInRoom' => $usersNotInRoom
        ])->layout('layouts.app');
    }
}
