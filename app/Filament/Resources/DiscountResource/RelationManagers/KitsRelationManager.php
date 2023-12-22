<?php

namespace App\Filament\Resources\DiscountResource\RelationManagers;

use Awcodes\Curator\Components\Tables\CuratorColumn;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KitsRelationManager extends RelationManager
{
    protected static string $relationship = 'kits';

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
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make(),
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
                Tables\Actions\AttachAction::make(),
            ]);
    }
}
