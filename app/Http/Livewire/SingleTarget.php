<?php

namespace App\Http\Livewire;

use App\Models\Target;
use Livewire\Component;

class SingleTarget extends Component
{

    public Target $itmm;
    public $loading;

    protected $rules = [

        "itmm.url" => "required|string|min:1",
        "itmm.title" => "required|string|min:1",

    ];

    public function mount(Target $itm)
    {
        $this->itmm = $itm;
    }

    public function updated()
    {


        sleep(2);

        $this->validate();


        $this->itmm->save();

        session()->flash('message', 'Post successfully updated.');



    }


    public function render()
    {
        return view('livewire.single-target');
    }
}
