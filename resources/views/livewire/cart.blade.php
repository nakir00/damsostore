<?php

use function Livewire\Volt\{state, mount,on};

//
    state(['visible'=>false, 'cart'=>null,'nombre'=>0,'somme'=>0]);

    $initCart = fn () => $this->cart=['lines'=>[],'subTotal'=>0];
    $emptyCart = fn () => $this->cart=null;
    $countCart=function(){if($this->cart!==null){return count($this->cart['lines']);}return 0;};
    $removeOne=function($key){unset($this->cart['lines'][$key]);$this->dispatch('mountNombre')->self();$this->dispatch('mountSomme')->self();if(empty($this->cart['lines'])){$this->emptyCart();}};
    $plus=function($key){$this->cart['lines'][$key]['quantity']+=1;$this->dispatch('mountNombre')->self();$this->dispatch('mountSomme')->self();};
    $minus=function($key){if($this->cart['lines'][$key]['quantity']===1){$this->removeOne($key);return;}$this->cart['lines'][$key]['quantity']-=1;$this->dispatch('mountNombre')->self();$this->dispatch('mountSomme')->self();};
    $somme=function(){if($this->cart!==null){$somme=0;foreach ($this->cart['lines'] as $value) {$somme+=$value['price']*$value['quantity'];}return$somme;}};

    on([
        'addedProduct'=>function(array $value){
            if ($this->cart===null) {
                $this->initCart();
                $this->visible=true;
            }elseif (is_array($this->cart)) {
                if(!array_key_exists('options',$value))
                {
                    $value['options']=['name'=>'produit'];
                }
                if(array_key_exists($value["slug"].'.'.$value['option'],$this->cart['lines']))
                {
                    $this->plus($value["slug"].'.'.$value['option']);
                    $this->visible=true;
                    return;
                }
            }

            if(!array_key_exists('options',$value)&&!array_key_exists('products',$value))
            {
                $value['options']=['name'=>'produit'];
                $this->cart['lines'][$value["slug"].'.'.$value['option']]=$value;
            }
            elseif(array_key_exists('options',$value)){
                $this->cart['lines'][$value["slug"].'.'.$value['option']]=$value;

            }elseif (array_key_exists('products',$value)) {
                dd($value);
                $this->cart['lines'][$value["slug"].'.'.$value['option']]=$value;
            }
            $this->dispatch('mountNombre')->self();
            $this->dispatch('mountSomme')->self();
            $this->visible=true;
        },
        'mountNombre'=>fn()=>$this->nombre=$this->countCart(),
        'mountSomme'=>fn()=>$this->somme=$this->somme(),
    ]);

?>

