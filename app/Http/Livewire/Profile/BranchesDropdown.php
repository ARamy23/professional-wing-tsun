<?php

namespace App\Http\Livewire\Profile;

use Livewire\Component;
use \App\Models\Branch;

class BranchesDropdown extends Component
{
    public string $branch = "";

    public function render()
    {
        return view('livewire.profile.branches-dropdown', [
            'branches' => Branch::get()
        ]);
    }

    public function select($id)
    {
        $this->branch = Branch::find($id)->name;
    }
}
