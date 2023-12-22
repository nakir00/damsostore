<div>
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}

    <div class="mt-20  ">
        @livewire('notifications')
        {{-- Stop trying to control. --}}
        <div class=" mx-4 md:mx-10 lg:mx-32 flex flex-col justify-between h-16">


            <span class="text-4xl  flex-row">
                Recherches
            </span>
            <div class="w-full flex flex-row justify-start items-center drop-shadow-sm border rounded-lg border-black focus:ring ring-black group">
                <x-filament::input
                class="group-focus:ring ring-black"
                    type="search"
                    wire:model.live="search"
                />
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>

            </div>

        </div>

        <div>
            @if ($products===[])
                        <div class="mt-10 mx-4 md:mx-10 lg:mx-32 flex justify-center">
                             <span>aucun resultat trouvé pour : {{$search}}</span>
                        </div>
                    @endif
            <div class="mt-6 grid grid-cols-2 gap-x-6 gap-y-10 md:grid-cols-3 lg:grid-cols-4 xl:gap-x-8  mx-4 md:mx-10 lg:mx-32 ">


                @foreach ($products as $product)

                    <x-product-view :name="$product['name']" :img="$product['url']" :alt="$product['alt']" :slug="$product['slug']" :price="$product['price']" :remise="$product['remise']" :type="$product['type']"/>

                @endforeach
                <!-- More products... -->
            </div>
        </div>
    </div>
</div>
