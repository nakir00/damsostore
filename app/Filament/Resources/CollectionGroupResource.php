<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CollectionGroupResource\Pages;
use App\Filament\Resources\CollectionGroupResource\RelationManagers;
use App\Models\CollectionGroup;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

use function Laravel\Prompts\text;

class CollectionGroupResource extends Resource
{
    protected static ?string $model = CollectionGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public function mount(): void
    {
        if(auth()->user()->role!=='admin')
        {
            redirect(route('filament.admin.pages.dashboard'));
        }
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                CuratorPicker::make('featured_image_id')
                    ->relationship('featuredImage', 'id')->required(),
                    TextInput::make('name')->required()->live(debounce: 1000)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                    TextInput::make('slug')->prefix('collection/'),
                SpatieTagsInput::make('tags'),
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
                TextColumn::make('name')->label("nom")->searchable(),
                TextColumn::make('slug')->label("slug")->searchable(),
                SpatieTagsColumn::make('tags')->searchable(),
                TextColumn::make('productOption.name')->label('option de produit'),
                ToggleColumn::make('onNavBar')
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

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->role==='admin';
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
