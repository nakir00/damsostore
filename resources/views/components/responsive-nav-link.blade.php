@props(['active','titre'])

@php
$classes = ($active ?? false)
            ? 'flex items-center pl-4 py-2 mt-5 text-white bg-black shadow-md rounded-md font-medium'
            : 'flex items-center pl-4 py-2 mt-5 text-black bg-white transition-colors duration-300 transform rounded-md hover:bg-black hover:text-white hover:drop-shadow-md';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} wire:navigate>
    {{ $slot }}
    <span class="mx-4">{{$titre}}</span>
</a>
