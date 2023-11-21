@props([
    'error' => false,
])
<input {{ $attributes->merge([
        'type' => 'text',
        'class' => 'w-full p-3 border border-black  focus:ring-black rounded-lg sm:text-sm',
    ])->class([
        'border-red-400' => !!$error,
    ]) }}
       maxlength="255">
