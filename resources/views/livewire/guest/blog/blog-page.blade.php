<div>
    <div class="w-full h-16">

@php
    $h=600;
    $w=600;
    $remise=12000
@endphp
    </div>
    <div class="mx-4 md:mx-10 lg:mx-32 flex flex-col justify-around">

        <section class="text-gray-600 body-font">
            <h1 class=" text-3xl font-semibold"> News et Astuces</h1>
            <div class="container mx-auto flex px-5 py-12 md:flex-row flex-col justify-between items-center">
              <div class="lg:max-w-lg lg:w-full md:w-1/2 w-5/6">
                <div class="relative">
                    <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200  group-hover:opacity-75 lg:h-80">
                        <x-curator-glider
                            class="object-cover rounded-md w-auto h-90"
                            :media="$top->miniature_image_id"
                            :width="$w"
                            :height="$h"
                        />
                    </div>
                    <div class="z-[5] absolute flex items-center justify-center   left-0 m-3 top-0 ">
                        @foreach ($top->tags()->get()->all() as $tag)
                            <div class="bg-black border-white rounded-md ml-4 w-auto ">
                                <span class="text-white font-medium py-4 mx-2 text-lg" > {{$tag->name}} </span>
                            </div>
                        @endforeach
                    </div>
                </div>
              </div>

              <div class="lg:flex-grow md:w-1/2 ml-10 lg:pr-24 md:pr-16 flex flex-col md:items-start md:text-left mb-16 md:mb-0 items-center text-center">
                <span class=" text-xs">{{ \Carbon\Carbon::parse($top->created_at)->format('j F, Y') }}</span>
                <h1 class="title-font sm:text-4xl text-3xl mb-1 font-medium text-gray-900">{{$top->title}}</h1>
                @if ($top->subtitle!==null ||$top->subtitle!=='') <span class="title-font sm:text-xl text-gray-900 font-semibold text-lg">{{$top->subtitle}}</span> @endif
                <p class="mb-8 leading-relaxed">{{$top->description}}</p>
                    <div class="flex justify-center">
                        <a class=" text-neutral-800 font-semibold inline-flex items-center mt-4"> Lire l'article
                            <svg class="w-4 h-4 ml-2" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"></path>
                            <path d="M12 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
              </div>

            </div>
          </section>
    </div>
        {{-- Do your work, then step back. --}}
        <section class="text-gray-600 body-font">
            <div class="container px-5 py-24 mx-auto">
              <div class="flex flex-wrap w-full mb-20">
                <div class="lg:w-1/2 w-full mb-6 lg:mb-0">
                  <h1 class="sm:text-3xl text-2xl font-medium title-font mb-2 text-gray-900">Voir plus d'articles :</h1>
                </div>
                <p class="lg:w-1/2 w-full leading-relaxed text-gray-500">Whatever cardigan tote bag tumblr hexagon brooklyn asymmetrical gentrify, subway tile poke farm-to-table. Franzen you probably haven't heard of them man bun deep jianbing selfies heirloom prism food truck ugh squid celiac humblebrag.</p>
              </div>
              <div>
                <div class="mt-6 grid grid-cols-2 gap-x-6 gap-y-10 md:grid-cols-3 lg:grid-cols-4 xl:gap-x-8">
                    @foreach ($articles as $article)

                        <x-article-view :tag="$article->tags?->first()?->name" :title="$article->title" :date="\Carbon\Carbon::parse($article->created_at)" />

                    @endforeach
                    <!-- More products... -->
                </div>
            </div>
            </div>

          </section>
    {{$articles->links() }}
</div>
