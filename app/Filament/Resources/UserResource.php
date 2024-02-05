<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public function mount(): void
    {
        abort_unless(auth()->user()->role==='admin', 403);
        if(auth()->user()->role!=='admin')
        {
            redirect(route('filament.admin.pages.dashboard'));
        }
    }

    public static function table(Table $table): Table
    {
        return $table
        ->query(User::query()->whereIn('role',['assistant','admin']))

            ->columns([
                TextColumn::make('name')->label('nom'),
                TextColumn::make('email')->label('email'),
                TextColumn::make('role')->label('role'),
            ])
            ->filters([
                //
            ])
            ->actions([
                //Action::make('changer le role')

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

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->role==='admin';
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
            'index' => Pages\ListUsers::route('/'),
        ];
    }
}
