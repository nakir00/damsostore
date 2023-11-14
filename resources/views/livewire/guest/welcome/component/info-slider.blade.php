<div x-data="{swiper: null}"
  x-init="swiper = new Swiper($refs.container, {
      loop: true,
      freeMode: true,
      speed:8000,
      slidesPerView: auto,
      spaceBetween: 0,
      autoplay: {
        delay: 100,
        disableOnInteraction: false,
      },
    })"
  class="absolute mx-auto flex flex-row w-full z-50 bg-white bottom-0"
>

  <div class="bg-white">
    <div class="swiper-container overflow-hidden w-screen bg-white" x-ref="container">

        <div class="swiper-wrapper w-full bg-white">
          <!-- Slides -->

          <x-info-slider-component :imgUrl="'https://images.unsplash.com/photo-1496128858413-b36217c2ce36?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1679&q=80'" :info="'ceci est un texte ceci est un texte'"></x-info-slider-component>

          <x-info-slider-component :imgUrl="'https://images.unsplash.com/photo-1496128858413-b36217c2ce36?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1679&q=80'" :info="'ceci est un texte'"></x-info-slider-component>
          <x-info-slider-component :imgUrl="'https://images.unsplash.com/photo-1496128858413-b36217c2ce36?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1679&q=80'" :info="'ceci est un texte'"></x-info-slider-component>
          <x-info-slider-component :imgUrl="'https://images.unsplash.com/photo-1496128858413-b36217c2ce36?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1679&q=80'" :info="'ceci est un texte'"></x-info-slider-component>
          <x-info-slider-component :imgUrl="'https://images.unsplash.com/photo-1496128858413-b36217c2ce36?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1679&q=80'" :info="'ceci est un texte'"></x-info-slider-component>
          <x-info-slider-component :imgUrl="'https://images.unsplash.com/photo-1496128858413-b36217c2ce36?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1679&q=80'" :info="'ceci est un texte'"></x-info-slider-component>
          <x-info-slider-component :imgUrl="'https://images.unsplash.com/photo-1496128858413-b36217c2ce36?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1679&q=80'" :info="'ceci est un texte'"></x-info-slider-component>
          <x-info-slider-component :imgUrl="'https://images.unsplash.com/photo-1496128858413-b36217c2ce36?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1679&q=80'" :info="'ceci est un texte'"></x-info-slider-component>
          <x-info-slider-component :imgUrl="'https://images.unsplash.com/photo-1496128858413-b36217c2ce36?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1679&q=80'" :info="'ceci est un texte'"></x-info-slider-component>
          <x-info-slider-component :imgUrl="'https://images.unsplash.com/photo-1496128858413-b36217c2ce36?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1679&q=80'" :info="'ceci est un texte'"></x-info-slider-component>
          <x-info-slider-component :imgUrl="'https://images.unsplash.com/photo-1496128858413-b36217c2ce36?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1679&q=80'" :info="'ceci est un texte'"></x-info-slider-component>
          <x-info-slider-component :imgUrl="'https://images.unsplash.com/photo-1496128858413-b36217c2ce36?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1679&q=80'" :info="'ceci est un texte'"></x-info-slider-component>

        </div>
      </div>
  </div>
</div>
