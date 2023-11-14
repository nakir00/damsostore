<?php

namespace App\Livewire\Guest\Collection;

use App\Filament\Resources\CollectionResource\Pages\collection as PagesCollection;
use App\Models\Collection;
use App\Models\CollectionGroup;
use App\Models\Discount;
use App\Models\kit;
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
                "free"=>null,
                "kits"=> [0,"Les kits",kit::with(['featuredImage'])->where('status','Publie')->get()->toArray()],
                default =>CollectionGroup::where('slug',$slug)->with([/* "kits"=>function($query){$query->where('status','Publie');},"kits.featuredImage", */"collections"=>function($query){$query->where('active',true)->where('parent_id',null);},"collections.featuredImage","collections.products"=>function($query){$query->where("status","publie");},"collections.products.images"])->first()?->toArray()
            };
        }else{
            $this->collection=match($slug){
                "new"=>[1,"Nouveautés",Product::latest()->with('images')->where("status","Publie")->get()->toArray()],
                default => Collection::where('slug',$slug)->with(["featuredImage","products"=>function($query){$query->where("status","publie");},"products.images"])->where('active',true)->first()?->toArray(),
            };
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
        //si collection n'est pas nulle
        if($this->collection)
        {
            //si il a un id(donc c'est soit un group ou soit une collection group)
            if(array_key_exists('id',$this->collection))
            {
                //si c'est un
                if(array_key_exists('collection_group_id',$this->collection))
                {
                    $collections=Collection::where('parent_id',$this->collection['id'])->with('featuredImage')->get()->toArray();
                }
                else
                {
                    $collections=Collection::where('parent_id',null)->where('collection_group_id',$this->collection['id'])->with('featuredImage')->get()->toArray();
                }
            }else{
                return[];
            }

        }else{
            $collections=Collection::where('parent_id',null)->with('featuredImage')->whereHas('group',fn($query)=>$query->whereHas('productOption',fn($query)=>$query->where('name','Pointure')))->get()->toArray();
        }
        return array_map(fn($collection)=>['name'=>$collection["name"],'slug'=>$collection['slug'],'url'=>$collection['featured_image']?config('app.url').$collection['featured_image']['large_url']:"",'alt'=>$collection['featured_image']?$collection['featured_image']['alt']:""],$collections);
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
            $products=Product::with('images')->where('status','Publie')->where('product_option_id',1)->get()->toArray();
        }

        return array_map(fn($product)=>['name'=>$product["name"],'slug'=>array_key_exists('images',$product)?$product['slug']:$product['slug'].'?k','url'=>array_key_exists('images',$product)?config('app.url').$product['images'][0]['large_url']:config('app.url').$product['featured_image']['large_url'],'alt'=>array_key_exists('images',$product)?$product['images'][0]['alt']:$product['featured_image']['alt'],"price"=>array_key_exists('old_price',$product)?$product["old_price"]:$product["price"]],$products);
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

    #[Title('Collection')]
    public function render()
    {
        return view('livewire.guest.collection.collection-page')->with([
                'breadcrumbs'=>$this->getBreadCrumb(),
                'name'=>$this->getName(),
                'collections'=>$this->getCollections(),
                'products'=>$this->arrayPaginate($this->getProducts()),
        ]);
    }
}
