<?php

use App\Filament\Resources\CollectionResource\Pages\collection;
use App\Models\Address;
use App\Models\Discount;
use App\Models\Kit;
use App\Models\Order;
use App\Models\Product;
use App\Notifications\OrderReception;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Notification;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use phpDocumentor\Reflection\Types\Nullable;

new #[Layout('layouts.app')]
class extends Component {

    public $cart=null;

    public $sommeFinal=null;

    public $livraison=null;

    public $sommeWithOutBreak=0;

    public $sommeWithBreak=0;

    public $break=0;

    public string $sessionId;

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

    public function mountCart($array):void
    {
        $accumulateur=[];
        foreach ($array as  $value) {
            foreach ($value as $object) {
                if(!array_key_exists('price',$object))
                {
                    foreach ($object as $remise) {
                        $accumulateur[]=$remise;
                    }
                }else{
                    $accumulateur[]=$object;
                }
            }
        }
        $this->cart=$accumulateur;
    }

    public function mount()
    {

        Session::forget('cart');
        if(Session::has('cart'))
        {
            $this->update();
            $this->mountSomme();
        }else {
            $this->dispatch('mountSession');
        }

        $this->sessionId= Session::getId();

    }

    #[On('checkOutUpdate')]
    public function update()
    {
        $product=Session::get('cart.lines');

        if(is_null($product)||[]===$product)
        {
            return redirect(route('home'));
        }
        $this->mountCart(Session::get('cart.lines'));
        $this->mountSomme();

    }

    public array $steps = [
        'address' => 1,
      /*'shipping_option' => 2,
        'billing_address' => 3, */
        'payment' => 2,
    ];

