<?php

namespace App\Livewire\Guest\Collection;

use App\Filament\Resources\CollectionResource\Pages\collection as PagesCollection;
use App\Models\Collection;
use App\Models\CollectionGroup;
use App\Models\Discount;
use App\Models\Kit;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class CollectionPage extends Component
{
    public $collection;

    public function mount(Request $request, $slug)
    {
        if(array_key_exists("g",$request->query()))
        {
            $this->collection=match($slug){
                "free"=>[],
                "kits"=> [0,"Les kits",Kit::latest()->with(['featuredImage','discounts'])->where('status','Publie')->get()->toArray()],
                default =>CollectionGroup::where('slug',$slug)->with([/* "kits"=>function($query){$query->where('status','Publie');},"kits.featuredImage", */"collections"=>function($query){$query->where('active',true)->where('parent_id',null);},"collections.featuredImage","collections.products"=>function($query){$query->where("status","publie");},"collections.products.images","discounts","collections.discounts","collections.products.discounts"])->first()
            };
        }else{
            $this->collection=match($slug){
                "new"=>[1,"Nouveautés",Product::latest()->with(['images','discounts'])->where("status","Publie")->get()->toArray()],
                default => Collection::where('slug',$slug)->with(['discounts',"featuredImage","products"=>function($query){$query->where("status","publie");},"products.images","products.discounts",'group'])->where('active',true)->first(),
            };
        }
        if(!is_array($this->collection))
        {
            $this->collection->visit()->withSession();
            $this->collection=$this->collection->toArray();
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

/*     public function getBreadcrumbs($id)
    {
        $tabAssoc = [];
        $tabAssoc[]= ["label"=>$this->product['collection']['group']['name'],"slug"=>$this->product['collection']['group']['slug']];
        $collectParent=Collection::find($id);
        $parents=array_reverse($this->getAllParents($collectParent));
        $parents[]=$collectParent;
        return array_merge($tabAssoc,array_map(fn($parent)=>["label"=>$parent->name,"slug"=>$parent->slug],$parents));
    }
 */
    public function getBreadCrumb()
    {
        $tabAssoc = [];
        $parents=null;

        if($this->collection!==null&&array_key_exists('parent_id',$this->collection)&&$this->collection['parent_id']!==null)
        {

            $parents=$this->getAllParents(Collection::find($this->collection['id']));
        }
        if($parents!==null)
        {
            $tabAssoc = array_map(fn($parent)=>["label"=>$parent->name,"slug"=>$parent->slug],$parents);
        }
        if(array_key_exists('group',$this->collection)){
            $col[]= ["label"=>$this->collection['group']['name'],"slug"=>$this->collection['group']['slug']];
            $tabAssoc=array_merge($tabAssoc,$col);
        }
        return array_reverse($tabAssoc);
    }

    public function getName()
    {
        $name="sneakers";
        if($this->collection)
        {
            //si c'est un array avec id
           if(array_key_exists('id',$this->collection))
           {
                return$this->collection['name'];
           }else
           {//si c'est un array sans id
                return$this->collection[1];
            }
        }
        return$name;
    }

    public function getCollections()
    {//on veut generer ici la liste des collections enfants
        $collections=null;
        $coupon=null;
        //si collection n'est pas nulle
        if($this->collection)
        {
            //si il a un id(donc c'est soit un group ou soit une collection group)
            if(array_key_exists('id',$this->collection))
            {
                //si c'est un
                if(array_key_exists('collection_group_id',$this->collection))
                {
                    $collections=Collection::where('active',true)->where('parent_id',$this->collection['id'])->with('featuredImage','discounts')->get()->all();
                }
                else
                {
                    $collections=Collection::where('active',true)->where('parent_id',null)->where('collection_group_id',$this->collection['id'])->with('featuredImage')->get()->all();
                }
            }else{
                return[];
            }

        }else{
            $collections=Collection::where('active',true)->where('parent_id',null)->with('featuredImage')->whereHas('group',fn($query)=>$query->whereHas('productOption',fn($query)=>$query->where('name','Pointure')))->get()->all();
        }
        $toSend=[];
        foreach ($collections as $collection) {
            $coupon=null;
            if([]!==$collection->discounts()->get()->all())
            {
                foreach ($collection->discounts()->get()->all() as $value) {
                    if($coupon===null||$value['priority']>$coupon['priotrity']||$value['priority']>$coupon['priotrity']&&(($value['data']['type']==='percentage'&&$value['data']['percentage']>$coupon['data']['percentage'])||($value['data']['type']==='fixed_values'&&$value['data']['fixed_values']>$coupon['data']['fixed_values'])))
                    {
                        $coupon=$value;
                    }
                }
            }
            $toSend[]=['name'=>$collection->name,'slug'=>$collection->slug,'url'=>$collection->featuredImage()->get()->first()?->toArray()['large_url']??"",'alt'=>$collection->featuredImage()->get()->first()?->toArray()['alt']??"",'remise'=>$coupon===null?null:($coupon->data['type']==='percentage'?$coupon->data['percentage']:$coupon->data['fixed_values']),'type'=> $coupon===null?null:$coupon->data['type']];
        }
        return $toSend;
    }

    public function getProducts()
    {
        $products=null;
        //si collection n'est pas nulle
        if($this->collection)
        {
            //si il a un id(donc c'est soit un group ou soit une collection group)
            if(array_key_exists('id',$this->collection))
            {
                //si c'est un
                if(array_key_exists('collection_group_id',$this->collection))
                {
                    $products=$this->collection["products"];
                }
                else
                {
                    $products=[];
                    foreach($this->collection['collections'] as $collect)
                    {
                        foreach ($collect["products"] as  $product) {
                            $products[]=$product;
                        }
                    }
                }
            }else{
                $products=$this->collection[2];
            }

        }else{
            $products=Product::with(['images','discounts'])->where('status','Publie')->where('product_option_id',1)->get()->toArray();
        }
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
            $image=$collection['images'][0]??$collection['featured_image'];
            $list[]=[
                "slug"=>array_key_exists('images',$collection)?$collection['slug']:$collection['slug'].'?k',
                "name"=>$collection['name'],
                "price"=>$collection['old_price']??$collection['price'],
                'alt'=>$image['alt']??"",
                'url'=> $image['large_url']??"",
                'remise'=>$coupon===null?null:($coupon['data']['type']==='percentage'?$coupon['data']['percentage']:$coupon['data']['fixed_values']),
                'type'=> $coupon===null?null:$coupon['data']['type']
                ];
        }
        return$list;

    }

    public function arrayPaginate(array $array)
    {
        $collection = collect($array);

        // Paginez la collection
        $perPage = 40; // Changez 10 par le nombre d'éléments par page souhaité
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPageItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedResults = new LengthAwarePaginator($currentPageItems, $collection->count(), $perPage, $currentPage);
        return$paginatedResults;
        // Stockez les résultats paginés dans la variable $results
    }

    public function getDiscount()
    {
        $coupon=null;
        if(array_key_exists('discounts',$this->collection))
        {
            if([]!==$this->collection['discounts'])
            {
                foreach ($this->collection['discounts'] as  $value) {
                    if($coupon===null||$value['priority']>$coupon['priotrity']||$value['priority']>$coupon['priotrity']&&(($value['data']['type']==='percentage'&&$value['data']['percentage']>$coupon['data']['percentage'])||($value['data']['type']==='fixed_values'&&$value['data']['fixed_values']>$coupon['data']['fixed_values'])))
                    {
                        $coupon=$value;
                    }
                }
            }

        }elseif(array_key_exists(1,$this->collection)&&$this->collection[1]==="Les kits"){

        }
        return$coupon;
    }

    #[Title('Collection')]
    public function render()
    {
        return view('livewire.guest.collection.collection-page')->with([
                'breadcrumbs'=>$this->getBreadCrumb(),
                'name'=>$this->getName(),
                'collections'=>$this->getCollections(),
                'discount'=>$this->getDiscount(),
                'products'=>$this->arrayPaginate($this->getProducts()),
        ]);
    }
}
