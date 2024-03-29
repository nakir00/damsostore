<?php

use function Livewire\Volt\{state, rendered,on};
use Illuminate\Support\Facades\Session;

    state(['visible'=>false, 'cart'=>null,'nombre'=>0,'somme'=>0]);
    $session=fn()=>dd(Session::all());
    $initCart = function () {$this->cart=['lines'=>[],'subTotal'=>0];Session::put('cart',$this->cart);$this->dispatch('checkOutUpdate');};
    $emptyCart = function(){$this->cart=null;Session::forget('cart');$this->dispatch('checkOutUpdate');};
    $countCart=function(){if($this->cart!==null){return count($this->cart['lines']);}return 0;};
    $removeOne=function($key){unset($this->cart['lines'][$key]);$k="cart.lines.".$key;Session::forget($k);$this->dispatch('mountNombre')->self();$this->dispatch('mountSomme')->self();if(empty($this->cart['lines'])){$this->dispatch('emptyCart')->self();};$this->dispatch('checkOutUpdate');};
    $plus=function($key){$this->cart['lines'][$key]['quantity']+=1;$this->dispatch('mountNombre')->self();$this->dispatch('mountSomme')->self();$cle='cart.lines.'.$key.'.quantity';$q=Session::get($cle);Session::put($cle,$q+1);$this->dispatch('checkOutUpdate');};
    $minus=function($key){if($this->cart['lines'][$key]['quantity']===1){$this->removeOne($key);return;}$this->cart['lines'][$key]['quantity']-=1;$cle='cart.lines.'.$key.'.quantity';$q=Session::get($cle);Session::put($cle,$q-1);$this->dispatch('mountNombre')->self();$this->dispatch('mountSomme')->self();$this->dispatch('checkOutUpdate');};
    $somme=function(){if($this->cart!==null){$somme=0;foreach ($this->cart['lines'] as $value){if(!is_null($value['handle'])){$somme+=$value['breakPrice']*$value['quantity'];}else{$somme+=$value['price']*$value['quantity'];}}return$somme;}};
    on([
        'addedProduct'=>function(array $value){if ($this->cart===null) {$this->initCart();$this->visible=true;}elseif (is_array($this->cart)){if((!is_null($value['handle']))){if(array_key_exists($value['handle'].'.'.$value["slug"].'.'.$value['option'],$this->cart['lines'])){$this->plus($value['handle'].'.'.$value["slug"].'.'.$value['option']);$this->visible=true;return;}}else{if(array_key_exists($value["slug"].'.'.$value['option'],$this->cart['lines'])){$this->plus($value["slug"].'.'.$value['option']);$this->visible=true;return;}}}if(!array_key_exists('options',$value)&&!array_key_exists('products',$value)){$value['options']=['name'=>'produit'];if(!is_null($value['handle'])){$this->cart['lines'][$value['handle'].'.'.$value["slug"].'.'.$value['option']]=$value;$key="cart.lines.".$value['handle'].'.'.$value["slug"].'.'.$value['option'];}else{$this->cart['lines'][$value["slug"].'.'.$value['option']]=$value;$key="cart.lines.".$value["slug"].'.'.$value['option'];}Session::put($key,$value);}elseif(array_key_exists('options',$value)){if(!is_null($value['handle'])){$this->cart['lines'][$value['handle'].'.'.$value["slug"].'.'.$value['option']]=$value;$key="cart.lines.".$value['handle'].'.'.$value["slug"].'.'.$value['option'];}else{$this->cart['lines'][$value["slug"].'.'.$value['option']]=$value;$key="cart.lines.".$value["slug"].'.'.$value['option'];}Session::put($key,$value);}elseif (array_key_exists('products',$value)){if(!is_null($value['handle'])){$this->cart['lines'][$value['handle'].'.'.$value["slug"].'.'.$value['option']]=$value;$key="cart.lines.".$value['handle'].'.'.$value["slug"].'.'.$value['option'];}else{$this->cart['lines'][$value["slug"].'.'.$value['option']]=$value;$key="cart.lines.".$value["slug"].'.'.$value['option'];}Session::put($key,$value);}$this->dispatch('mountNombre')->self();$this->dispatch('mountSomme')->self();$this->visible=true;$this->dispatch('checkOutUpdate');},
        'mountNombre'=>fn()=>$this->nombre=$this->countCart(),
        'mountSomme'=>fn()=>$this->somme=$this->somme(),
        'emptyCart'=>function(){$this->cart=null;$this->nombre=0;$this->somme=0;Session::forget('cart');},
        'mountSession'=>function(){if($this->cart!==null&&!Session::has('cart')){Session::put('cart',['lines'=>[],'subTotal'=>0]);foreach ($this->cart['lines'] as $key => $value){$k='cart.lines.'.$key;Session::put($k,$value);}$this->dispatch('checkOutUpdate');}},]);
