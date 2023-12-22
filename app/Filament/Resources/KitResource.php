<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KitResource\Pages;
use App\Filament\Resources\KitResource\RelationManagers;
use App\Models\Kit;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class KitResource extends Resource
{
    protected static ?string $model = Kit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                CuratorPicker::make('featured_image_id')
                    ->relationship('featuredImage', 'id')
                    ->orderColumn('order')
                    ->label('images')
                    ->buttonLabel('ajouter des images')
                    ->color('primary') // defaults to primary
                    ->size('md') // defaults to md
                    ->constrained(true)
                    ->lazyLoad(true)
                    ->preserveFilenames(),
                TextInput::make('name')->required()->live(debounce: 1000)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                TextInput::make('slug')->prefix('kit/'),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->suffix('franc'),
                Select::make('status')
                    ->options([
                        'enPreparation' => 'En préparation',
                        'cache' => 'Caché',
                        'Publie' => 'Publié',
                    ])
                    ->default('enPreparation')
                    ->disabled(!request()->routeIs('filament.admin.resources.products.edit')),
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
                Select::make('collection_group_id')
                    ->relationship(name: 'collectionGroup', titleAttribute: 'name')
                    ->label('type de produit')
                    ->searchable()
                    ->preload()
                    ->optionsLimit(20),
                SpatieTagsInput::make('tags'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                CuratorColumn::make('featured_image_id')
                    ->size(40),
                TextColumn::make('name')->label("nom")
                    ->searchable(),
                TextColumn::make('collectionGroup.name')
                    ->searchable(),
                SpatieTagsColumn::make('tags')->prefix('#'),
                TextColumn::make('total_price')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('price')->label("prix général")->suffix(' Francs cfa')->sortable(),
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
            RelationManagers\ProductsRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKits::route('/'),
            'create' => Pages\CreateKit::route('/create'),
            'edit' => Pages\EditKit::route('/{record}/edit'),
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
