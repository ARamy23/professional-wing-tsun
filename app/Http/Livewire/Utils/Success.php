<?php

namespace App\Http\Livewire\Utils;

use Livewire\Component;

class Success extends Component
{
    public string $message = "";
    public function render()
    {
        return view('livewire.utils.success');
    }
}
