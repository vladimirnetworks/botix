<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\Target;


class TargetComp extends Component
{




    public $stylex = 'idle';


    public $targets;
    public $xer = "ddd";

    protected $rules = [
        "targets.*.url" => "required|string|min:1",
        "targets.*.title" => "required|string|min:1",
    ];


    public function mount()
    {



        $this->targets = Target::all();
    }


 


    public function savex()
    {

       
        $this->validate();

        foreach ($this->targets as $ta) {
            $ta->save();
        }
    }


    public function updated() {
        $this->savex();
        $this->stylex="idlez";
    }

    public function updating() {
        $this->stylex="updating";
    }


    public function render()
    {



        return view('livewire.target-comp');
    }
}
