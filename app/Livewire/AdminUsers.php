<?php

namespace App\Livewire;

use Livewire\Component;

class AdminUsers extends Component
{
    use \Livewire\WithPagination;

    public $name, $email, $password, $role = 'cidadao';
    public $showCreateModal = false;
    public $search = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8',
        'role' => 'required|in:admin,cidadao',
    ];

    public function createUser()
    {
        $this->validate();

        \App\Models\User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => \Illuminate\Support\Facades\Hash::make($this->password),
            'role' => $this->role,
        ]);

        $this->reset(['name', 'email', 'password', 'role', 'showCreateModal']);
        session()->flash('success', 'Utilizador criado com sucesso.');
    }

    public function render()
    {
        if (!auth()->user()->isAdmin()) abort(403);

        $users = \App\Models\User::where('name', 'like', '%'.$this->search.'%')
            ->orWhere('email', 'like', '%'.$this->search.'%')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('livewire.admin-users', ['users' => $users])->layout('layouts.app');
    }
}
