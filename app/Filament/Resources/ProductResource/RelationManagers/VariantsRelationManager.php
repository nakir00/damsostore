<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\ProductOptionValue;
use App\Models\ProductVariant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use function PHPUnit\Framework\isEmpty;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';



    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')->searchable()->label('nom'),
                ToggleColumn::make('disponibility')->label('disponible'),
                TextInputColumn::make('min_price')->sortable()->rules(['required','numeric', 'max:100000'])
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('generer variantes')
                ->hidden($this->ownerRecord->collection_id===null)
            ->color('gray')
            ->requiresConfirmation()
            ->action(function (){
                $this->ownerRecord->variants()->delete();
                $this->ownerRecord->GenerateVariants();
                Notification::make()
                ->title('variantes générées avec succés')
                ->success()
                ->seconds(8)
                ->body('vous pouvez gérer les prix et la disponibilité des variantes maintenant ')
                ->send();
            }),
            ])
            ->emptyStateActions([
                Action::make('generer variantes')
                ->hidden($this->ownerRecord->collection_id===null)
            ->color('gray')
            ->requiresConfirmation()
            ->modalDescription("cela va (re)initialiser les variantes. vous confirmez ?")
            ->action(function (){
                $this->ownerRecord->variants()->delete();
                $this->ownerRecord->GenerateVariants();
                Notification::make()
                ->title('variantes générées avec succés')
                ->success()
                ->seconds(8)
                ->body('vous pouvez gérer les prix et la disponibilité des variantes maintenant ')
                ->send();
            })
            ]);
    }

    public function associateVariante()
    {
        $values=ProductOptionValue::where('product_option_id',$this->ownerRecord->product_option_id)->get()->all();
        $names=array_map(fn($value)=>$value->name,$values);
        $imageOwner=$this->ownerRecord->images()->get()->first()->toArray();
        $acc=[];
        foreach ($names as  $name) {

                if(in_array($name,['46','47','35','36','37','38','39']))
                {
                    $acc[]=["name"=>$name,"attribute_data"=>["product"=>["name"=>$this->ownerRecord->name,"url"=>$imageOwner['large_url'],"alt"=>$imageOwner['alt']]],"min_price"=>$this->ownerRecord->old_price,"disponibility"=>false];
                }else{
                    $acc[]=["name"=>$name,"attribute_data"=>["product"=>["name"=>$this->ownerRecord->name,"url"=>$imageOwner['large_url'],"alt"=>$imageOwner['alt']]],"min_price"=>$this->ownerRecord->old_price,"disponibility"=>true];
                }
        }
        $this->ownerRecord->variants()->createMany($acc);
    }
}
