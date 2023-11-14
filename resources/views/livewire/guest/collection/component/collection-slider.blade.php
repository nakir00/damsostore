<div x-data="{swiper: null}"
  x-init="swiper = new Swiper($refs.container, {
      speed: 4000,
      autoPlay:true,
      slidesPerView: 3,
      spaceBetween: 6,
      breakpoints: {
        640: {
          slidesPerView: 4,
          spaceBetween: 6,
        },
        768: {
          slidesPerView: 5,
          spaceBetween: 6,
        },
        1024: {
          slidesPerView: 6,
          spaceBetween: 6,
        },
      },
    })"
  class="relative w-full overflow-visible mt-4  mx-auto flex flex-row"
>
@php
    $add = 6-count($collections);
    if($add<0){$add=1;}else{$add=$add+1;};
@endphp

  <div class="swiper-container overflow-hidden" x-ref="container">
    <div class="swiper-wrapper pl-20">
        @foreach ($collections as $collection)
            <x-mini-collection-slider-component :slug="$collection['slug']" :url="$collection['url']" :alt="$collection['name']" :name="$collection['name']" />
        @endforeach

        @for ($i = 0; $i <$add ; $i++)
        <div class="swiper-slide">

        </div>
        @endfor

{{--
      <x-mini-collection-slider-component :slug="'ceci'" imgUrl="https://th.bing.com/th/id/OIP.22tPt9bi1FRyjOYQI8irPQHaD4?pid=ImgDet&rs=1" :alt="''" :name="'collection'" />
 --}}
    </div>
  </div>

</div>
