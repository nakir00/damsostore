<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\Collection;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CollectionsRelationManager extends RelationManager
{
    protected static string $relationship = 'collections';


    public function table(Table $table): Table
    {
        return $table
        ->query(Collection::query()->whereHas('products',function ($query) {$query->where('product_id', $this->ownerRecord->id);})->whereHas('group',function ($query) {$query->where('product_option_id', null);}))
            ->recordTitleAttribute('name')
            ->columns([
                CuratorColumn::make('featured_image_id')->size(50)->rounded(),
                TextColumn::make('name')->label("nom")->searchable(),
                TextColumn::make('slug')->label("slug")->searchable(),
                TextColumn::make('group.name')->label('groupe'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('voir')
                ->url(fn (Collection $record): string => route('filament.admin.resources.collections.view', $record)),
                DetachAction::make('detacher'),
            ])
            ;

    }
}
