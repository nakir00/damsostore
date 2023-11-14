@props(['product','key'])

{{-- @php
    dd($product,$key)
@endphp --}}

<div class="flex flex-col">
    <div class="flex py-4" >
        <img class="object-cover w-16 h-16 rounded" src="{{ $product['url'] }}">
        <div class="flex-1 ml-4">
            <a class="max-w-[20ch] hover:underline text-sm font-medium" href="{{route('product',['slug'=> $product['slug'] ] )}}" >
                {{ $product['name'] }}
            </a>
            <span class="block mt-1 text-xs text line-through text-gray-500">
                {{number_format($product['old_price'], 0, ',', ' ')}} franc cfa
            </span>
        </div>
        <div>
            @if (!empty($product['options']))
           {{--  @php
                $this->incrementCount();
            @endphp --}}
                <div class="flex items-center mr-10" >
                    <p class="ml-2 text-xs">
                        {{ $product['options']['name'] }} :
                    </p>
                    <select wire:model="products.{{ $key }}" name="products.{{ $key }}"class="w-16 p-2 text-xs transition-colors border border-gray-100 rounded-lg hover:border-gray-200" wire:init='updateProducts({{$key}})' required>
                        <option selected="selected" value="*">*</option>
                        @foreach ($product['options']['values'] as $value)
                            <option value="{{$value['name']}}">{{$value['name']}}</option>
                        @endforeach
                    </select>
                </div>
            @endif

        </div>
        <div>

        </div>
    </div>
    @if ($errors->get('products.' . $key ))
                        <div class="p-2 mb-4 text-xs font-medium text-center text-red-700 rounded bg-red-50"
                            role="alert">
                            @foreach ($errors->get('products.' . $key) as $error)
                                {{ $error }}
                             @endforeach
                        </div>
    @endif
</div>
