<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers\VariantsRelationManager;
use App\Models\Product;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\SpatieTagsColumn;
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
                Repeater::make('attribute_data')
                    ->schema([
                        TextInput::make('text')->label('info')->url(),

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
                Select::make('status')
                    ->options([
                        'enPreparation' => 'En préparation',
                        'cache' => 'Caché',
                        'Publie' => 'Publié',
                    ])
                    ->default('enPreparation')
                    ->disabled(!request()->routeIs('filament.admin.resources.products.edit')),
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
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            RelationManagers\VariantsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\Product::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }


}
