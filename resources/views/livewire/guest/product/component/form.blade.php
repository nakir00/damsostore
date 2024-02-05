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
<div class="mt-4 h-full lg:row-span-3 flex   flex-col lg:mt-0 ml-3">
    <div>
        <h2 class="sr-only">Product information</h2>

        <div class="my-4">
            <x-breadcrumb :breadcrumbs="$form['breadcrumb']" />
        </div>

        <div class="lg:grid lg:grid-cols-2 items-center lg:col-span-1  lg:border-gray-200 ">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">{{$form['name']}}</h1>
            <form wire:submit.prevent='updateDiscount'>
                <div class="grid grid-cols-5 gap-4" >
                    <x-input.group class="col-span-3"
                                   label="Coupon"
                                   :errors="$errors->get('coupon')"
                                   required>
                    <x-input.text class="md:h-8 lg:h-12 md:font-medium lg:font-bold uppercase" wire:model.defer='coupon' />

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

        </div>

            @if ($discount!==null)
            <p class="text-xl tracking-tight mt-6 flex flex-row mb-12 text-gray-900">
                <span class="md:text-base lg:text-xl tracking-tight text-gray-300 mr-2">Prix :   </span>
                <span class="md:text-base lg:text-xl tracking-tight mb-12 line-through text-gray-400 mr-2">{{number_format($oldPrice, 0, ',', ' ')}} Franc cfa</span>
                <span class="md:text-base lg:text-xl tracking-tight font-bold mb-12 text-black mr-2">{{number_format($newPrice, 0, ',', ' ')}} Franc cfa</span>

            </p>
            @else
            <p class="md:text-xl lg:text-3xl tracking-tight mt-6 flex flex-row mb-12 text-gray-900"><span class="text-xl tracking-tight text-gray-300 mr-2">Prix :   </span> {{number_format($oldPrice, 0, ',', ' ')}} Franc cfa </p>
            @endif




    </div>
    <!-- Reviews -->
    <!--<div class="mt-6">
        <h3 class="sr-only">Reviews</h3>
        <div class="flex items-center">
            <div class="flex items-center">
             Active: "text-gray-900", Default: "text-gray-200"
            <svg class="text-gray-900 h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" />
            </svg>
            <svg class="text-gray-900 h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" />
            </svg>
            <svg class="text-gray-900 h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" />
            </svg>
            <svg class="text-gray-900 h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" />
            </svg>
            <svg class="text-gray-200 h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" />
            </svg>
            </div>
            <p class="sr-only">4 out of 5 stars</p>
            <a href="#" class="ml-3 text-sm font-medium text-indigo-600 hover:text-indigo-500">117 reviews</a>
        </div>
    </div>-->

    <form wire:submit.prevent='addToCart'
    >
    <!-- Colors
        <div>
            <h3 class="text-sm font-medium text-gray-900">Color</h3>

            <fieldset class="mt-4">
            <legend class="sr-only">Choose a color</legend>
            <div class="flex items-center space-x-3">

                Active and Checked: "ring ring-offset-1"
                Not Active and Checked: "ring-2"

                <label class="relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none ring-gray-400">
                <input type="radio" name="color-choice" value="White" class="sr-only" aria-labelledby="color-choice-0-label">
                <span id="color-choice-0-label" class="sr-only">White</span>
                <span aria-hidden="true" class="h-8 w-8 bg-white rounded-full border border-black border-opacity-10"></span>
                </label>

                Active and Checked: "ring ring-offset-1"
                Not Active and Checked: "ring-2"

                <label class="relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none ring-gray-400">
                <input type="radio" name="color-choice" value="Gray" class="sr-only" aria-labelledby="color-choice-1-label">
                <span id="color-choice-1-label" class="sr-only">Gray</span>
                <span aria-hidden="true" class="h-8 w-8 bg-gray-200 rounded-full border border-black border-opacity-10"></span>
                </label>

                Active and Checked: "ring ring-offset-1"
                Not Active and Checked: "ring-2"

                <label class="relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none ring-gray-900">
                <input type="radio" name="color-choice" value="Black" class="sr-only" aria-labelledby="color-choice-2-label">
                <span id="color-choice-2-label" class="sr-only">Black</span>
                <span aria-hidden="true" class="h-8 w-8 bg-gray-900 rounded-full border border-black border-opacity-10"></span>
                </label>
            </div>
            </fieldset>
        </div> -->

        <div class="flex flex-col md:h-40 lg:h-80 justify-around">
                <!-- Sizes -->
            <div>
                @if (!empty($form['variants'])&&array_key_exists('values',$form['variants']))
                    <div class="{{-- flex items-center justify-between --}}">
                        <h3 class="text-sm font-semibold text-gray-900">{{$form['variants']['name']}}</h3>
        {{--                 <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Size guide</a>
        --}}       </div>
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
                                <x-mini-product-view :$product :key="$key" />
                            @endforeach
                        </div>
                    </div>
                </div>
                @else

                @endif
            </div>

            <div class="flex my-10 gap-4">
                <div>
                    <label for="quantity"
                        class="sr-only">
                        Quantity
                    </label>

                    <input class="w-16 px-1 py-4 text-sm text-center  transition border border-gray-100 rounded-lg no-spinner"
                        type="number"
                        id="quantity"
                        min="1"
                        value="2"

                        wire:model="quantity"
                        />

                </div>
                @if (!empty($form['variants'])&& array_key_exists('values',$form['variants']))
                    <button type="submit"
                            x-on:click="fbq('track', 'AddToCart', {
                                content_name: @entangle('content_name'),
                                content_category: 'Produit',
                                content_ids: [@entangle('content_name')],
                                content_type: 'product',
                                currency: 'FCFA',
                                value: @entangle('price'),
                                num_items: @entangle('quantity'),
                            });"
                            @if ($objet===null)
                                disabled
                                class="flex w-full items-center justify-center rounded-md border border-transparent bg-gray-400 px-8 py-3 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2"
                            @else
                                class="flex w-full items-center justify-center rounded-md border border-transparent bg-black px-8 py-3 text-base font-medium text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2"
                            @endif
                            >
                        Ajouter au panier
                    </button>

                @elseif (!empty($form['products']))
                    <button type="submit"
                                x-on:click="fbq('track', 'AddToCart', {
                                    content_name: @entangle('content_name'),
                                    content_category: 'Produit',
                                    content_ids: [@entangle('content_name')],
                                    content_type: 'product',
                                    currency: 'FCFA',
                                    value: @entangle('price'),
                                    num_items: @entangle('quantity'),
                                });"
                                class="flex w-full items-center justify-center rounded-md border border-transparent bg-black px-8 py-3 text-base font-medium text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2"
                            >
                        Ajouter au panier
                    </button>
                @else
                    <button type="submit"
                            class=" flex w-full items-center justify-center rounded-md border border-transparent bg-black px-8 py-3 text-base font-medium text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2"
                            x-on:click="fbq('track', 'AddToCart', {
                                content_name: @entangle('content_name'),
                                content_category: 'Produit',
                                content_ids: [@entangle('content_name')],
                                content_type: 'product',
                                currency: 'FCFA',
                                value: @entangle('price'),
                                num_items: @entangle('quantity'),
                            });"
                            wire:click="$dispatch('added', { added: {{json_encode($form['variants'])}} })">
                        Ajouter au panier
                    </button>
                @endif
            </div>
        </div>
    </form>
</div>
