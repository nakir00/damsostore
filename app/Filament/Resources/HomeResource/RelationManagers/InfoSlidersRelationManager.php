<?php

namespace App\Filament\Resources\HomeResource\RelationManagers;

use Awcodes\Curator\Components\Forms\CuratorPicker;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InfoSlidersRelationManager extends RelationManager
{
    protected static string $relationship = 'infoSliders';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                CuratorPicker::make('featured_image_id')
                ->relationship('featuredImage', 'id')->required(),
                TextInput::make('info')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                CuratorColumn::make('featured_image_id')->size(50)->rounded(),
                TextInputColumn::make('info'),
                ToggleColumn::make('active'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
}
