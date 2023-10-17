<?php

namespace App\Livewire;

use App\Models\Post;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\InputField;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class CreateRole extends Component implements HasForms
{

    use InteractsWithForms;

    public Role $role;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('nom du role')
                    ->required(),
                //MarkdownEditor::make('content'),
                // ...
            ])
            ->statePath('data')
            ->model(Role::class);
    }

    public function create(): void
    {
        $this->role = Role::create($this->data);


    }

    public function render()
    {
        return view('livewire.userManagement.roles.create-role');
    }
}
