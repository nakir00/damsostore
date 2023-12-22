<?php

namespace App\Filament\Resources\DiscountResource\RelationManagers;

use App\Models\Collection;
use App\Models\CollectionGroup;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Attributes\On;

use function Pest\Laravel\get;

class GroupsRelationManager extends RelationManager
{
    protected static string $relationship = 'groups';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                CuratorColumn::make('featured_image_id')->size(80)->rounded(),
                TextColumn::make('name')->label("nom")->searchable(),
                TextColumn::make('slug')->label("slug")->searchable(),
                SpatieTagsColumn::make('tags')->searchable(),
                TextColumn::make('productOption.name')->label('option de produit'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('attacher avec produits')
                    ->requiresConfirmation()
                    ->modalHeading('attacher avec produits')
                    ->modalDescription('cela va appliquer la promotion à tous les produits de cette collection ')
                    ->modalSubmitActionLabel('Appliquer')
                    ->form([
                        Select::make('collection')
                            ->label('Groupe de collection')
                            ->options(CollectionGroup::query()->pluck('name','id'))
                    ])
                    ->action(function($data)  {
                        $this->ownerRecord->groups()->attach($data['collection']);
                        $acc=CollectionGroup::query()->find($data['collection']);
                        if($acc->name==='kit'||$acc->name==='kits')
                        {
                            $kits=$acc->kits()->get()->pluck('id')->toArray();
                            $idpresent=$this->ownerRecord->kits()->get()->pluck('id')->all();
                            $this->ownerRecord->kits()->attach(array_diff($kits,$idpresent));
                            Notification::make()
                                ->title('Attachés avec succés')
                                ->success()
                                ->body('les kits on été attachés avec suucés')
                                ->send();
                            return;
                        }else {
                            $p=[];
                            $ids=[];
                            $cds=[];
                            $ccs=Collection::query()->where('collection_group_id',$data['collection'])->where('parent_id',null)->get()->all();
                            foreach ($ccs as $collection) {
                                $p=array_merge($p,$collection->products()->get()->all());
                                $cds[]=$collection->id;
                                $cds=array_merge($cds,$this->getAllEnfants($collection->id));
                            }
                            foreach ($p as $prod) {
                                $ids[]=$prod->id;
                            }
                            $idpresent=$this->ownerRecord->products()->get()->pluck('id')->toArray();
                            $this->ownerRecord->products()->attach(array_diff($ids,$idpresent));
                            $cpresent=$this->ownerRecord->collections()->get()->pluck('id')->toArray();
                            $this->ownerRecord->collections()->attach(array_diff($cds,$cpresent));
                        }

                        Notification::make()
                            ->title('Attachés avec succés')
                            ->success()
                            ->body('les produits on été attachés avec suucés')
                            ->send();
                    })
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Action::make('attacher avec produits')
                    ->requiresConfirmation()
                    ->modalHeading('attacher avec produits')
                    ->modalDescription('cela va appliquer la promotion à tous les produits de cette collection ')
                    ->modalSubmitActionLabel('Appliquer')
                    ->form([
                        Select::make('collection')
                            ->label('Groupe de collection')
                            ->options(CollectionGroup::query()->pluck('name','id'))
                    ])
                    ->action(function($data)  {
                        $this->ownerRecord->groups()->attach($data['collection']);
                        $acc=CollectionGroup::query()->find($data['collection']);
                        if($acc->name==='kit'||$acc->name==='kits')
                        {
                            $kits=$acc->kits()->get()->pluck('id')->toArray();
                            $idpresent=$this->ownerRecord->kits()->get()->pluck('id')->all();
                            $this->ownerRecord->kits()->attach(array_diff($kits,$idpresent));
                            Notification::make()
                                ->title('Attachés avec succés')
                                ->success()
                                ->body('les kits on été attachés avec suucés')
                                ->send();
                            return;
                        }else {
                            $p=[];
                            $ids=[];
                            $cds=[];
                            $ccs=Collection::query()->where('collection_group_id',$data['collection'])->where('parent_id',null)->get()->all();
                            foreach ($ccs as $collection) {
                                $p=array_merge($p,$collection->products()->get()->all());
                                $cds[]=$collection->id;
                                $cds=array_merge($cds,$this->getAllEnfants($collection->id));
                            }
                            foreach ($p as $prod) {
                                $ids[]=$prod->id;
                            }
                            $idpresent=$this->ownerRecord->products()->get()->pluck('id')->toArray();
                            $this->ownerRecord->products()->attach(array_diff($ids,$idpresent));
                            $cpresent=$this->ownerRecord->collections()->get()->pluck('id')->toArray();
                            $this->ownerRecord->collections()->attach(array_diff($cds,$cpresent));
                        }

                        Notification::make()
                            ->title('Attachés avec succés')
                            ->success()
                            ->body('les produits on été attachés avec suucés')
                            ->send();
                    })
            ]);
    }


    public function getAllEnfants($object):array
    {
        $ids=Collection::query()->where('parent_id',$object)->get()->pluck('id')->all();
        if($ids===[])
        {return[];}
        foreach ($ids as $value) {

            $ids=array_merge($ids,$this->getAllEnfants($value));
        }
        return$ids;
    }

}
