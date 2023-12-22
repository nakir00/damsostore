<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;

use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('Address.first_name')->label("Prenom"),
                TextEntry::make('Address.last_name')->label("Nom"),
                TextEntry::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'enAttente' => 'gray',
                        'confirme' => 'success',
                        'livre' => 'info',
                        'annule'=> 'danger',
                        'enLivraison'=>'warning',
                    }),
                TextEntry::make('reference'),
                TextEntry::make('sub_total')
                    ->label('montant hors remise')
                    ->suffix(' F cfa')
                    ->numeric(),
                TextEntry::make('discount_total')
                    ->label('montant remise')
                    ->suffix(' F cfa')
                    ->numeric(),
                TextEntry::make('shipping_total')
                    ->label('Livraison')
                    ->suffix(' F cfa')
                    ->numeric(),
                TextEntry::make('total')
                    ->label('montant final')
                    ->suffix(' F cfa')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->label('date de commande')
                    ->dateTime(),
                TextEntry::make('Address.pays'),
                TextEntry::make('Address.region'),
                TextEntry::make('Address.departement'),
                TextEntry::make('Address.commune'),
                TextEntry::make('Address.line_one'),
                TextEntry::make('Address.line_two'),
                TextEntry::make('Address.line_three'),
                TextEntry::make('Address.contact_email'),
                TextEntry::make('Address.attribute_data'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('Address.first_name')->label("Prenom")->searchable(),
                TextColumn::make('Address.last_name')->label("Nom")->searchable(),
                TextColumn::make('Address.contact_email')->label("Nom")->searchable(),
                TextColumn::make('Address.contact_phone')->label("Nom")->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'enAttente' => 'gray',
                        'confirme' => 'success',
                        'livre' => 'info',
                        'annule'=> 'danger',
                        'enLivraison'=>'warning',
                    }),
                TextColumn::make('sub_total')
                    ->summarize(Sum::make()->label('Total'))
                    ->label('montant hors remise')
                    ->suffix(' F cfa')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('discount_total')
                    ->summarize(Sum::make()->label('Total'))
                    ->label('montant remise')
                    ->suffix(' F cfa')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('shipping_total')
                    ->summarize(Sum::make()->label('Total'))
                    ->label('Livraison')
                    ->suffix(' F cfa')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total')
                    ->summarize(Sum::make()->label('Total'))
                    ->label('montant final')
                    ->suffix(' F cfa')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('date_commande')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('date_confirmation')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('date_livraison')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('date_annulation')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                //date_commande
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                /* Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]), */
            ])
            ->emptyStateActions([
                /* Tables\Actions\CreateAction::make(), */
            ])->groups([
                Tables\Grouping\Group::make('date')
                    ->label('Par date')
                    ->collapsible(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            RelationManagers\OrderablesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            //'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }
}
