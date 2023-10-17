@props([
    'model',
    'label'=>'label exemple',
    'type'=> 'text',
    'placeholder'=>'ceci est un placeholder',
    'autocomplete'=>"name"
])

<div >

    <x-input-label for="{{$model}}" >{{$label}}</x-input-label>

    <div class="mt-2">
        <x-text-input wire:model="{{$model}}" id="{{$model}}" name="{{$model}}" type="{{$type}}" placeholder="{{$placeholder}}"  autocomplete="{{$autocomplete}}" />
    </div>

    <x-input-error :messages="$errors->get($model)" class="mt-2" />

</div>
