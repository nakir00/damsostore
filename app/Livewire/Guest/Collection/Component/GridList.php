<?php

namespace App\Livewire\Guest\Collection\Component;

use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;

class GridList extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public $products;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required(),
                    TextInput::make('name')
                    ->required(),
                    TextInput::make('text')
                    ->required(),

                // ...
            ])
            ->statePath('data');
    }

    public function render()
    {
        return view('livewire.guest.collection.component.grid-list');
    }
}
