<?php

namespace App\Http\Livewire\Dashboard\Users;

use App\Models\User;
use Livewire\Component;

class UsersSection extends Component
{
    public string $search = "";


    public function render()
    {
        if (empty($this->search))
            $users = User::where('id', '!=', auth()->id())->get();
        else
            $users = User::whereLike(['name', 'email', 'grade.english_number', 'roles.title', 'branch.name', 'excuses_count', 'allowed_excuses'], $this->search)->get();

        if (!empty($users) && empty($this->search))
            $users->prepend(auth()->user());

        return view('livewire.dashboard.users.users-section', [
            'users' => $users
        ]);
    }
}
