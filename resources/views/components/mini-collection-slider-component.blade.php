@props(['slug'=>'nom','name'=>'component','url','alt'])

<a href="/collection/{{$slug}}" class='swiper-slide flex flex-row justify-center items-center border border-black rounded py-1 px-3 mr-4'>
    <div class="mr-3  z-0 ">
        <img  class="h-12 min-w-full " src="{{$url}}" alt="{{$alt}}">
    </div>
    <span class="whitespace-nowrap">
        {{$name}}
    </span>
</a>
