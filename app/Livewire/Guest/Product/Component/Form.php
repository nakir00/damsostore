<?php

namespace App\Livewire\Guest\Product\Component;

use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Form extends Component
{

    public $form,$objet;
    public $quantity=1;
    public $selectedSize;
    public $price;
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

    public function mount()
    {
        $this->price=$this->form['price'];
    }

    #[On('selected')]
    public function show($val)
    {
        $this->objet=$val;
        $this->price=$val['price'];
    }

    public function updateProducts($key)
    {
        $this->products[$key]="*";
    }

    #[on('added')]
    public function addToCart($added=null)
    {

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
                $toSend=["name" =>$this->form['name'],"slug" =>$this->form['slug'],"price" =>$this->form['price'],"url" =>$this->form['url'],'products'=>$this->form['products'],'options'=>['name'=>'kit'],'option'=>$mot,'quantity'=>$this->quantity,'kit'=>'k'];
                $this->dispatch('addedProduct',$toSend);
        }elseif(!empty($this->form['variants'])&&array_key_exists('values',$this->form['variants']))
        {
            $this->objet['options']=$this->form['variants'];
            $this->objet['quantity']=$this->quantity;
            $this->dispatch('addedProduct',$this->objet);
        }elseif($added!==null)
        {
            $added['quantity']=$this->quantity;
            $this->dispatch('addedProduct',$added);
        }
    }

    public function render()
    {
        return view('livewire.guest.product.component.form');
    }
}
