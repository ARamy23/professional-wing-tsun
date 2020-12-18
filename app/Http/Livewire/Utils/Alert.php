<?php

namespace App\Http\Livewire\Utils;

use Livewire\Component;

class Alert extends Component
{
    public string $message = "";
    public function render()
    {
        return view('livewire.utils.alert');
    }
}
