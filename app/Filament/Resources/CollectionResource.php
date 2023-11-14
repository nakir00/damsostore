<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CollectionResource\Pages;
use App\Models\Collection;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CollectionResource extends Resource
{
    protected static ?string $model = Collection::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function form(Form $form): Form
    {

         $form
            ->schema([
                //
                CuratorPicker::make('featured_image_id')
                    ->relationship('featuredImage', 'id'),
                TextInput::make('name')->required()->live(debounce: 1000)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                TextInput::make('slug')->prefix('collection/'),
                Select::make('collection_group_id')
                    ->relationship(name: 'group', titleAttribute: 'name')
                    ->label('group'),
                Select::make('parent_id')
                    ->relationship(name: 'parent', titleAttribute: 'name')
                    ->label('collection parent'),
                SpatieTagsInput::make('tags'),

            ]);
            return $form;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                CuratorColumn::make('featured_image_id')->size(50)->rounded(),
                TextColumn::make('name')->label("nom")->searchable(),
                TextColumn::make('slug')->label("slug")->searchable(),
                SpatieTagsColumn::make('tags')->searchable(),
                TextColumn::make('group.name')->label('groupe'),
                ToggleColumn::make('active')
            ])
            ->filters([
                //
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                Action::make('changer')
                ->url(fn (Collection $record): string => route('filament.admin.resources.collections.edit', $record))

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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCollections::route('/'),
            'create' => Pages\CreateCollection::route('/create'),
            'view' => Pages\collection::route('/{record}'),
            'edit' => Pages\EditCollection::route('/{record}/edit'),
        ];
    }
}
