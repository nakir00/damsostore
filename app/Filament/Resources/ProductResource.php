<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;


class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                CuratorPicker::make('images')
                    ->multiple()
                    ->relationship('images', 'id')
                    ->orderColumn('order'),
                TextInput::make('name')->required()->live(debounce: 1000)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                TextInput::make('slug')->prefix('product/'),
                TextInput::make('old_price')->numeric()->placeholder("25.000")->required(),
                Repeater::make('attribute_data')->label('arbre des colections')
                    ->schema([
                        TextInput::make('collection_name')->label('nom')->disabled(),
                    ])
                    ->addable(false)
                    ->reorderable(false)
                    ->deletable(false),
                Repeater::make('description')
                    ->schema([
                        TextInput::make('text')->label('info'),
                        RichEditor::make('value')->label('text')->toolbarButtons([
                            'blockquote',
                            'bold',
                            'bulletList',
                            'italic',
                            'link',
                            'orderedList',
                            'redo',
                            'strike',
                            'underline',
                            'undo',
                        ])

                    ]),
                Select::make('product_type_id')
                    ->relationship(name: 'productType', titleAttribute: 'name')
                    ->label('type de produit')
                    ->searchable()
                    ->preload()
                    ->optionsLimit(20),
                SpatieTagsInput::make('tags')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                CuratorColumn::make('images')
            ->size(40)
            ->ring(2) // options 0,1,2,4
            ->overlap(4) // options 0,2,3,4
            ->limit(3),
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
                        'cache' => 'danger',
                        'Publie' => 'success'
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            RelationManagers\VariantsRelationManager::class,
            RelationManagers\KitsRelationManager::class,
            RelationManagers\CollectionsRelationManager::class,
            RelationManagers\AssociationsRelationManager::class,
        ];
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->with(['featured_image', 'product_pictures']);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
