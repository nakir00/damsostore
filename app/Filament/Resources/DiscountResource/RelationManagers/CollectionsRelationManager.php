<?php

namespace App\Filament\Resources\DiscountResource\RelationManagers;

use App\Models\Collection;
use App\Models\CollectionGroup;
use App\Models\Product;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CollectionsRelationManager extends RelationManager
{
    protected static string $relationship = 'collections';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                CuratorColumn::make('featured_image_id')->size(50)->rounded(),
                TextColumn::make('name')->label("nom")->searchable(),
                TextColumn::make('slug')->label("slug")->searchable(),
                SpatieTagsColumn::make('tags')->searchable(),
                TextColumn::make('group.name')->label('groupe'),
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
                                ->label('Collection')
                                ->options(Collection::query()->pluck('name','id'))
                        ])
                    ->action(function($data)  {
                        $acc=Collection::find($data['collection']);
                        $prods=$acc->products()->get()->pluck('id')->all();
                        $idpresent=$this->ownerRecord->collections()->get()->pluck('id')->all();
                        $this->ownerRecord->products()->attach(array_diff($prods,$idpresent));
                        $this->ownerRecord->collections()->attach($acc->id);

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
                                ->label('Collection')
                                ->options(Collection::query()->pluck('name','id'))
                        ])
                    ->action(function($data)  {
                        $acc=Collection::find($data['collection']);
                        $prods=$acc->products()->get()->pluck('id')->all();
                        $idpresent=$this->ownerRecord->products()->get()->pluck('id')->all();
                        $this->ownerRecord->products()->attach(array_diff($prods,$idpresent));
                        $at[]=$acc->id;
                        $at=array_merge($at,$this->getAllEnfants($acc->id));
                        $atP=$this->ownerRecord->collections()->get()->pluck('id')->all();
                        $this->ownerRecord->collections()->attach(array_diff($at,$atP));

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
