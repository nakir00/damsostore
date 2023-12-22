<?php

namespace App\Livewire\Guest\Welcome;

use App\Models\Collection;
use App\Models\Home;
use App\Models\Product;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class WelcomePage extends Component
{

    public function getSlides()
    {
        $slides = Home::where('active',true)->first()->topSliders()->where('active', true)->with('featuredImage')->get();
        return array_map(fn( $topSlider)=>['button_message'=>$topSlider['button_message'],'button_link'=>$topSlider['button_link'],'primary'=>$topSlider['primary'],'secondary'=>$topSlider['secondary'],'position'=>$topSlider['position'],'url'=>$topSlider['featured_image']['large_url'],'alt'=>$topSlider['featured_image']['alt'],'info'=>$topSlider['info']],$slides->toArray());
    }

    public function getCollectionsSlider()
    {
        $datas=Home::where('active',true)->first()->collectionSliders()->get()->toArray();
        $assocNameId=array_reduce($datas, function ($carry, $item) {$carry[$item['collectionable_id']] = $item['name'];return $carry;}, []);
        $ids=array_map(fn($collection)=> $collection['collectionable_id'],$datas);
        $sliders=Collection::whereIn('id',$ids)->with('featuredImage','discounts')->get()->all();
        $toSend=[];
        foreach ($sliders as $collection) {
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
            $toSend[]=['name'=>$assocNameId[$collection->id],'slug'=>$collection->slug,'url'=>$collection->featuredImage()->get()->first()->toArray()['large_url']??"",'alt'=>$collection->featuredImage()->get()->first()->toArray()['alt']??"",'remise'=>$coupon===null?null:($coupon->data['type']==='percentage'?$coupon->data['percentage']:$coupon->data['fixed_values']),'type'=> $coupon===null?null:$coupon->data['type']];
        }
        return $toSend;
    }

    public function getList()
    {
        $ids=array_map(fn($collection)=>$collection['id'],Home::where('active',true)->first()->productSliders()->get()->toArray());
        $latestCollections=Product::latest()->with('images','discounts')->where("status","Publie")->take(10)->get()->all();
        $collections[]=["name"=>"nouveautés","slug"=>"new","products"=>$this->formatProducts($latestCollections)];
        $collectionss=Collection::where('id',$ids)->where('active',true)->with(['products' => function ($query) {$query->where("status","Publie")->latest()->take(10);},'products.images'])->get()->all();
        foreach ($collectionss as $collection) {
            $collections[]=['name'=>$collection['name'],'slug'=>$collection['slug'],"products"=>$this->formatProducts($collection["products"])];
        }
        return$collections;
    }

    public function formatProducts($array):array
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
            $image=$collection->images()->get()->first()->toArray();
            $list[]=[
                "slug"=>$collection->slug,
                "name"=>$collection->name,
                "price"=>$collection->old_price,
                'alt'=>$image['alt']??"",
                'url'=> $image['large_url'],
                'remise'=>$coupon===null?null:($coupon->data['type']==='percentage'?$coupon->data['percentage']:$coupon->data['fixed_values']),
                'type'=> $coupon===null?null:$coupon->data['type']
                ];
        }
        return$list;
    }


    #[Title('Page d\'accueil')]
    public function render()
    {
        SEOMeta::setTitle('Bienvenue');
        SEOMeta::setDescription('les meilleures chaussures de Dakar');
        SEOMeta::setCanonical('https://damsostore.com/');

        OpenGraph::setDescription('les meilleures chaussures de Dakar');
        OpenGraph::setTitle('DamsoStore - Accueil');
        OpenGraph::setUrl('https://damsostore.com/');
        OpenGraph::addProperty('type', 'shoe','chaussures dakar');



        return view('livewire.guest.welcome.welcome-page')
                ->with([
                    'slides'=>$this->getSlides(),
                    'collections'=>$this->getCollectionsSlider(),
                    'list'=>$this->getList()
                ]);
    }
}
