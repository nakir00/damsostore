<?php

use App\Filament\Resources\CollectionResource\Pages\collection;
use App\Models\Address;
use App\Models\kit;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Date;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use phpDocumentor\Reflection\Types\Nullable;

new #[Layout('layouts.app')]
class extends Component {

    public $cart=null;

    public $somme=0;
    public Order $order;

    public ?Address $adress=null;

    public int $currentStep = 1;

    #[Rule('required')]
    public $first_name;//

    #[Rule('required')]
    public $last_name;//

    #[Rule('required|email')]
    public $contact_email;//

    #[Rule('required|regex:/^\+?\d+(?:[.\s-]?[68]?[.\s-]?\d+)*\s*$/')]
    public $contact_phone;//

    #[Rule('required')]
    public $country="Sénégal";//

    #[Rule('required')]
    public $region="Dakar";//

    #[Rule('required')]
    public $departement;//

    #[Rule('required')]
    public $commune;//

    #[Rule('required')]
    public $line_one;//

    #[Rule('Nullable')]
    public $line_two;//

    #[Rule('Nullable')]
    public $line_three;//

    #[Rule('Nullable')]
    public $delivery_instructions;



    /**
     * The payment type we want to use.
     *
     * @var string
     */
    public $paymentType = 'cash-in-hand';

    public function mount()
    {
        Session::forget('cart');
        if(Session::has('cart'))
        {
            $this->cart=Session::get('cart');
            $this->mountSomme();
        }else {
            $this->dispatch('mountSession');
        }

    }

    #[On('checkOutUpdate')]
    public function update()
    {
        $this->cart=Session::get('cart');
        $this->mountSomme();
    }

    public array $steps = [
        'address' => 1,
/*         'shipping_option' => 2,
        'billing_address' => 3, */
        'payment' => 2,
    ];

    public function mountSomme()
    {
        if($this->cart!==null)
        {
            $somme=0;
            foreach ($this->cart['lines'] as $size) {
                foreach ($size as $product)
                {
                    $somme+=$product['price']*$product['quantity'];
                }
            }
            $this->somme=$somme;
        }
    }

    public function saveAddress()
    {
        $this->validate();

        $this->adress=new Address();

        $this->determineCheckoutStep();

    }

    public function ensureAllIsOk()
    {
        $panier=Session::get('cart.lines');
        $slugs=['collections'=>[],'groups'=>[]];
        foreach($panier as $size){foreach ($size as $value) {
            if(array_key_exists('kit',$value))
            {
                if(!in_array($value['slug'],$slugs['groups']))
                {$slugs['groups'][]=$value['slug'];}
            }else{
                if(!in_array($value['slug'],$slugs['collections']))
                {$slugs['collections'][]=$value['slug'];}
            }
        }}
        $productsFull=Product::whereIn('slug', $slugs['collections'])->with('variants')->get();
        $productsLess=$productsFull->pluck('id','slug');
        $kitsFull=kit::whereIn('slug', $slugs['groups'])->get();
        $kitsLess=$kitsFull->pluck('id','slug');
        $ProductsCpt=[];
        $KitsCpt=[];
        foreach($panier as $size){foreach ($size as $key => $value) {
            if(array_key_exists('kit',$value))
            {
                $value['id']=$kitsLess[$value['slug']];
                $KitsCpt[]=$value;
            }else{
                $value['id']=$productsLess[$value['slug']];
                $ProductsCpt[]=$value;
            }
        }}
        //dd($ProductsCpt,$productsFull,$KitsCpt);
        $mapSlugPluck=[];
        foreach($productsFull->all() as $prod)
        {
            $mapSlugPluck[$prod->slug]=$prod->variants()->get()->pluck('min_price','name')->all();
        }

        foreach($ProductsCpt as $prod)
        {
            if($mapSlugPluck[$prod['slug']]!==[])
            {
                $prod['price']=$mapSlugPluck[$prod['slug']][$prod['option']];
            }else{
                foreach ($productsFull as $value) {
                    if($prod['id']===$value->id)
                    {
                       $prod['price']=$value->old_price;
                    }
                }
            }
        }

        foreach($KitsCpt as $kit)
        {
            foreach ($kitsFull as $value) {
                if($kit['id']===$value->id)
                {
                    $kit['price']=$value->price;
                }
            }
        }


    }

