<?php

namespace App\Http\Livewire;

use App\Models\Target;
use Livewire\Component;
use Livewire\WithPagination;


class PaginatedTarget extends Component
{

    use WithPagination;


    public function render()
    {
        return view('livewire.paginated-target',[


            'targets' => Target::paginate(10),

        ]);
    }
}
