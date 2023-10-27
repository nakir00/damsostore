<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CollectionGroupResource\Pages;
use App\Filament\Resources\CollectionGroupResource\RelationManagers;
use App\Models\CollectionGroup;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use function Laravel\Prompts\text;

class CollectionGroupResource extends Resource
{
    protected static ?string $model = CollectionGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                CuratorPicker::make('featured_image_id')
                    ->relationship('featuredImage', 'id'),
                TextInput::make('name')->required()->maxLength(50),
                Select::make('product_option_id')
                    ->relationship(name: 'productOption',titleAttribute:'name')
                    ->label('options de produit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                CuratorColumn::make('featured_image_id')->size(80)->rounded(),
                TextColumn::make('name')->label("nom"),
                TextColumn::make('product_option')->label('option de produit'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                /* Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]), */
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            RelationManagers\CollectionsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCollectionGroups::route('/'),
            'create' => Pages\CreateCollectionGroup::route('/create'),
            'edit' => Pages\EditCollectionGroup::route('/{record}/edit'),
        ];
    }
}
