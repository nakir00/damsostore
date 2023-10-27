<?php

namespace App\Filament\Resources\CollectionGroupResource\RelationManagers;

use Awcodes\Curator\Components\Forms\CuratorPicker;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
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
                CuratorColumn::make('featured_image_id')->size(80)->rounded(),
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
               // Tables\Actions\AttachAction::make(),
            ])
            ->actions([
                //Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
               /*  Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]), */
            ])
            ->emptyStateActions([
               // Tables\Actions\AttachAction::make(),
            ]);
    }
}
