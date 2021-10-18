<div>
    @foreach ($targets as $targ)
        @livewire('single-target',['itm' => $targ],key($targ->id))
    @endforeach
 
    {{ $targets->links() }}






</div>
