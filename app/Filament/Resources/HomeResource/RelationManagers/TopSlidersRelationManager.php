<?php

namespace App\Filament\Resources\HomeResource\RelationManagers;

use App\Enums\InfoPosition;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TopSlidersRelationManager extends RelationManager
{
    protected static string $relationship = 'topSliders';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                CuratorPicker::make('featured_image_id')
                    ->relationship('featuredImage', 'id')->required(),
                TextInput::make('button_link')->required()->maxLength(50)->unique()->dehydrateStateUsing(fn (string $state): string =>$state = str_replace([' ',"'","_"], '-', $state)),
                TextInput::make('button_message')
                    ->required()
                    ->maxLength(255),
                Select::make('position')
                    ->options(['center'=> 'Centre',
                    'N'=>'Nord',
                    'S'=>'Sud',
                    'E'=>'Est',
                    'W'=>'Ouest',
                    'NE'=>'Nord Est',
                    'NW'=>'Nord Ouest',
                    'SE'=>'Sud Est',
                    'SW'=>'Sud Ouest'])->required(),
                RichEditor::make('info')->required()
                ->toolbarButtons([
                    'bold',
                    'bulletList',
                    'codeBlock',
                    'h2',
                    'h3',
                    'italic',
                    'orderedList',
                    'redo',
                    'strike',
                    'underline',
                    'undo',
                ]),
                ColorPicker::make('primary')->required(),
                ColorPicker::make('secondary')->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                CuratorColumn::make('featured_image_id')->size(50)->rounded(),
                ColorColumn::make('primary'),
                ColorColumn::make('secondary'),
                ToggleColumn::make('active'),
                TextInputColumn::make('button_message'),
                SelectColumn::make('position')
                    ->options(['center'=> 'Centre',
                    'N'=>'Nord',
                    'S'=>'Sud',
                    'E'=>'Est',
                    'W'=>'Ouest',
                    'NE'=>'Nord Est',
                    'NW'=>'Nord Ouest',
                    'SE'=>'Sud Est',
                    'SW'=>'Sud Ouest'])
                    ->rules(['required']),


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
