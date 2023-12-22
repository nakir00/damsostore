@php
    if($discount!==null)
    {
        if($discount->data['type']==='percentage')
        {
            $reduces=($price*$discount->data['percentage'])/100;
        }
        else {
            $reduce=$discount->data['fixed_values'];
        }
        $reduce=$reduces;
        $newPrice=$price-$reduce;
        $breakPrice=$newPrice;
    }
    $oldPrice=$price;
@endphp

<div class="flex flex-col relative  justify-start">


    <div class="">
        <div x-data="{swiper: null}"
            x-init="swiper = new Swiper($refs.container, {
                slidesPerView: 1,
                spaceBetween: 0,
                autoplay: {
                    delay: 3000,
                },
                })"
            class="relative w-full items-center h-[32rem] mx-auto justify-end  flex flex-col"
        >

            <div class=" absolute z-10 left-0 top-10 justify-end flex flex-row w-full  items-center py-6">
                <div></div>
                <div class=" flex items-center justify-center rounded-md bg-black w-auto h-8 right-0 mr-16 top-0 ">
                    @if ($discount!==null)
                        <span class="text-white font-semibold py-4 mx-2   text-xs" > - @if($discount->data['type']==='percentage') {{number_format((int)$discount->data['percentage'], 0, ',', ' . ')}} %@else{{number_format((int)$discount->data['fixed_values'], 0, ',', ' . ')}} f cfa @endif</span>
                    @endif
                </div>
            </div>

            <div class="swiper-container h-full bg-black opacity-90 w-full overflow-hidden " x-ref="container">
                <div class="swiper-wrapper">
                <!-- Slides -->

                    @foreach ($images as $image)
                        <div class=" swiper-slide w-full h-full relative overflow-hidden ">
                            <img src="{{$image}}" alt="Model wearing plain black basic tee." class="h-full w-full object-cover object-center">
                        </div>
                    @endforeach

                </div>
            </div>


        </div>
    </div >

    <div class="ml-12 mt-4">
        <x-breadcrumb :breadcrumbs="$form['breadcrumb']" />
    </div>

    <div class=" border border-black m-5">
        <div class="justify-around flex flex-row w-full  items-center  py-6">
            <h1 class="text-3xl font-bold tracking-tight text-black">{{$form['name']}}</h1>
            <div class=" flex items-center justify-center rounded-md bg-black w-auto h-8 right-0 m-3 top-0 ">
                @if ($discount!==null)
                    <span class="text-white font-semibold py-4 mx-2   text-xs" > - @if($discount->data['type']==='percentage') {{number_format((int)$discount->data['percentage'], 0, ',', ' . ')}} %@else{{number_format((int)$discount->data['fixed_values'], 0, ',', ' . ')}} f cfa @endif</span>
                @endif
            </div>
        </div>

         <div class="flex flex-col items-center">
            @if ($discount!==null)
                <p class=" justify-between text-sm tracking-tight flex flex-row text-gray-900">
                    <span class="text-sm tracking-tight ml-3 text-gray-300 mr-2">Prix :   </span>
                    <span class="text-sm tracking-tight ml-3 line-through text-gray-400 mr-2">{{number_format($oldPrice, 0, ',', ' ')}} Franc cfa</span>
                    <span class="text-sm tracking-tight ml-3 font-bold text-black mr-2">{{number_format($newPrice, 0, ',', ' ')}} Franc cfa</span>
                </p>
                @else
                <p class="text-base tracking-tight flex flex-row text-gray-900"><span class="text-sm tracking-tight text-gray-300 mr-2">Prix :   </span> {{number_format($oldPrice, 0, ',', ' ')}} Franc cfa </p>
            @endif
        </div>

        <div class="p-4">
            <button
            x-on:click="$dispatch('open-modal', { id : 'add-panier' })"
            class="flex w-full items-center justify-center rounded-md border border-transparent bg-black p-3 font-medium text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
                Ajouter au panier
            </button>
        </div>



    </div>



    <x-filament::modal id="add-panier" :close-by-clicking-away="false" width="5xl" >
        <x-slot name="heading">
            choix des tailles
        </x-slot>

        <div class="justify-start flex flex-col items-center mx-2">
            @if ($discount!==null)
                <p class="text-sm tracking-tight flex flex-row text-gray-900">
                    <span class="text-sm tracking-tight ml-3 text-gray-300 mr-2">Prix :   </span>
                    <span class="text-sm tracking-tight ml-3 line-through text-gray-400 mr-2">{{number_format($oldPrice, 0, ',', ' ')}} Franc cfa</span>
                    <span class="text-sm tracking-tight ml-3 font-bold text-black mr-2">{{number_format($newPrice, 0, ',', ' ')}} Franc cfa</span>
                </p>
                @else
                <p class="text-base tracking-tight flex flex-row text-gray-900"><span class="text-sm tracking-tight text-gray-300 mr-2">Prix :   </span> {{number_format($oldPrice, 0, ',', ' ')}} Franc cfa </p>
            @endif
        </div>
        <form wire:submit.prevent='updateDiscount'>
            <div class="grid grid-cols-5 gap-4 " >
                <x-input.group class="col-span-3"
                            label="Coupon"
                            :errors="$errors->get('coupon')"
                            required>
                    <x-input.text class="h-3 md:h-8 lg:h-12 md:font-medium lg:font-bold uppercase" wire:model.defer='coupon' />

                </x-input.group>
                <div class="flex col-span-2 flex-col justify-end">
                    <button type="submit"
                            class="flex w-full md:h-8 lg:h-12 items-center justify-center rounded-md border border-transparent bg-black lg:px-8 py-1 md:text-sm lg:text-base font-medium text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2"
                            >
                        Appliquer
                    </button>
                </div>

            </div>
        </form>
        <form wire:submit.prevent='addToCart'>

            <div class="flex flex-col md:h-40 lg:h-80 justify-around">
                <div>
                    @if (!empty($form['variants'])&&array_key_exists('values',$form['variants']))
                        <div class="{{-- flex items-center justify-between --}}">
                            <h3 class="text-sm font-semibold text-gray-900">{{$form['variants']['name']}}</h3>
                        </div>
                        <fieldset class="mt-4">
                            <legend class="sr-only">Choose a size</legend>
                            <div x-data="{selectedSize: null,}" class="grid grid-cols-6 gap-4 sm:grid-cols-8 lg:grid-cols-6">
                                        @foreach($form['variants']['values'] as $value)
                                            @if ($value['active'])
                                                <label x-bind:class="{ ' ring-black  ring-2 ': selectedSize === '{{$value['name']}}'}"
                                                    class="group relative flex items-center justify-center rounded-md border py-3 px-4 text-sm font-medium bg-black uppercase hover:bg-black focus:outline-none sm:flex-1 sm:py-6 cursor-pointer  text-white shadow-sm">
                                                        <input type="radio" name="size-choice" value="{{$value['name']}}" class="sr-only"
                                                        x-model="selectedSize"  wire:click="$dispatch('selected', { val: {{json_encode($value['object'])}} })">
                                                    <span>{{$value['name']}}</span>
                                                    <span class="pointer-events-none absolute -inset-px rounded-md" aria-hidden="true"></span>
                                                </label>
                                            @else
                                                <label class="group relative flex items-center justify-center rounded-md border py-3 px-4 text-sm font-medium uppercase hover:bg-gray-400 focus:outline-none sm:flex-1 sm:py-6 cursor-not-allowed bg-gray-400 text-gray-50">
                                                    <input type="radio" name="size-choice" value="{{$value['name']}}" disabled class="sr-only" aria-labelledby="size-choice-0-label">
                                                    <span id="size-choice-0-label">{{$value['name']}}</span>

                                                        <span aria-hidden="true" class="pointer-events-none absolute -inset-px rounded-md border-2 border-gray-200">
                                                            <svg class="absolute inset-0 h-full w-full stroke-2 text-gray-200" viewBox="0 0 100 100" preserveAspectRatio="none" stroke="currentColor">
                                                                <line x1="0" y1="100" x2="100" y2="0" vector-effect="non-scaling-stroke" />
                                                            </svg>
                                                        </span>
                                                </label>
                                            @endif
                                        @endforeach
                            </div>
                        </fieldset>
                    @elseif (!empty($form['products']))
                    <h3 class="text-sm font-semibold text-gray-900">Produits du kit : {{count($form['products'])}}</h3>
                    <div class="flex max-h-64">
                        <div class="flex-auto overflow-y-auto">
                            <div class="flex flex-col space-y-4">
                                @foreach($form['products'] as $key=> $product)
                                    <x-mini-product-view-sm :$product :key="$key" />
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @else

                    @endif
                </div>

                <div class="flex flex-col my-10 gap-4">

                    <label for="quantity"
                            class="sr-only">
                            Quantity
                    </label>
                    <div class=" flex flex-row w-full justify-center items-center">

                        <button class="p-4 mr-2  text-gray-600 transition-colors rounded-lg hover:bg-gray-100 hover:text-gray-700"
                            type="button"
                            wire:click="moins"
                            >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>

                        </button>

                        <input class="w-16 px-1 py-4 text-sm text-center  transition border border-gray-100 rounded-lg no-spinner"
                            type="number"
                            id="quantity"
                            min="1"
                            value="1"

                            wire:model="quantity"
                            />
                        <button class="p-4 ml-2  text-gray-600 transition-colors rounded-lg hover:bg-gray-100 hover:text-gray-700"
                            type="button"
                            wire:click="plus"
                            >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>

                        </button>

                    </div>
                    {{-- <x-slot name="footer"> --}}
                        {{-- Modal footer content --}}

                        @if (!empty($form['variants'])&& array_key_exists('values',$form['variants']))
                            <button type="submit"
                                    @if ($objet===null)
                                        disabled
                                        class="flex w-full items-center justify-center rounded-md border border-transparent bg-gray-400 px-8 py-3 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2"
                                    @else
                                        class="flex w-full items-center justify-center rounded-md border border-transparent bg-black px-8 py-3 text-base font-medium text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2"
                                    @endif
                                    {{-- x-on:click="$dispatch('close-modal', { id: 'add-panier' })" --}}>
                                Ajouter au panier
                            </button>

                        @elseif (!empty($form['products']))
                            <button type="submit"
                                    class="flex w-full items-center justify-center rounded-md border border-transparent bg-black px-8 py-3 text-base font-medium text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2"
                                    {{-- x-on:click="$dispatch('close-modal', { id: 'add-panier' })" --}}>
                                Ajouter au panier
                            </button>
                        @else
                            <button type="submit"
                                    class=" flex w-full items-center justify-center rounded-md border border-transparent bg-black px-8 py-3 text-base font-medium text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2"
                                    wire:click="$dispatch('added', { added: {{json_encode($form['variants'])}} })"
                                    {{-- x-on:click="$dispatch('close-modal', { id: 'add-panier' })" --}}>
                                Ajouter au panier
                            </button>
                        @endif
                    {{-- </x-slot> --}}
                </div>
            </div>
        </form>
    </x-filament::modal>


</div>
