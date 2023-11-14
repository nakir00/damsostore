<?php

use Livewire\Volt\Component;

new class extends Component {
    public $collections;
};
?>

<div>

@foreach ($collections as $collection)
<livewire:guest.welcome.component.product-slider :$collection/>
@endforeach
</div>