    public function mountSomme()
    {
        if($this->cart!==null)
        {
            $somme=0;
            $break=0;
            foreach ($this->cart as $size) {
                if (!is_null($size['handle']))
                {
                    $break+=$size['reduce']*$size['quantity'];
                }
                $somme+=$size['price']*$size['quantity'];
            }
            $this->sommeWithOutBreak=$somme;
            $this->break=$break;
            $this->sommeWithBreak=$this->sommeWithOutBreak-$this->break;

            if($this->sommeWithBreak>75000)
            {
                $this->livraison=0;
                $this->sommeFinal=$this->sommeWithBreak+$this->livraison;
            }

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
        $panier=$this->cart;
        //recuperer la liste de slug pour pouvoir recuperer les données
        $slugs=['collections'=>[],'groups'=>[]];
        foreach ($panier as $value) {
            if(array_key_exists('kit',$value))
            {
                if(!in_array($value['slug'],$slugs['groups']))
                {$slugs['groups'][]=$value['slug'];}
            }else{
                if(!in_array($value['slug'],$slugs['collections']))
                {$slugs['collections'][]=$value['slug'];}
            }
        }
        //liste des produits avec tous les attributs sauf id
        $productsFull=Product::whereIn('slug', $slugs['collections'])->with('variants')->get();
        //tableau associatifs des produits on associe un slug avec un id
        $productsLess=$productsFull->pluck('id','slug');
        //liste des kits avec tous les attributs sauf id
        $kitsFull=Kit::whereIn('slug', $slugs['groups'])->get();
        //tableau associatifs des produits on associe un slug avec un id
        $kitsLess=$kitsFull->pluck('id','slug');
        //on recupere les produits avec id
        $ProductsCpt=[];
        //on recupere les kits avec id
        $KitsCpt=[];
        foreach($panier as $value){
            if(array_key_exists('kit',$value))
            {
                $value['id']=$kitsLess[$value['slug']];
                $KitsCpt[]=$value;
            }else{
                $value['id']=$productsLess[$value['slug']];
                $ProductsCpt[]=$value;
            }
        }
        //pour faciliter la validation des prix en rapport avec leur variantes, on essai d'associer ici un slug avec une liste de pluck 'variante'=> 'prix
        $mapSlugPluck=[];
        foreach($productsFull->all() as $prod)
        {
            $mapSlugPluck[$prod->slug]=$prod->variants()->get()->pluck('min_price','name')->all();
        }
        for ($i=0; $i < count($ProductsCpt); $i++) {
            if($mapSlugPluck[$ProductsCpt[$i]['slug']]!==[])
            {
                $ProductsCpt[$i]['price']=$mapSlugPluck[$ProductsCpt[$i]['slug']][$ProductsCpt[$i]['option']];
            }else{
                foreach ($productsFull as $value) {
                    if($ProductsCpt[$i]['id']===$value->id)
                    {
                        $ProductsCpt[$i]['price']=$value->old_price;
                    }
                }
            }
        }

        for ($i=0; $i < count($KitsCpt); $i++) {
            foreach ($kitsFull as $value) {
                if($KitsCpt[$i]['id']===$value->id)
                {
                    $KitsCpt[$i]['price']=$value->price;

                }
            }
        }

        $productDiscountHandles=[];
        $kitDiscountHandles=[];
        foreach($ProductsCpt as $product)
        {
            if(!is_null($product['handle'])){
                $productDiscountHandles[$product['handle']]=$product['handle'];
            }
        }
        foreach($KitsCpt as $kit)
        {
            if(!is_null($kit['handle'])){
                $kitDiscountHandles[$kit['handle']]=$kit['handle'];
            }

        }
        $discounts=Discount::whereIn('handle',array_merge($productDiscountHandles,$kitDiscountHandles))->get();//dd()
        $matchDiscount = array_reduce($discounts->all(), function ($carry, $item) {
            $carry[$item->handle] = $item;
            return $carry;
        }, []);

        for ($i=0; $i < count($ProductsCpt); $i++){
            if(!is_null($ProductsCpt[$i]['handle']))
            {
                $data=$matchDiscount[$ProductsCpt[$i]['handle']]->data;
                $pu=$ProductsCpt[$i]['price'];
                $st=$pu*$ProductsCpt[$i]['quantity'];
                if($data['type']==="percentage")
                {
                    $du=$pu*$data['percentage']/100;
                }else{
                    $du=$pu-$data['fixed_values'];
                }
                $dt=$du*$ProductsCpt[$i]['quantity'];
                $t=$st-$dt;
                //remplissage du produit
                $ProductsCpt[$i]['subTotal']=$st;
                $ProductsCpt[$i]['discount_unit']=$du;
                $ProductsCpt[$i]['discount_total']=$dt;
                $ProductsCpt[$i]['total']=$t;
            }else{
                $ProductsCpt[$i]['subTotal']=$ProductsCpt[$i]['price']*$ProductsCpt[$i]['quantity'];
                $ProductsCpt[$i]['discount_unit']=0;
                $ProductsCpt[$i]['discount_total']=0;
                $ProductsCpt[$i]['total']=$ProductsCpt[$i]['subTotal'];
            }
        }

        for ($i=0; $i < count($KitsCpt); $i++){
            if(!is_null($KitsCpt[$i]['handle']))
            {
                $data=$matchDiscount[$KitsCpt[$i]['handle']]->data;
                $pu=$KitsCpt[$i]['price'];
                $st=$pu*$KitsCpt[$i]['quantity'];
                if($data['type']==="percentage")
                {
                    $du=$pu*$data['percentage']/100;
                }else{
                    $du=$pu-$data['fixed_values'];
                }
                $dt=$du*$KitsCpt[$i]['quantity'];
                $t=$st-$dt;
                //remplissage du produit
                $KitsCpt[$i]['subTotal']=$st;
                $KitsCpt[$i]['discount_unit']=$du;
                $KitsCpt[$i]['discount_total']=$dt;
                $KitsCpt[$i]['total']=$t;
            }else{
                $KitsCpt[$i]['subTotal']=$KitsCpt[$i]['price']*$KitsCpt[$i]['quantity'];
                $KitsCpt[$i]['discount_unit']=0;
                $KitsCpt[$i]['discount_total']=0;
                $KitsCpt[$i]['total']=$KitsCpt[$i]['subTotal'];
            }
        }
        //verification des
        return[$ProductsCpt, $KitsCpt];


    }

    public function checkout()
    {
        [$cleanProducts,$cleanKits]=$this->ensureAllIsOk();

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

        $sommeFinal=0;

        $livraison=null;

        $sommeWithOutBreak=0;

        $sommeWithBreak=0;

        $break=0;

        $somme=0;

        foreach ($cleanProducts as $size) {
            if (!is_null($size['handle']))
            {
                $break+=$size['reduce']*$size['quantity'];
            }
            $sommeWithOutBreak+=$size['price']*$size['quantity'];
        }
        foreach ($cleanKits as $size) {
            if (!is_null($size['handle']))
            {
                $break+=$size['reduce']*$size['quantity'];
            }
            $sommeWithOutBreak+=$size['price']*$size['quantity'];
        }
        $sommeWithBreak=$sommeWithOutBreak-$break;
        if($sommeWithBreak>75000)
        {
            $livraison=0;
            $sommeFinal=$sommeWithBreak+$livraison;
        }


        $this->order=Order::create([
            'address_id'=>$adress->id,
            'reference'=>uniqid('order_'),
            'sub_total'=>$sommeWithOutBreak,
            'discount_breakdown'=>$break,
            'total'=>$sommeFinal,
            'shipping_total'=>$livraison,
            'discount_total'=>$break,
            'attribute_data'=>json_encode([]),
            'date_commande'=> Date::now(),
            'date'=>Date::now(),
        ]);

        if (auth()->check())
        {
            $this->order->customer()->associate(auth()->user()->id);
        }

        $this->order->Address()->associate($adress);

        foreach($cleanProducts as $prod)
        {
            $optName=['name'=>$prod['options']['name'],'value'=>$prod['option']];
            $this->order->products()->attach($prod['id'],['option'=>json_encode($optName),'unit_price'=>$prod['price'],'quantity'=>$prod['quantity'],'total'=>$prod['total'],'discount_total'=>$prod['discount_total'],'sub_total'=>$prod['subTotal'],'meta'=>json_encode($prod), 'created_at'=> Date::now()]);
        }
        foreach($cleanKits as $prod)
        {
            $optName=['name'=>$prod['options']['name'],'value'=>$prod['option']];
            $this->order->kits()->attach($prod['id'],['option'=>json_encode($optName),'unit_price'=>$prod['price'],'quantity'=>$prod['quantity'],'total'=>$prod['total'],'discount_total'=>$prod['discount_total'],'sub_total'=>$prod['subTotal'],'meta'=>json_encode($prod), 'created_at'=> Date::now()]);
        }

        $this->order->save();

        //Notification::route('mail',$this->contact_email)->notify(new OrderReception());

        $this->dispatch('emptyCart');
        $this->dispatch('orderSended');
        Session::put('statuts', 'dispatch');

        return redirect(route('home'));

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

<div x-data="cart= @entangle('cart').live" x-init="fbq('track', 'InitiateCheckout', {
    content_name: 'Commande en Cours - {{$sessionId}} ',
    content_category: 'commande',
    content_ids: ['{{$sessionId}}'],
    content_type: 'commande',
    currency: 'FCFA',
});">
    @livewire('notifications')
    <div>
        <div class="max-w-screen-xl px-4 py-12 mt-5 mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3 lg:items-start">
                <div class="px-6 py-8 space-y-4 bg-white border border-gray-100 lg:sticky lg:top-8 rounded-xl lg:order-last shadow-lg">
                    <h3 class="font-medium text-xl">
                        Panier
                    </h3>

