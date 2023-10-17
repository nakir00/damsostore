@props(['active','titre'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-4 py-2 mt-5 text-black bg-gray-100 rounded-md font-medium'
            : 'flex items-center px-4 py-2 mt-5 text-gray-600 transition-colors duration-300 transform rounded-md hover:bg-gray-100 hover:text-gray-700';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
    <span class="mx-4">{{$titre}}</span>
</a>
