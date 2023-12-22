<?php

namespace App\Livewire\Guest\Search;

use App\Models\Kit;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
class Search extends Component
{
    public ?string $search='';

    public function getProducts()
    {
        $products=Product::where('name', 'like', '%'.$this->search.'%')->where('status','Publie')->with(['images','discounts'])->take(15)->get()->toArray();
        $list=[];
        foreach ($products as $collection) {
            $coupon=null;
            if([]!==$collection['discounts'])
            {
                foreach ($collection['discounts'] as  $value) {
                    if($coupon===null||$value['priority']>$coupon['priotrity']||$value['priority']>$coupon['priotrity']&&(($value['data']['type']==='percentage'&&$value['data']['percentage']>$coupon['data']['percentage'])||($value['data']['type']==='fixed_values'&&$value['data']['fixed_values']>$coupon['data']['fixed_values'])))
                    {
                        $coupon=$value;
                    }
                }
            }
            $list[]=[
                "slug"=>$collection['slug'],
                "name"=>$collection['name'],
                "price"=>$collection['old_price']??$collection['price'],
                'alt'=>$collection['images'][0]['alt']??"",
                'url'=> config('app.url').$collection['images'][0]['large_url']??"",
                'remise'=>$coupon===null?null:($coupon['data']['type']==='percentage'?$coupon['data']['percentage']:$coupon['data']['fixed_values']),
                'type'=> $coupon===null?null:$coupon['data']['type']
                ];
        }
        return$list;
    }

    public function getKits()
    {
        $kits=Kit::where('name', 'like', '%'.$this->search.'%')->where('status','Publie')->with(['featuredImage','discounts'])->take(15)->get()->toArray();
        $list=[];
        foreach ($kits as $collection) {
            $coupon=null;
            if([]!==$collection['discounts'])
            {
                foreach ($collection['discounts'] as  $value) {
                    if($coupon===null||$value['priority']>$coupon['priotrity']||$value['priority']>$coupon['priotrity']&&(($value['data']['type']==='percentage'&&$value['data']['percentage']>$coupon['data']['percentage'])||($value['data']['type']==='fixed_values'&&$value['data']['fixed_values']>$coupon['data']['fixed_values'])))
                    {
                        $coupon=$value;
                    }
                }
            }
            $list[]=[
                "slug"=>array_key_exists('images',$collection)?$collection['slug']:$collection['slug'].'?k',
                "name"=>$collection['name'],
                "price"=>$collection['old_price']??$collection['price'],
                'alt'=>$collection['featured_image']['alt']??"",
                'url'=>$collection['featured_image']['large_url']??"",
                'remise'=>$coupon===null?null:($coupon['data']['type']==='percentage'?$coupon['data']['percentage']:$coupon['data']['fixed_values']),
                'type'=> $coupon===null?null:$coupon['data']['type']
                ];
        }
        return$list;
    }

    #[Title('Recherche')]
    public function render()
    {
        return view('livewire.guest.search.search', [
            'products' =>array_merge( $this->getProducts(),$this->getKits())
        ]);
    }
}
