<?php

namespace App\Http\Livewire\Profile;

use Livewire\Component;

class OrderTshirt extends Component
{
    public $tshirt_size = "T-Shirt Size";

    public function render()
    {
        return view('livewire.profile.order-tshirt');
    }

    public function didSelectTShirtSize($size)
    {
        $this->tshirt_size = $size;
    }

    public function submit()
    {
        $this->emit('saved');
    }
}
