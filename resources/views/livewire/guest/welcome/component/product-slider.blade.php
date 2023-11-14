<div x-data="{swiper: null}"
x-init="swiper = new Swiper($refs.container, {
    slidesPerView: 2,
    spaceBetween: 0,

    breakpoints: {
      640: {
        slidesPerView: 2,
        spaceBetween: 0,
      },
      768: {
        slidesPerView: 3,
        spaceBetween: 0,
      },
      1024: {
        slidesPerView: 4,
        spaceBetween: 0,
      },
    },
  })"
class=" my-20">
    <div class="overflow-hidden relative">
        <div class="justify-between flex items-center" >
            <div class="mr-auto text-gray-800 text-3xl ">
                <p>{{$collection['name']}}</p>
            </div>
            <div class="flex items-center ml-3">

                <div class="relative flex items-center justify-center bg-white" >
                    <a href="collection/{{$collection['slug']}}" class="outline-none transition duration-75  gap-1.5 px-3 py-2">
                        <span class="font-semibold text-sm text-black" >
                            Voir Plus
                        </span>
                    </a>
                </div>

                <div class="flex items-center justify-around ml-8 ">
                    <button @click="swiper.slidePrev(2000)" class="bg-white mr-1 ml-2 lg:ml-4 flex justify-center items-center w-10 h-10 border rounded-full shadow focus:outline-none">
                        <svg viewBox="0 0 20 20" fill="currentColor" class="chevron-left w-6 h-6"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                    </button>
                    <button @click="swiper.slideNext(2000)" class="bg-white mr-2 lg:mr-4 flex justify-center items-center w-10 h-10 border rounded-full shadow focus:outline-none">
                        <svg viewBox="0 0 20 20" fill="currentColor" class="chevron-right w-6 h-6"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
            </div>

        </div>
        <div class="relative overflow-visible touch-pan-y">
            <div class="swiper-container mt-4" x-ref="container">
                <div class="swiper-wrapper">
                  <!-- Slides -->

                  @foreach ($collection['products'] as $product)

                        <x-product-view class="swiper-slide p-2" :alt="$product['alt']" :img="$product['url']" :slug="$product['slug']" :name="$product['name']" :oldprice="0" :price="$product['price']" />

                  @endforeach

                </div>
              </div>
        </div>
    </div>
    <div class="relative mt-7">

    </div>
</div>
