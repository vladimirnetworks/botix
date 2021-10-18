 targets : 

<div>


  @foreach ($targets as $k => $tar)


<div wire:key="post-field-{{ $tar->id }}">




     <input type="text" wire:model="targets.{{ $k }}.title">       <input type="text" wire:model="targets.{{ $k }}.url">
 

        </div>

   @endforeach






</div>

