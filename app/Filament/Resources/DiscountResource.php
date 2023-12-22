<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiscountResource\Pages;
use App\Filament\Resources\DiscountResource\RelationManagers;
use App\Models\Discount;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Relationship;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class DiscountResource extends Resource
{
    protected static ?string $model = Discount::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required()->live(debounce: 1000)->label('nom')
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('handle', Str::slug($state))),
                TextInput::make('handle')->prefix('identifiant')->label('identifiant'),
                Forms\Components\DateTimePicker::make('starts_at')
                    ->required(),
                Forms\Components\DateTimePicker::make('ends_at'),
                Select::make('priority')
                    ->options([
                        1 => 'Faible',
                        5 => 'Moyenne',
                        10 => 'Forte',
                    ])
                    ->required()
                    ->selectablePlaceholder(false)
                    ->default(1),
                Forms\Components\Toggle::make('stop')
                    ->required(),
                TextInput::make('coupon')->maxLength(255)->regex('/^[a-z0-9_\-]+$/')->hint('en minuscule et sans espaces'),
                Forms\Components\TextInput::make('max_uses')
                    ->numeric(),
                TextInput::make('data.min_prices')
                    ->label('somme minimale')
                    ->numeric(),
                TextInput::make('max_uses_per_user')
                    ->numeric(),
                Select::make('type')
                    ->options([
                        'amount' => 'Amount',
                    ])
                    ->default('amount')
                    ->selectablePlaceholder(false)
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (Select $component) => $component
                        ->getContainer()
                        ->getComponent('typeDiscount')
                        ->getChildComponentContainer()
                        ->fill()),

                Grid::make(2)
                    ->schema(fn (Get $get): array => match ($get('type')) {
                        'amount' => [
                            Select::make('data.type')
                                ->label('Par :')
                                ->options([
                                    'percentage' => 'Pourcentage',
                                    'fixed_values'=>'Prix diminué'
                                ])
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn (Select $component) => $component
                                    ->getContainer()
                                    ->getComponent('typeAmount')
                                    ->getChildComponentContainer()
                                    ->fill()),
                            Grid::make(2)
                                ->schema(fn (Get $get): array => match ($get('data.type')) {
                                    'percentage' => [
                                        TextInput::make('data.percentage')
                                            ->label('Pourcentage')
                                            ->numeric()
                                            ->regex('/^(100|\d{1,2})$/')
                                            ->suffix('%'),
                                        ],
                                    'fixed_values' => [
                                        TextInput::make('data.fixed_values')
                                            ->label('valeur de remise')
                                            ->numeric()
                                            ->suffix('franc cfa'),
                                    ],
                                    default => [],
                                })->key('typeAmount'),
                        ],
                        default => [],
                    })
                    ->key('typeDiscount'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('handle')
                    ->searchable(),

                Tables\Columns\TextColumn::make('starts_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ends_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('priority')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('stop')
                    ->boolean()
                    ->default(true),
                Tables\Columns\TextColumn::make('coupon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('uses')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('max_uses')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('max_uses_per_user')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('restriction')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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
            RelationManagers\ProductsRelationManager::class,
            RelationManagers\KitsRelationManager::class,
            RelationManagers\CollectionsRelationManager::class,
            RelationManagers\GroupsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDiscounts::route('/'),
            'create' => Pages\CreateDiscount::route('/create'),
            'edit' => Pages\EditDiscount::route('/{record}/edit'),
        ];
    }
}
