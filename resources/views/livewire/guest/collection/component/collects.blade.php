<?php

use Livewire\Volt\Component;

new class extends Component {
    public $collections;

    public function mount($collections)
    {
        $this->collections=$collections;
    }

}; ?>

<div class="">


  <div >
    <div x-ref="container" x-on:drag="dragging" class=" flex flex-row h-24 overflow-x-auto max-w-full scrollbar  pl-20 ">
        @foreach ($collections as $collection)
        <x-mini-collection-slider-component x-on:drag="dragging" x-ref="tab" :slug="$collection['slug']" :url="$collection['url']" :alt="$collection['name']" :name="$collection['name']" :remise="$collection['remise']" :type="$collection['type']" />
        @endforeach
{{--
      <x-mini-collection-slider-component :slug="'ceci'" imgUrl="https://th.bing.com/th/id/OIP.22tPt9bi1FRyjOYQI8irPQHaD4?pid=ImgDet&rs=1" :alt="''" :name="'collection'" />
 --}}
    </div>
  </div>

</div>

