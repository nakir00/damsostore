<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductOptionValue;
use App\Models\ProductVariant;
use App\Models\User;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    public function mount(): void
    {
        abort_unless(User::isAdmin(), 403);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('generer variantes')
            ->color('gray')
            ->requiresConfirmation()
            ->modalDescription("cela va (re)initialiser les variantes. vous confirmez ?")
            ->action(function (){
                $products=Product::query()->where('status','Publie')->get()->all();
                foreach ($products as $product) {
                    $product->variants()->delete();
                    $this->associateVariante($product);
                }
                //$this->deleteAll();
                //$this->associateVariante();
                Notification::make()
                ->title('variantes générées avec succés')
                ->success()
                ->seconds(8)
                ->body('vous pouvez gérer les prix et la disponibilité des variantes maintenant ')
                ->send();
            })
        ];
    }

    public function associateVariante(object $product)
    {
        $values=ProductOptionValue::where('product_option_id',$product->product_option_id)->get()->all();
        $names=array_map(fn($value)=>$value->name,$values);
        $imageOwner=$product->images()->get()->first()->toArray();
        $acc=[];
        foreach ($names as  $name) {
            if(in_array($name,['46','47','35','36','37','38','39']))
            {
                $acc[]=["name"=>$name,"attribute_data"=>["product"=>["name"=>$product->name,"url"=>$imageOwner['large_url'],"alt"=>$imageOwner['alt']]],"min_price"=>$product->old_price,"disponibility"=>false];
            }else{
                $acc[]=["name"=>$name,"attribute_data"=>["product"=>["name"=>$product->name,"url"=>$imageOwner['large_url'],"alt"=>$imageOwner['alt']]],"min_price"=>$product->old_price,"disponibility"=>true];
            }
        }
        $product->variants()->createMany($acc);

    }

}
