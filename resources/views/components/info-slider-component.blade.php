@props(['imgUrl','info'])

<div class="swiper-slide flex flex-row bg-white">
    <div class="flex flex-col rounded shadow overflow-hidden">
      <div class="flex-shrink-0 h-8">
        <img class="h-6 object-cover" src="{{$imgUrl}}" alt="">
      </div>
    </div>
    <span class=" whitespace-nowrap ">{{$info}}</span>
</div>
