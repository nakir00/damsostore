<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CollectionResource\Pages;
use App\Filament\Resources\CollectionResource\RelationManagers;
use App\Models\Collection;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                TextInput::make('name')->required()->maxLength(50)
               /*  ->live()
                ->afterStateUpdated(fn ($component) => $form->schema([])), */,
                Select::make('collection_group_id')
                    ->relationship(name: 'group', titleAttribute: 'name')
                    ->label('group'),
                Select::make('parent_id')
                    ->relationship(name: 'parent', titleAttribute: 'name')
                    ->label('collection parent'),
                /* Repeater::make('members')
                    ->schema([
                        Select::make('parent_id')
                    ->relationship(name: 'parent', titleAttribute: 'name')
                    ->label('collection parent'),
                    ]) ->reorderable(false) ->deletable(false)
 */

            ]);
            return $form;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                CuratorColumn::make('featured_image_id')->size(50)->rounded(),
                TextColumn::make('name')->label("nom"),
                TextColumn::make('group.name')->label('groupe'),
            ])
            ->filters([
                //
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                Action::make('voir')
                ->url(fn (Collection $record): string => route('filament.admin.resources.collections.view', $record)),
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
