@props(['img','alt','slug','name','oldprice','price'])



<div {{ $attributes->merge(['class' => 'group relative']) }}>

    <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80">
      <img src="{{$img}}" alt="{{$alt}}" class="h-full w-full object-cover object-center lg:h-full lg:w-full">
    </div>

    <div class="mt-4 flex justify-between">
      <div>
        <h3 class="text-sm text-gray-700">
          <a href="{{route('product',$slug)}}">
            <span aria-hidden="true" class="absolute inset-0"></span>
           {{$name}}
          </a>
        </h3>
        <p class="mt-1 text-sm text-gray-500">Black</p>
      </div>
      <p class="text-sm font-medium text-gray-900">{{$price}} fcfa</p>
    </div>

</div>
