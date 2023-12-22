<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderablesRelationManager extends RelationManager
{
    protected static string $relationship = 'orderables';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('orderable.name')
                    ->label('Nom produit/kit')
                    ->sortable(),
                TextColumn::make('unit_price')
                    ->label('Prix unitaire')
                    ->suffix(' F cfa')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('quantity')
                    ->summarize(Sum::make()->label('nombre de produits total'))
                    ->label('Quantité')
                    ->prefix('nb : ')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sub_total')
                    ->summarize(Sum::make()->label('Total hors remise'))
                    ->label('montant hors remise')
                    ->suffix(' F cfa')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('meta.handle')//discount_unit
                    ->label('Nom remise')
                    ->sortable(),
                TextColumn::make('meta.discount_unit')
                    ->label('montant remise')
                    ->suffix(' F cfa')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('discount_total')
                    ->summarize(Sum::make()->label('Total remise'))
                    ->label('montant total de remise')
                    ->suffix(' F cfa')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total')
                    ->summarize(Sum::make()->label('montant final'))
                    ->label('Total')
                    ->suffix(' F cfa')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
                Action::make('voir')
                ->url(function($record){
                    $act=$record->orderable()->get()->first()->toArray();
                    if(array_key_exists('collection_group_id',$act))
                    {return route('filament.admin.resources.kits.edit',['record'=>$act['id']]);}
                    else
                    {return route('filament.admin.resources.products.edit',['record'=>$act['id']]);}
                }),

            ])
            ->bulkActions([

            ])
            ->emptyStateActions([

            ]);
    }
}
