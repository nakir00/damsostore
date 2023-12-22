@props(['img','alt'=>'','slug','name','oldprice','remise'=>null,'price','type'=>null])



<div {{ $attributes->merge(['class' => 'group relative']) }}>

    <div class="relative">
        <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200  group-hover:opacity-75 lg:h-80">
            <img src="{{$img}}" alt="{{$alt}}" class="h-full w-full object-cover object-center lg:h-full lg:w-full">
        </div>
        <div class="z-[5] absolute flex items-center justify-center rounded-md bg-black w-auto h-8 right-0 m-3 top-0 ">
            @if ($remise)
                <span class="text-white font-semibold py-4 mx-2   text-xs" > - {{number_format((int)$remise, 0, ',', ' . ')}} @if($type==='percentage')%@else f cfa @endif</span>
            @endif
        </div>
    </div>

    <div class="mt-4 flex justify-between flex-col">
        <div>
        <h3 class="text-base text-black  font-semibold">
            <a href="{{route('product',$slug)}}">
            <span aria-hidden="true" class="absolute inset-0"></span>
            {{$name}}
            </a>
        </h3>
        <p class="text-base font-xs text-gray-600"> à partir de <span class="text-semibold font-base text-black">{{ number_format($price, 0, ',', ' . ')}} f cfa</span></p>
        </div>
    </div>


</div>
