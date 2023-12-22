<?php

use App\Models\Collection;
use App\Models\CollectionGroup;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Livewire\Volt\Component;
use Livewire\Attributes\On;
use function Livewire\Volt\{mount,state,On};

new class extends Component
{

    public function with(): array
    {
        return [
            'menus' => $this->getMenuGroup(),
            'collection'=> CollectionGroup::query()->first()
        ];
    }

    #[On('orderSended')]
    public function DispatchNotification()
    {
        Notification::make()
            ->title('Commande recu')
            ->body('Nous vous avons envoyé un mail')
            ->success()
            ->send();
    }

    public function getMenuGroup()
    {
        return $this->formatCollection(CollectionGroup::query()->where('onNavBar',true)->with('discounts')->take(4)->get()->all());
    }

    public function formatCollection($array):array
    {
        $list=[];
        foreach ($array as $collection) {
            $coupon=null;
            if([]!==$collection->discounts()->get()->all())
            {
                foreach ($collection->discounts()->get()->all() as  $value) {
                    if($coupon===null||$value['priority']>$coupon['priotrity']||$value['priority']>$coupon['priotrity']&&(($value['data']['type']==='percentage'&&$value['data']['percentage']>$coupon['data']['percentage'])||($value['data']['type']==='fixed_values'&&$value['data']['fixed_values']>$coupon['data']['fixed_values'])))
                    {
                        $coupon=$value;
                    }
                }
            }
            $list[]=[
                "slug"=>$collection->slug,
                "name"=>$collection->name,
                'remise'=>$coupon===null?null:($coupon->data['type']==='percentage'?$coupon->data['percentage']:$coupon->data['fixed_values']),
                'type'=> $coupon===null?null:$coupon->data['type']
                ];
        }
        return$list;
    }

    #[On('logout')]
    public function logout(): void
    {
        auth()->guard('web')->logout();

        session()->invalidate();
        session()->regenerateToken();

        $this->redirect('/', navigate: true);
    }

}; ?>


<div class=" z-30" x-data="{visible:false}">
    <!--

      mobile navbar en gros
    -->

    <div class="fixed z-10 lg:hidden w-full h-16 max-w-lg -translate-x-1/2 bg-white border border-gray-200 rounded-full bottom-4 left-1/2 ">
        <div class="grid grid-cols-4 h-full max-w-lg justify-between items-center mx-auto">
            <a href="{{route('home')}}" class="inline-flex flex-col items-center justify-center px-5 rounded-l-full hover:bg-gray-50  ">

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                  </svg>

                <span class=" font-thin text-xs">accueil</span>
            </a>

            <button  {{-- href="{{route('collection',['slug'=>$collection->slug,'g'])}} " --}}
                x-on:click="visible=!visible"

                class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 ">

                <svg  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                </svg>

                <span class=" font-thin text-xs">collections</span>
            </button>

            <div class="inline-flex flex-col items-center justify-center px-2 hover:bg-gray-50  group">
                @livewire('cartSm')
                <span class=" font-thin text-xs ">panier</span>
            </div>

            <a href="{{route('search')}}"  class="inline-flex flex-col items-center justify-center px-5 rounded-r-full hover:bg-gray-50 group">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                  </svg>
                <span class="font-thin text-xs ">recherches</span>
            </a>

        </div>
    </div>

    <div x-show="visible"
        x-on:click.away="visible = false;"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 "
        x-transition:enter-end="opacity-100 "
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 "
        x-transition:leave-end="opacity-0 "
        class="fixed z-10 lg:hidden w-full h-8 max-w-lg -translate-x-1/2 bg-white border border-gray-200 rounded-full bottom-24  left-1/2 "
        >
        <div class="flex flex-row justify-around h-full max-w-lg items-center mx-auto">
            @foreach ($menus as $collection)
                        <a class="flex justify-between items-center text-sm font-medium text-gray-700 hover:text-gray-800"
                            href="{{ route('collection',["slug"=>$collection['slug'],"g"]) }}" >
                            @if ($collection['remise']!==null)
                                <div class=" flex items-center justify-center rounded-md bg-black w-auto h-8 m-3">
                                    <span class="text-white font-semibold py-4 mx-2   text-xs" > - {{number_format((int)$collection['remise'], 0, ',', ' . ')}} @if($collection['type']==='percentage')%@else f cfa @endif</span>
                                </div>
                            @endif
                            {{ $collection['name'] }}
                        </a>
                    @endforeach
        </div>
    </div>



    <!-- normal bar en large-->

    <header
        x-bind:class="top==false ?' bg-white  ':''"
        class="bg-gradient-to-b	 from-white to-transparent lg:bg-white"
        x-transition>


        <nav aria-label="Top" class=" mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="">
            <div class="flex h-16 justify-center lg:justify-between items-center">

              <!-- Logo -->

                    <a  href="{{ route('home') }}" >
                        <div class="flex items-center shrink-0">
                            <x-application-logo class="block w-auto text-gray-800 fill-current h-9 " />
                            <span class="self-center whitespace-nowrap  ml-3">DamsoStore</span>
                        </div>
                    </a>


              <!--  menus -->
              <div class="ml-8 hidden lg:ml-8 lg:block lg:self-stretch self-stretch">
                <div class="flex h-full space-x-8">
                    @foreach ($menus as $collection)
                        <a class="flex justify-between items-center text-sm font-medium text-gray-700 hover:text-gray-800"
                            href="{{ route('collection',["slug"=>$collection['slug'],"g"]) }}" >
                            @if ($collection['remise']!==null)
                                <div class=" flex items-center justify-center rounded-md bg-black w-auto h-8 m-3">
                                    <span class="text-white font-semibold py-4 mx-2   text-xs" > - {{number_format((int)$collection['remise'], 0, ',', ' . ')}} @if($collection['type']==='percentage')%@else f cfa @endif</span>
                                </div>
                            @endif
                            {{ $collection['name'] }}
                        </a>
                    @endforeach
                </div>
              </div>

              <div>

                <div class=" hidden lg:ml-8 ml-auto lg:flex items-center">
                    <div class="hidden lg:flex lg:flex-1 lg:items-center lg:justify-end lg:space-x-6">
                      <a href="{{route('search')}}" class="text-sm font-medium text-gray-700 hover:text-gray-800" >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                          </svg>
                    <span class="sr-only">Profile</span></a>
                    </div>

                    <!-- Search -->
                    {{-- <div class=" hidden lg:flex lg:ml-6">
                      @livewire('search')
                    </div> --}}

                    <!-- Cart -->
                    <div class=" hidden ml-4 lg:flow-root lg:ml-6">
                        @livewire('cart')
                    </div>
                  </div>
              </div>
            </div>
          </div>
        </nav>
    </header>



</div>


