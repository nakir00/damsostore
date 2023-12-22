@props(['slug'=>'nom','name'=>'component','url','alt','remise'=>null,'type'=>null])

<a href="/collection/{{$slug}}" class=' min-w-fit min-h-fit unselectable'>
    <div class="flex flex-row justify-center items-center border border-black rounded h-16 mx-3 my-1">
        <div class="rounded-md flex justify-center items-center bg-black w-auto h-8  m-3  ">
            @if ($remise)
                <span class="text-white font-semibold py-4 mx-2 whitespace-nowrap text-xs" > - {{number_format((int)$remise, 0, ',', ' . ')}} @if($type==='percentage')%@else f cfa @endif</span>
            @endif
        </div>
        <div class="mr-3  z-0 ">
            <img  class="h-12 min-w-full " src="{{$url}}" alt="{{$alt}}">
        </div>
        <span class="whitespace-nowrap mr-2">
            {{$name}}
        </span>
    </div>
</a>