?>

<div class="sm:relative"
     x-data="{
        linesVisible : @entangle('visible').live,
        cart: $persist(@entangle('cart').live),
    }"
    x-init="
        $dispatch('mountNombre');
        $dispatch('mountSomme');
        $dispatch('mountSession');
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
{{-- absolute inset-x-0 lg:top-auto z-30 w-screen max-w-sm px-6 py-8 mx-auto mt-6 bg-white border border-gray-100 shadow-xl lg:left-auto rounded-xl --}}

        <div class="">
            <div class=" absolute inset-x-0 lg:top-auto z-40 w-screen max-w-sm px-6 py-8 mx-auto lg:mt-6 sm:mb-10 md:mb-10 bg-white border border-gray-100 shadow-xl left-auto rounded-xl"
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
                                                        <div class="flex flex-row justify-between items-center">
                                                            <a href="{{route('product',['slug'=>$line['slug']])}}" class="max-w-[20ch] text-sm font-medium">
                                                                {{ $line['name'] }}

                                                            </a>
                                                            @if(!is_null($line['handle']))
                                                                <div class=" flex items-center justify-center rounded-md bg-black w-auto h-8 m-3">
                                                                        <span class="text-white font-semibold py-4 mx-2   text-xs" > - {{number_format((int)$line['number'], 0, ',', ' . ')}} @if($line['type']==='percentage')%@else f cfa @endif</span>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <div class="flex justify-between mt-1 text-xs text-gray-500">
                                                            <span class="truncate">
                                                                {{ $line['options']['name'] }} : {{ $line['option'] }}
                                                            </span>
                                                            @if (is_null($line['handle']))

                                                            <span class="ml-4">
                                                                @ prix : {{ $line['price']  }} fcfa
                                                            </span>

                                                            @else

                                                            <span class="ml-4">
                                                                - remise : {{ $line['breakPrice']  }} fcfa
                                                            </span>

                                                            @endif

                                                        </div>

                                                        <div class="flex items-center mt-2">
                                                            <button
                                                                x-on:click="console.log(line)"
                                                            class="p-2 mr-1 text-gray-600 transition-colors rounded-lg hover:bg-gray-100 hover:text-gray-700"
                                                                    type="button"
                                                                    @if(is_null($line['handle']))
                                                                        wire:click="minus('{{$line['slug'].'.'.$line['option']}}')"
                                                                    @else
                                                                        wire:click="minus('{{$line['handle'].'.'.$line['slug'].'.'.$line['option']}}')"
                                                                    @endif
                                                                    >

                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    fill="none"
                                                                    viewBox="0 0 24 24"
                                                                    stroke-width="1.5"
                                                                    stroke="currentColor"
                                                                    class="w-5 h-5"
                                                                    >
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>

                                                            </button>

                                                            <input disabled value="{{$line['quantity']}}" class="w-6 p-2 text-xs transition-colors border border-gray-100 rounded-lg hover:border-gray-200"

                                                                {{-- wire:model="lines.{{ $index }}.quantity" --}} />
                                                            <button class="p-2 ml-1 text-gray-600 transition-colors rounded-lg hover:bg-gray-100 hover:text-gray-700"
                                                                type="button"
                                                                @if(is_null($line['handle']))
                                                                        wire:click="plus('{{$line['slug'].'.'.$line['option']}}')"
                                                                @else
                                                                        wire:click="plus('{{$line['handle'].'.'.$line['slug'].'.'.$line['option']}}')"
                                                                @endif
                                                                >
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>

                                                            </button>

                                                            <button class="p-2 ml-28 text-gray-600 transition-colors rounded-lg hover:bg-red-100 hover:text-red-700"
                                                                    type="button"
                                                                    @if(is_null($line['handle']))
                                                                        wire:click="removeOne('{{$line['slug'].'.'.$line['option']}}')"
                                                                    @else
                                                                        wire:click="removeOne('{{$line['handle'].'.'.$line['slug'].'.'.$line['option']}}')"
                                                                    @endif
                                                                    >
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
                                    {{ number_format($somme, 0, ',', ' . ') }} f cfa
                                </dd>
                            </dl>
                            <div class="flex flex-col items-center" >
                                <progress  class="w-full"  value="{{$somme}}" max="75000"></progress>
                                <span class="text-sm font-medium text-center text-gray-500" >livraison offerte a partir de 75 . 000 f cfa</span>
                        </div>


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
                            href="{{ route('checkout') }}">
                                commander
                            </a>

                            <button class="inline-block text-sm font-medium text-gray-600 underline hover:text-gray-500" x-on:click="linesVisible = false;" >

                                Continuer mes achats
                            </button>

                        </div>
                    @endif
            </div>
        </div>

</div>

