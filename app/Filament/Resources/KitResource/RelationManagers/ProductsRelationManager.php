<?php

namespace App\Filament\Resources\KitResource\RelationManagers;

use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

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
                TextColumn::make('name')->label("nom"),
                TextColumn::make('slug')->label("slug")->searchable(),
                TextColumn::make('productType.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('old_price')->label("prix général")->suffix(' Francs cfa')->sortable(),
                SpatieTagsColumn::make('tags'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'enPreparation' => 'gray',
                        'cache' => 'warning',
                        'Publie' => 'success'
                    })
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->actions([
                Action::make('voir')
                    ->url(fn (Product $record): string => route('filament.admin.resources.products.edit', $record)),
                Tables\Actions\DetachAction::make(),
            ])
            ->headerActions([
                AttachAction::make()
                ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\AttachAction::make()->preloadRecordSelect(),
            ]);

    }
}
