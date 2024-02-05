<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductOptionResource\Pages;
use App\Filament\Resources\ProductOptionResource\RelationManagers;
use App\Models\ProductOption;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductOptionResource extends Resource
{
    protected static ?string $model = ProductOption::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public function mount(): void
    {
        abort_unless(auth()->user()->role==='admin', 403);
        if(auth()->user()->role!=='admin')
        {
            redirect(route('filament.admin.pages.dashboard'));
        }
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('name')->required()->maxLength(50),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('name')->label("nom"),
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
                ]) ,*/
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //

            RelationManagers\ValuesRelationManager::class
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->role==='admin';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductOptions::route('/'),
            'create' => Pages\CreateProductOption::route('/create'),
            'edit' => Pages\EditProductOption::route('/{record}/edit'),
        ];
    }
}