<div class="sm:relative"
     x-data="{
        linesVisible : @entangle('visible').live,
        cart: $persist(@entangle('cart').live),
    }"
    x-init="
        $dispatch('mountNombre');
        $dispatch('mountSomme');
    "
     >

        <button class="group -m-2 flex items-center p-2 "
            x-on:click="linesVisible = !linesVisible; ">

            <span class="sr-only">Cart</span>
                <svg class="h-6 w-6 flex-shrink-0 text-black group-hover:text-gray-800" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
            <span class="ml-2 text-sm font-medium text-gray-700 group-hover:text-gray-800" >{{$nombre===0?0:$nombre}}</span>

            <span class="sr-only">items in cart, view bag</span>

        </button>

        <div class="absolute inset-x-0 top-auto z-50 w-screen max-w-sm px-6 py-8 mx-auto mt-6 bg-white border border-gray-100 shadow-xl sm:left-auto rounded-xl"
         x-show="linesVisible"
         x-on:click.away="linesVisible = false;"
         x-transition
         x-cloak>
                <button class="absolute text-gray-900 transition-transform top-3 right-3 hover:scale-110"
                        type="button"
                        aria-label="Close"
                        x-on:click="linesVisible = false">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                </button>

                <div>
                    @if ($cart)
                        @if ($cart['lines'])
                            <div class="flow-root" >
                                <ul class="-my-4 overflow-y-auto divide-y divide-gray-100 max-h-96">
                                    @foreach ($cart['lines'] as $index => $line)
                                        <li>
                                            <div class="flex py-4"  wire:key="line_{{ $line['slug'] }}">

                                                <div class="flex flex-row">
                                                    <a href="{{route('product',['slug'=>$line['slug']])}}">
                                                        <img class="object-cover w-16 h-16 rounded" src="{{ $line['url'] }}">
                                                    </a>
                                                </div>

                                                <div class="flex-1 ml-4">
                                                    <a href="{{route('product',['slug'=>$line['slug']])}}" class="max-w-[20ch] text-sm font-medium">
                                                        {{ $line['name'] }}
                                                    </a>

                                                    <div class="flex justify-between mt-1 text-xs text-gray-500">
                                                        <span class="truncate">
                                                            {{ $line['options']['name'] }} : {{ $line['option'] }}
                                                        </span>
                                                        <span class="ml-4">
                                                            @ prix : {{ $line['price']  }} fcfa
                                                        </span>

                                                    </div>

                                                    <div class="flex items-center mt-2">
                                                        <button class="p-2 mr-1 text-gray-600 transition-colors rounded-lg hover:bg-gray-100 hover:text-gray-700"
                                                                type="button"
                                                                wire:click="minus('{{$line['slug'].'.'.$line['option']}}')">

                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                             fill="none"
                                                             viewBox="0 0 24 24"
                                                              stroke-width="1.5"
                                                              stroke="currentColor"
                                                              class="w-5 h-5">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                              </svg>

                                                        </button>

                                                        <input disabled value="{{$line['quantity']}}" class="w-6 p-2 text-xs transition-colors border border-gray-100 rounded-lg hover:border-gray-200"

                                                            {{-- wire:model="lines.{{ $index }}.quantity" --}} />
                                                        <button class="p-2 ml-1 text-gray-600 transition-colors rounded-lg hover:bg-gray-100 hover:text-gray-700"
                                                            type="button"
                                                            wire:click="plus('{{$line['slug'].'.'.$line['option']}}')">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                              </svg>

                                                        </button>

                                                        <button class="p-2 ml-28 text-gray-600 transition-colors rounded-lg hover:bg-red-100 hover:text-red-700"
                                                                type="button"
                                                                wire:click="removeOne('{{$line['slug'].'.'.$line['option']}}')">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="w-4 h-4"
                                                                fill="none"
                                                                viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </div>

                                                </div>


                                            </div>

                                            {{-- @if ($errors->get('lines.' . $index . '.quantity'))
                                                <div class="p-2 mb-4 text-xs font-medium text-center text-red-700 rounded bg-red-50"
                                                    role="alert">
                                                    @foreach ($errors->get('lines.' . $index . '.quantity') as $error)
                                                        {{ $error }}
                                                    @endforeach
                                                </div>
                                            @endif --}}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <p class="py-4 text-sm font-medium text-center text-gray-500">
                                Votre panier est vide
                            </p>
                        @endif

                        <dl class="flex flex-wrap pt-4 mt-6 text-sm border-t border-gray-100">
                            <dt class="w-1/2 font-medium">
                                somme :
                            </dt>

                            <dd class="w-1/2 text-right">
                                {{ number_format($somme, 0, ',', ' . ') }} fcfa
                            </dd>
                        </dl>
                        <div class="flex flex-col items-center" >
{{--                             <progress  class="w-full h-4 rounded-md bg-gray-600 " value="50" max="100"></progress>
 --}}                   </div>


                    @else
                        <p class="py-4 text-sm font-medium text-center text-gray-900">
                            Votre panier est vide
                        </p>
                    @endif
                </div>

                @if ($cart)
                    <div class="mt-4 space-y-4 text-center">
                        {{-- <button class="block w-full p-3 text-sm font-medium text-black border border-black rounded-lg hover:ring-1 hover:ring-black"
                                type="button"
                                wire:click="updateLines">
                            mettre à jour le panier
                        </button> --}}

                        <a class="block w-full p-3 text-sm font-medium text-center text-white bg-black rounded-lg hover:bg-gray-600 "
                        href="{{ route('home') }}">
                            commander
                        </a>

                        <button class="inline-block text-sm font-medium text-gray-600 underline hover:text-gray-500" x-on:click="linesVisible = false;" >

                            Continuer mes achats
                        </button>
                    </div>
                @endif
        </div>

</div>

