<?php

namespace App\Http\Livewire\Profile;

use Livewire\Component;

class Grade extends Component
{
    public function render()
    {
        return view('livewire.profile.grade', [
            'grade' => auth()->user()->grade
        ]);
    }

    public function submit()
    {

    }
}
