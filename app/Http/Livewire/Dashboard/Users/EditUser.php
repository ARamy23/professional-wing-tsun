<?php

namespace App\Http\Livewire\Dashboard\Users;

use App\Models\Branch;
use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class EditUser extends Component
{
    public User $user;
    public string $grade;
    public string $certifiedGrade;
    public string $excusesAllowance;
    public string $role;
    public string $branch;
    public string $sessionsCredit;
    public $selectedRoleId;
    public $selectedBranchId;

    public $rules = [
        'grade' => 'required|integer|min:1|max:12',
        'certifiedGrade' => 'required|integer|min:1|max:12',
        'excusesAllowance' => 'required|integer',
        'sessionsCredit' => 'required|integer',
        'role' => 'required|string',
        'branch' => 'required|string',
    ];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->grade = $user->grade->english_number;
        $this->certifiedGrade = $user->certifiedGrade->english_number;
        $this->excusesAllowance = $user->allowed_excuses;
        $role = $user->roles->first();
        $this->role = $role->title;
        $this->selectedRoleId = $role->id;
        $branch = $user->branch;
        $this->branch = $branch->name;
        $this->selectedBranchId = $branch->id;
        $this->sessionsCredit = $user->sessions_credit;
    }

    public function render()
    {
        return view('livewire.dashboard.users.edit-user', [
            'branches' => Branch::all(),
            'roles' => Role::all()
        ]);
    }

    public function editProfile()
    {
        $this->validate();

        $this->user->update([
            'grade_id' => $this->grade,
            'certified_grade_id' => $this->certifiedGrade,
            'branch_id' => $this->selectedBranchId,
            'allowed_excuses' => $this->excusesAllowance,
            'sessions_credit' => $this->sessionsCredit
        ]);

        $newRole = Role::findById($this->selectedRoleId);

        $this->user->roles()->first()->update([
            'name' => $newRole->name,
            'title' => $newRole->title,
        ]);

        session()->flash('message', 'User was updated successfully!');
    }

    public function select($id)
    {
        $this->selectedBranchId = $id;
        $this->branch = Branch::find($id)->name;
    }

    public function selectRole($id)
    {
        $this->selectedRoleId = $id;
        $this->role = Role::find($id)->title;
    }
}
