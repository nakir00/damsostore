<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssociationsRelationManager extends RelationManager
{
    protected static string $relationship = 'associations';

    public function table(Table $table): Table
    {
        return $table
            ->query(Product::query()->whereHas('associations',function ($query) {$query->where('product_parent_id', $this->ownerRecord->id);}))
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')->label("nom"),
                TextColumn::make('slug')->label("slug")->searchable(),
                TextColumn::make('productType.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('old_price')->label("prix général")->suffix(' Francs cfa')->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'enPreparation' => 'gray',
                        'cache' => 'warning',
                        'Publie' => 'success'
                    })
            ])
            ->actions([
                Action::make('voir')
                ->url(fn (Product $record): string => route('filament.admin.resources.products.edit', $record))
            ]);

    }
}