                    <div class="flow-root">
                        <div class="-my-4 divide-y divide-gray-100">
                            <ul class="-my-4 overflow-y-auto divide-y divide-gray-100 max-h-72">
                            @if ($cart)
                                @foreach ($cart as $product)
                                <li>
                                    <div class="flex py-4"  wire:key="line_{{ $product['slug'] }}">

                                        <div class="flex flex-row">
                                            <a href="{{route('product',['slug'=>$product['slug']])}}">
                                                <img class="object-cover w-16 h-16 rounded" src="{{ $product['url'] }}">
                                            </a>
                                        </div>

                                        <div class="flex-1 ml-4">
                                            <div class="flex flex-row justify-between items-center">
                                                <a href="{{route('product',['slug'=>$product['slug']])}}" class="max-w-[20ch] text-sm font-medium">
                                                    {{ $product['name'] }}

                                                </a>
                                                @if(!is_null($product['handle']))
                                                    <div class=" flex items-center justify-center rounded-md bg-black w-auto h-8 m-3">
                                                            <span class="text-white font-semibold py-4 mx-2   text-xs" > - {{number_format((int)$product['number'], 0, ',', ' . ')}} @if($product['type']==='percentage')%@else f cfa @endif</span>
                                                    </div>
                                                @endif
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
                                                @if (is_null($product['handle']))

                                                <span class="ml-4">
                                                    @ prix : {{ $product['price']  }} fcfa
                                                </span>

                                                @else

                                                <span class="ml-4">
                                                    - remise : {{ $product['breakPrice']  }} fcfa
                                                </span>

                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            @endif
                            </ul>
                        </div>
                    </div>

                    <div class="flow-root mt-1">
                        <dl class="-my-4 text-sm divide-y divide-gray-100 mt-2">

                            <div class="flex flex-wrap py-4 border-t border-black">
                                <dt class="w-1/2 font-medium">
                                    somme :
                                </dt>

                                <dd class="w-1/2 text-right">
                                    {{ number_format($sommeWithOutBreak, 0, ',', ' . ') }} fcfa
                                </dd>
                            </div>

                            <div class="flex flex-wrap py-4 border-t border-black">
                                <dt class="w-1/2 font-medium">
                                    remises total :
                                </dt>

                                <dd class="w-1/2 text-right">
                                    {{ number_format($break, 0, ',', ' . ') }} fcfa
                                </dd>
                            </div>

                            <div class="flex flex-wrap py-4 border-t border-black">
                                <dt class="w-1/2 font-medium">
                                    Livraison :
                                </dt>

                                <dd class="w-1/2 text-right">
                                    @if (!is_null($livraison))
                                        Gratuite
                                    @else
                                        en fonction du lieu <br> <span class=" text-xs leading-3"> taxé par le livreur </span>
                                    @endif
                                </dd>
                            </div>

                            <div class="flex flex-wrap py-4 border-t border-black">
                                <dt class="w-1/2 font-medium">
                                    Prix final :
                                </dt>

                                <dd class="w-1/2 text-right">
                                    {{ number_format($sommeWithBreak, 0, ',', ' . ') }} fcfa
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
