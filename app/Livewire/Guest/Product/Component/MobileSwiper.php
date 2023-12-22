<?php

namespace App\Livewire\Guest\Product\Component;

use App\Models\Discount;
use App\Models\Kit;
use App\Models\Product;
use Filament\Notifications\Notification;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;

class MobileSwiper extends Component
{

    public $form,$objet;
    public $quantity=1;
    public $selectedSize;
    public $price;
    public ?Discount $discount=null;
    public int $breakPrice=0;
    public int $reduce=0;
    #[Rule('required|regex:/^[a-zA-Z0-9_.-]*$/')]
    public string $coupon="";
    public $images;

    #[Rule('required|notIn:*')]
    public $products=[];

    protected $rules = [
        'products.*' => 'required',
        'quantity'=> 'required',
    ];

    public function updated($property)
    {
        $this->validateOnly($property);
        //if($this->errors()) {dump($this->errors());};
    }

    public function updateDiscount()
    {
        $this->validate( [
            'coupon'=>['required','regex:/^[a-zA-Z0-9_.-]*$/']
        ],
        [
            'coupon.required'=>'Veuillez saisir un coupon',
            'coupon.regex'=>'Le format du coupon saisi est invalide',
        ]);
        $toSearch=strtolower($this->coupon);

        if([]!==$this->form['variants'])
        {
            $coupons=Product::with('coupons')->where('slug',$this->form['slug'])->get()->first()->coupons()->get();

        }else{
            $coupons=Kit::with('coupons')->where('slug',$this->form['slug'])->get()->first()->coupons()->get();
        }
        $coupons->each(
            function($discount) use ($toSearch)
            {
                if($discount->coupon===$toSearch)
                {
                    $this->discount=$discount;
                    $discount->uses++;
                    $discount->save();
                    Notification::make()
                        ->title('Coupon appliqué avec succés')
                        ->success()
                        ->body('les prix sont maintenant réduits')
                        ->send();
                }
            });
    }

    public function mount()
    {

        if($this->form['discount']!==null)
        {
            $this->discount=Discount::find($this->form['discount']['id']);
        }
        $this->price=$this->form['price'];

    }

    #[On('selected')]
    public function show($val)
    {
        $this->objet=$val;
        $this->price=$val['price'];
    }

    public function plus()
    {
        $this->quantity++;
    }

    public function moins()
    {
        $this->quantity--;
    }

    public function updateProducts($key)
    {
        $this->products[$key]="*";
    }

    #[on('added')]
    public function addToCart($added=null)
    {
        if($this->discount!==null)
        {
            if($this->discount->data['type']==='percentage')
            {
                $reduces=($this->price*$this->discount->data['percentage'])/100;
            }
            else {
                $reduces=$this->discount->data['fixed_values'];
            }
            $this->reduce=$reduces;
            $this->breakPrice=$this->price-$this->reduce;
        }

        if(!empty($this->form['products']))
        {
            $this->validate(
                [
                    'products'=>['required'],
                    'products.*'=>['required','not_in:*,']
                ],
                [
                    'products.required'=>'veuillez choisir avant de commander',
                    'products.*.required'=>'veuillez choisir avant de commander',
                    'products.*.not_in'=>'veuillez choisir avant de commander'
                ]
            );
                $this->dispatch('close-modal', id: 'add-panier');
                $prod=$this->products;
                for($i=0;$i<count($this->form['products']);$i++) {
                    if(!empty($this->form['products'][$i]['options']))
                    {
                        $this->form['products'][$i]['choice']=reset($this->products);
                        unset($prod[$i]);
                    }
                }
                $mot="tailles";
                foreach($this->products as $terme)
                {
                    $mot=$mot.'-'.$terme;
                }
                $this->form['quantity']=$this->quantity;
                $toSend=["name" =>$this->form['name'],"slug" =>$this->form['slug'],"price" =>$this->form['price'],"url" =>$this->form['url'],'products'=>$this->form['products'],'options'=>['name'=>'kit'],'option'=>$mot,'quantity'=>$this->quantity,'breakPrice'=>$this->breakPrice,'reduce'=>$this->reduce,'type'=>$this->discount?->data['type']??null,'number'=>$this->discount?->data['type']==='percentage'?($this->discount?->data['percentage']??$this->discount?->data['fixed_values']):null,'handle'=>$this->discount?->handle??null,'kit'=>'k'];
                $this->dispatch('addedProduct',$toSend);
        }elseif(!empty($this->form['variants'])&&array_key_exists('values',$this->form['variants']))
        {
            $this->dispatch('close-modal', id: 'add-panier');
            $this->objet['options']=$this->form['variants'];
            $this->objet['quantity']=$this->quantity;
            $this->objet['breakPrice']=$this->breakPrice;
            $this->objet['reduce']=$this->reduce;
            $this->objet['type']=$this->discount?->data['type']??null;
            $this->objet['number']=$this->discount?->data['type']==='percentage'?($this->discount?->data['percentage']??$this->discount?->data['fixed_values']):null;
            $this->objet['handle']=$this->discount?->handle??null;
            $this->dispatch('addedProduct',$this->objet);
        }elseif($added!==null)
        {
            $this->dispatch('close-modal', id: 'add-panier');
            $added['quantity']=$this->quantity;
            $added['breakPrice']=$this->breakPrice;
            $added['reduce']=$this->reduce;
            $added['type']=$this->discount?->data['type']??null;
            $added['number']=$this->discount?->data['type']==='percentage'?($this->discount?->data['percentage']??$this->discount?->data['fixed_values']):null;
            $added['handle']=$this->discount?->handle??null;
            $this->dispatch('addedProduct',$added);
        }

    }

    public function render()
    {
        return view('livewire.guest.product.component.mobile-swiper');
    }
}