    public function checkout()
    {
        $CleanCart=$this->ensureAllIsOk();

        $adress=Address::create([
            'first_name'=>$this->first_name,
            'last_name'=>$this->last_name,
            'pays'=>$this->country,
            'region'=>$this->region,
            'departement'=>$this->departement,
            'commune'=>$this->commune,
            'line_one'=>$this->line_one,
            'line_two'=>$this->line_two,
            'line_three'=>$this->line_three,
            'contact_email'=>$this->contact_email,
            'contact_phone'=>$this->contact_phone
        ]);

        $adress->save();

        $this->order=Order::create([
            'address_id'=>$adress->id,
            'total'=>$this->somme,
            'date_commande'=> Date::now(),
        ]);

        if (auth()->check())
        {
            $this->order->customer()->associate(auth()->user()->id);
        }

        $this->order->Address()->associate($adress);
        $this->order->save();

    }

    public function determineCheckoutStep()
    {
        if($this->adress!==null)
        {
            $this->currentStep=2;
        }
        else
        {
            $this->currentStep=1;
        }
    }

}; ?>

<div>
    <div>
        <div class="max-w-screen-xl px-4 py-12 mt-5 mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3 lg:items-start">
                <div class="px-6 py-8 space-y-4 bg-white border border-gray-100 lg:sticky lg:top-8 rounded-xl lg:order-last shadow-lg">
                    <h3 class="font-medium text-xl">
                        Panier
                    </h3>

                    <div class="flow-root">
                        <div class="-my-4 divide-y divide-gray-100">
                            <ul class="-my-4 overflow-y-auto divide-y divide-gray-100 max-h-96">
                            @if ($cart)
                                @foreach ($cart['lines'] as $slug => $sizes)
                                    @foreach ($sizes as $size=>$product)
                                        <li>
                                            <div class="flex py-4"  wire:key="line_{{ $product['slug'] }}">

                                                <div class="flex flex-row">
                                                    <a href="{{route('product',['slug'=>$product['slug']])}}">
                                                        <img class="object-cover w-16 h-16 rounded" src="{{ $product['url'] }}">
                                                    </a>
                                                </div>

                                                <div class="flex-1 ml-4">
                                                    <div>
                                                        <a href="{{route('product',['slug'=>$product['slug']])}}" class="max-w-[20ch] text-sm font-medium">
                                                            {{ $product['name'] }}
                                                        </a>
                                                    </div>

                                                    <div>
                                                        <span class="text-xs text-gray-500">
                                                            quantite : {{$product['quantity']}}
                                                        </span>
                                                    </div>

                                                    <div class="flex justify-between mt-1 text-xs text-gray-500">
                                                        <span class="truncate">
                                                            {{ $product['options']['name'] }} : {{ $product['option'] }}
                                                        </span>
                                                        <span class="ml-4">
                                                            @ prix : {{ $product['price']  }} fcfa
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                @endforeach
                            @endif
                            </ul>
                        </div>
                    </div>

                    <div class="flow-root mt-6">
                        <dl class="-my-4 text-sm divide-y divide-gray-100 mt-5">

                            <div class="flex flex-wrap py-4 border-t border-black">
                                <dt class="w-1/2 font-medium">
                                    Total :
                                </dt>

                                <dd class="w-1/2 text-right">
                                    {{ number_format($somme, 0, ',', ' . ') }} fcfa
                                </dd>
                            </div>

                        </dl>
                    </div>
                </div>

                <div class="space-y-6 lg:col-span-2">
                    @include('livewire.guest.cart.components.address', [
                        'step' => $steps['address'],
                    ])

                   {{-- @include('partials.checkout.shipping_option', [
                        'step' => $steps['shipping_option'],
                    ])

                    @include('partials.checkout.address', [
                        'type' => 'billing',
                        'step' => $steps['billing_address'],
                    ])--}}

                    @include('livewire.guest.cart.components.payement', [
                        'step' => $steps['payment'],
                    ])
                </div>
            </div>
        </div>
    </div>

</div>
