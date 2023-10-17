<?php

namespace App\Livewire\Admin\Parametres\Marque\Components;

use App\Models\Marque;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Filament\Tables\Actions\Action;

use Livewire\Component;

class TableMarque extends Component implements HasForms, HasTable
{

    use InteractsWithTable;
    use InteractsWithForms;

    public Marque $marque;


    public function mount()
    {
        $this->marque = new Marque();
    }

    public function table(Table $table): Table
    {

        return $table
            ->query(Marque::query())

            ->columns([
                ImageColumn::make('logo')->image(function ($model) {
                    return asset('images/' . $model->image);
                }),
                TextColumn::make('name')->label("nom du Role"),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
                Action::make('edit')
                ->label("voir")
                ->color('gray')
                ->icon('heroicon-o-eye')
               // ->url(fn (): string => route('posts.edit', ['post' => $this->role]))
            ])
            ->headerActions([
                // ...

            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        return view('livewire.admin.parametres.marque.components.table-marque');
    }
}
