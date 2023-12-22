@php
    $compte=count($products);
@endphp

<div class="relative" >

        <div class="sticky top-16 z-10 bg-white">
            {{-- Because she competes with no one, no one can compete with her. --}}
            <div class="flex justify-between items-center px-6 w-full border-y py-2">

                <span class="text-gray-500	text-sm	">
                    <span class="text-black">
                        {{$compte}}
                    </span>
                    résultats
                </span>

                <x-filament::modal  width="4xl">
                    {{-- <x-slot name="trigger">
                        filtrer
                    </x-slot> --}}
                    <x-slot name="header">
                        filtrer
                    </x-slot>
                    <x-slot name="footer">
                        <x-filament::button  >
                            Appliquer
                        </x-filament::button>
                    </x-slot>

                    {{ $this->form }}
            {{-- Modal content --}}
                </x-filament::modal>

            </div>
        </div>



    <div>
        <div class="mt-6 grid grid-cols-2 gap-x-6 gap-y-10 md:grid-cols-3 lg:grid-cols-4 xl:gap-x-8">
            @foreach ($products as $product)

                <x-product-view :name="$product['name']" :img="$product['url']" :alt="$product['alt']" :slug="$product['slug']" :price="$product['price']" :remise="$product['remise']" :type="$product['type']"/>

            @endforeach
            <!-- More products... -->
        </div>
    </div>

</div>
