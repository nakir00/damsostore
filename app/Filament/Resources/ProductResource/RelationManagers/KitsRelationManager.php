<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\kit;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KitsRelationManager extends RelationManager
{
    protected static string $relationship = 'kits';

    public function table(Table $table): Table
    {
        return $table
            ->query(Kit::query()->whereHas('products',function ($query) {$query->where('product_id', $this->ownerRecord->id);}))
            ->recordTitleAttribute('name')
            ->columns([
                CuratorColumn::make('featured_image_id')
        ->size(40),
        TextColumn::make('name')->label("nom")
            ->searchable(),
        TextColumn::make('collectionGroup.name')
            ->searchable(),
        TextColumn::make('price')->label("prix général")->suffix(' Francs cfa')->sortable(),
        TextColumn::make('status')
            ->badge()
            ->color(fn (string $state): string => match ($state) {
                'enPreparation' => 'gray',
                'cache' => 'danger',
                'Publie' => 'success'
            })
            ->sortable()
            ])
            ->actions([
                Action::make('voir')
                ->url(fn (kit $record): string => route('filament.admin.resources.kits.view', $record))
            ])

            ->emptyStateActions([
                Action::make('creer un Kits')
                        ->url(fn (): string => route('filament.admin.resources.kits.create'))
            ]);
    }
}
