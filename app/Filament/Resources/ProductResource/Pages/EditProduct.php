<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Collection;
use App\Models\ProductOptionValue;
use App\Models\ProductVariant;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Action::make('publier')
            ->disabled($this->record->collection_id===null)
            ->hidden($this->record->status==="Publie")
            ->color('success')
            ->requiresConfirmation()
            ->action(function (){
                $this->record->status="Publie";
                $this->record->save();
                Notification::make()
                ->title('publié avec succés')
                ->success()
                ->seconds(8)
                ->body('le produit est desormais accéssible directement')
                ->send();
            }),
            Action::make('cacher')
            ->hidden($this->record->status==="cache"||$this->record->status==="enPreparation")
            ->color('danger')
            ->requiresConfirmation()
            ->action(function (){
                $this->record->status="cache";
                $this->record->save();
                Notification::make()
                ->title('caché avec succés')
                ->success()
                ->seconds(8)
                ->body('le produit est invisible directement')
                ->send();
            }),
            Action::make('detacher')
            ->hidden($this->record->status==="Publie"||$this->record->collection_id===null)
            ->color('danger')
            ->requiresConfirmation()
            ->action(function (){
                $this->deleteAll();
                $this->record->status="cache";
                $this->record->collection_id=null;
                $this->record->attribute_data=[];
                $this->record->save();
                Notification::make()
                ->title('Collection detaché avec succés')
                ->success()
                ->seconds(8)
                ->body('vous pouvez désormais attacher à des collection ayant d\'autres options maintenant')
                ->send();
            }),
            Action::make('Attacher Collection')

            ->fillForm(fn (): array => [
                'collections' => $this->record->collection_id,
                ])
            ->form([
                Select::make('collections')
                ->options(function(){
                    if($this->record->variants()->get()->toArray()===[])
                    {return Collection::query()->whereHas('group',function ($query) {$query->whereHas('productOption');})->pluck('name', 'id');}
                    else
                    {return Collection::query()->whereHas('group',function ($query) {$query->whereHas('productOption', function ($query) {$query->where('id', $this->record->product_option_id);});})->pluck('name', 'id');}
                })
                ->searchable()

                    //->default('enPreparation'),
            ])->action(function (array $data): void {
                //dd(Collection::query()->whereHas('group',function ($query) {$query->whereHas('productOption');})->get()->all(),$this->record);
                $this->manageCollectionAttachement($data['collections']);
            })
        ];
    }

    public function manageCollectionAttachement($id)
    {
        //on recupere la collection à travers son id
        $collection=Collection::find($id);
        if($this->isCollectionAttachable($collection))
        {
            //on recupere les parents de cette collection
            $parents=$this->getAllParents($collection);
            //on met les collections parentes dans la propriete attribute data
            $this->record->attribute_data=array_map(fn($collection)=>["collection_name"=>$collection['name']],array_merge($parents,[$collection]));
            //on filtre seulement les collections qui doivent etre attachee
            [$toAttach,$toDetach]=$this->getAttaches(array_merge($parents,[$collection]));
            //on attache les collections concernées
            $this->attachToCollection($toAttach);
            //on detache les collections qui ne sont plus concernées
            $this->detachChildCollections($toDetach);
            //on save la collection pour la retrouver sur le record
            $this->record->collection_id=$collection->id;
            //on save le productOption sur le record
            $this->record->product_option_id=$collection->group()->first()->productOption()->get()->first()->id;
            //on enregistre les modifications sur le record
            $this->record->save();

            Notification::make()
                ->title('Attaché avec succés')
                ->success()
                ->seconds(8)
                ->body('vous pouvez créer des variantes maintenant')
                ->send();
        }
        else{
            Notification::make()
                ->title('Collection non attaché')
                ->danger()
                ->seconds(8)
                ->body('cette collection ne respecte pas les conditions pour etre attachée')
                ->send();
        }

    }

    public function deleteAll()
    {
        $isEmpty=$this->record->variants()->get()->all()===[];
        if(!$isEmpty){ProductVariant::query()->delete(array_map(fn($variante)=>$variante->id,$this->record->variants()->get()->all()));}
    }


    public function isCollectionAttachable(Collection $collection):bool
    {
        $flag=false;
        if(is_null($this->record->product_option_id))
        {
            $flag=true;
        }
        else{
            if($this->record->variants()->get()->toArray()===[])
            {
                $flag=true;
            }
            else{
                if($collection->group()->first()->productOption()->first()->id===$this->record->product_option_id)
                {
                    $flag=true;
                }
            }
        }
        return$flag;
    }

    public function getAllParents($object, $allParents = []):array
    {
        if ($object->parent_id!=null) {
            $parent=$object->parent()->get()->first();
            $allParents[] = $parent;
            return $this->getAllParents($parent, $allParents);
        }
        return $allParents;
    }

    public function getAttaches($array)
    {
        $array=array_map(fn($collection) => $collection->id, $array);
        $datas=$this->record->collections()->wherePivot('product_id',$this->record->id)->get()->toJson();
        $datas=json_decode($datas,true);
        $dbAttachement=array_map(fn($collection) => $collection['id'], $datas);
        $toDetach=array_diff($dbAttachement,$array);
        $toAttach=array_diff($array,$dbAttachement);
        return [$toAttach,$toDetach];
    }

    public function attachToCollection($array)
    {
        $this->record->collections()->attach($array);
    }

    public function detachChildCollections($array)
    {
            $this->record->collections()->detach($array);
    }



}
