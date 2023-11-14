<?php

namespace App\Livewire\Guest\Product;

use App\Models\Collection;
use App\Models\kit;
use App\Models\Product;
use Illuminate\Http\Request;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ProductPage extends Component
{

    public $product;

    public function mount(Request $request,$slug)
    {
        if(array_key_exists("k",$request->query()))
        {
            $this->product=kit::Where("status","Publie")->where('slug',$slug)->with(['featuredImage','products','products.images','products','products.productOption','products.variants'=>function($query){$query->where('disponibility',true);},'collectionGroup'])->get()->first()->toArray();
        }
        else
        {
            $this->product=Product::Where("status","Publie")->where('slug',$slug)->with(['images','variants','productType','collection','collection.group','associations','associations.images','kits','kits.featuredImage','productOption','productOption.values'])->get()->first()->toArray();
        }
    }

    public function getImages()
    {
        if(array_key_exists('collection_group_id',$this->product))
        {
            $images[]=config('app.url').$this->product['featured_image']['large_url'];
            foreach($this->product['products'] as $prod)
            {
                $images[]=config('app.url').$prod['images'][0]['large_url'];
            }
            return$images;

        }else{
        return array_map(fn($images)=>config('app.url').$images['large_url'],$this->product['images']);
        }
    }

    public function getAllParents($object, $allParents = [])
    {
        if ($object->parent_id!=null) {
            $parent=$object->parent()->get()->first();
            $allParents[] = $parent;
            return $this->getAllParents($parent, $allParents);
        }
        return $allParents;
    }

    public function getVariants($iden)
    {
        if(!empty($iden['product_option']['values']))
        {
            $toReturn=[];
            $toReturn['name']=$iden['product_option']['name'];
            $toReturn['values']=[];
            if(count($iden['product_option']['values'])===count($iden['variants']))
            {
                for($i=0;$i<count($iden['variants']);$i++)
                {
                    $toReturn['values'][]=[
                        'name'=>$iden['product_option']['values'][$i]['name'],
                        'active'=>$iden['product_option']['values'][$i]['active']&&$iden['variants'][$i]['disponibility'],
                        'object'=>[
                            'name'=>$iden['name'],
                            'slug'=>$iden['slug'],
                            'old_price'=>$iden['old_price'],
                            'option'=>$iden['variants'][$i]['name'],
                            'price'=>$iden['variants'][$i]['min_price'],
                            'url'=>config('app.url').$iden['variants'][$i]['attribute_data']['product']['url'],
                            'alt'=>$iden['variants'][$i]['attribute_data']['product']['alt'],
                        ],
                    ];
                }
            }
            else
            {
                foreach ($iden['product_option']['values'] as $value) {
                    foreach($iden['variants'] as $variant)
                    {
                        if($value['name']===$variant['name'])
                        {
                            $toReturn['values'][]=[
                                'name'=>$value['name'],
                                'active'=>$value['active']===true&&$variant['disponibility']===true,
                                'object'=>[
                                    'name'=>$iden['name'],
                                    'slug'=>$iden['slug'],
                                    'old_price'=>$iden['old_price'],
                                    'option'=>$variant['name'],
                                    'price'=>$variant['min_price'],
                                    'url'=>config('app.url').$variant['attribute_data']['product']['url'],
                                    'alt'=>$variant['attribute_data']['product']['alt'],
                                ],
                            ];
                        }
                    }
                    if(empty($toReturn['values'])||end(array_slice($toReturn['values'], -1))['name']!==$value['name'])
                    {
                        $toReturn['values'][]=[
                            'name'=>$value['name'],
                            'active'=>false,
                            'object'=>[],
                        ];
                    }
                }
            }
            return$toReturn;
        }
        return ['name'=>$iden['name'],'slug'=>$iden['slug'],'old_price'=>$iden['old_price'],'price'=>$iden['old_price'],'option'=>"sans option",'url'=>config('app.url').$iden['images'][0]['large_url'],'alt'=>$iden['images'][0]['alt']??""];
    }

    public function getBreadcrumbs($id)
    {
        $tabAssoc = [];
        $tabAssoc[]= ["label"=>$this->product['collection']['group']['name'],"slug"=>$this->product['collection']['group']['slug']];
        $collectParent=Collection::find($id);
        $parents=array_reverse($this->getAllParents($collectParent));
        $parents[]=$collectParent;
        return array_merge($tabAssoc,array_map(fn($parent)=>["label"=>$parent->name,"slug"=>$parent->slug],$parents));
    }

    public function getProducts($iden)
    {
        $products=[];
        foreach ($iden as $product) {
            $prod=[
                'name'=>$product['name'],
                'slug'=>$product['slug'],
                'old_price'=>$product['old_price'],
                'url'=>config('app.url').$product['images'][0]['large_url'],
                'options'=>!empty($product['variants'])?[
                    'name'=>$product['product_option']['name'],
                    'values'=>array_map(fn($variant)=>['name'=>$variant['name'],'price'=>$variant['min_price']],$product['variants'])
                    ]:[],
            ];
            $products[]=$prod;
        }
        return $products;
    }

    public function getForm()
    {
        $datas=[];
        if(array_key_exists('collection_group_id',$this->product))
        {
            $datas=[
                'price'=>$this->product['price'],
                'breadcrumb'=>["kit"=>['label'=>$this->product['collection_group']['name'],'slug'=>$this->product['collection_group']['slug']]],
                'variants'=>[],
                'products'=>$this->getProducts($this->product['products']),
            ];
        }else{
            $datas=[
                'price'=>$this->product['old_price'],
                'breadcrumb'=>$this->getBreadcrumbs($this->product['collection']['id']),
                'variants'=>$this->getVariants($this->product),
                'products'=>[],
            ];
        }
        $datas['name']=$this->product['name'];
        $datas['slug']=$this->product['slug'];
        return $datas;
    }

    #[Title("produit")]
    public function render()
    {
        return view('livewire.guest.product.product-page')->with([
            'images'=>$this->getImages(),
            'form'=>$this->getForm(),

        ]);
    }
}
