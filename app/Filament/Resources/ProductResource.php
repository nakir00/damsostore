<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\ProductType;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;


class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

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
                CuratorPicker::make('images')
                    ->multiple()
                    ->relationship('images', 'id')
                    ->orderColumn('order'),
                TextInput::make('name')->required()->live(debounce: 1000)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                TextInput::make('slug')->prefix('product/'),
                TextInput::make('old_price')->numeric()->placeholder("25.000")->required(),
                Select::make('availability')->options(["disponible"=>'Disponible','precommande'=> "Pre-Commande","epuise"=>"épuisé", ])->default('disponible'),
                Repeater::make('attribute_data')->label('arbre des colections')
                    ->schema([
                        TextInput::make('collection_name')->label('nom')->disabled(),
                    ])
                    ->addable(false)
                    ->reorderable(false)
                    ->deletable(false),
                Repeater::make('description')
                    ->schema([
                        TextInput::make('text')->label('info'),
                        RichEditor::make('value')->label('text')->toolbarButtons([
                            'blockquote',
                            'bold',
                            'bulletList',
                            'italic',
                            'link',
                            'orderedList',
                            'redo',
                            'strike',
                            'underline',
                            'undo',
                        ])

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                CuratorColumn::make('images')
                    ->size(40)
                    ->ring(2) // options 0,1,2,4
                    ->overlap(4) // options 0,2,3,4
                    ->limit(3),
                TextColumn::make('name')
                    ->searchable()
                    ->label("nom"),
                SelectColumn::make('availability')
                    ->options(["disponible"=>'Disponible','precommande'=> "Pre-Commande","epuise"=>"épuisé", ])
                    ->sortable(),
                TextInputColumn::make('old_price')
                    ->type('number')
                    ->label("prix général")
                    ->afterStateUpdated(function ($record, $state) {
                        // Runs after the state is saved to the database.
                        $record->variants()->delete();
                        $record->GenerateVariants();
                        Notification::make()
                        ->title('variantes générées avec succés')
                        ->success()
                        ->seconds(8)
                        ->body('vous pouvez gérer les prix et la disponibilité des variantes maintenant ')
                        ->send();
                    })
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'enPreparation' => 'gray',
                        'cache' => 'danger',
                        'Publie' => 'success'
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
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
            RelationManagers\VariantsRelationManager::class,
            RelationManagers\KitsRelationManager::class,
            RelationManagers\CollectionsRelationManager::class,
            RelationManagers\AssociationsRelationManager::class,
        ];
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->with(['featured_image', 'product_pictures']);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->role==='admin';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
