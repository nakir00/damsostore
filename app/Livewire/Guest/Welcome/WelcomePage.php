<?php

namespace App\Livewire\Guest\Welcome;

use App\Livewire\Guest\Welcome\Component\TopSlider;
use App\Models\Collection;
use App\Models\collectionsSlider;
use App\Models\home;
use App\Models\Product;
use App\Models\topSlider as ModelsTopSlider;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class WelcomePage extends Component
{

    public function getSlides()
    {
        $slides = home::where('active',true)->first()->topSliders()->where('active', true)->with('featuredImage')->get();
        return array_map(fn( $topSlider)=>['button_message'=>$topSlider['button_message'],'button_link'=>$topSlider['button_link'],'primary'=>$topSlider['primary'],'secondary'=>$topSlider['secondary'],'position'=>$topSlider['position'],'url'=>$topSlider['featured_image']['url'],'alt'=>$topSlider['featured_image']['alt'],'info'=>$topSlider['info']],$slides->toArray());
    }

    public function getCollectionsSlider()
    {
        $datas=home::where('active',true)->first()->collectionSliders()->get()->toArray();
        $assocNameId=array_reduce($datas, function ($carry, $item) {$carry[$item['collectionable_id']] = $item['name'];return $carry;}, []);
        $ids=array_map(fn($collection)=> $collection['collectionable_id'],$datas);
        $sliders=Collection::whereIn('id',$ids)->with('featuredImage')->get()->toArray();
        $slides=array_map(fn($slide)=> ['name'=>$assocNameId[$slide['id']],'slug'=>$slide['slug'],'url'=>$slide['featured_image']?config('app.url').$slide['featured_image']['large_url']:"",'alt'=>$slide['featured_image']?$slide['featured_image']['alt']:""],$sliders);
        return $slides;//medium_url,thumbnail_url
    }


    public function getList()
    {
        $ids=array_map(fn($collection)=>$collection['id'],home::where('active',true)->first()->productSliders()->get()->toArray());
        $latestCollections=Product::latest()->with('images')->where("status","Publie")->take(10)->get()->toArray();
        $collections[]=["name"=>"nouveautés","slug"=>"new","products"=>$this->formatProducts($latestCollections)];
        $collectionss=Collection::where('id',$ids)->where('active',true)->with(['products' => function ($query) {$query->where("status","Publie")->latest()->take(10);},'products.images'])->get()->toArray();
        foreach ($collectionss as $collection) {
            $collections[]=['name'=>$collection['name'],'slug'=>$collection['slug'],"products"=>$this->formatProducts($collection["products"])];
        }
        return$collections;
    }

    public function formatProducts($array):array
    {

        $list=[];
        foreach ($array as $collection) {

            $image=reset($collection['images']);
            $list[]=[
                "slug"=>$collection['slug'],
                "name"=>$collection['name'],
                "price"=>$collection['old_price'],
                'alt'=>$image['alt']??"",
                'url'=> config('app.url').$image['large_url'],
                ];
        }
        return$list;
    }



    #[Title('Page d\'accueil')]
    public function render()
    {
        return view('livewire.guest.welcome.welcome-page')
                ->with([
                    'slides'=>$this->getSlides(),
                    'collections'=>$this->getCollectionsSlider(),
                    'list'=>$this->getList()
                ]);
    }
}
