<?php

namespace App\Livewire\Admin\Parametres\Marque\Components;

use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Models\Marque;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\InputField;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\WithFileUploads;
use Livewire\Component;

class CreateMarqueForm extends Component implements HasForms
{

    use InteractsWithForms,WithFileUploads;

    public Marque $marque;

    public ?array $data = [];



    public function mount(): void
    {
        $this->form->fill();
    }

    public function createMarque(){



        $marque = Marque::create($this->data);
        $pic= reset($this->data['logo']);
        $marque->addMedia($pic->getRealPath())->toMediaCollection('image');
        Notification::make()
            ->title("marque crée avec succés")
            ->success()
            ->send();


        //$post = Marque::create($this->data);
        /* collect($this->images)->each(fn($image) =>
        $post->addMedia($image->getRealPath())->toMediaCollection('images')); */

            /* $med=(array)reset($this->data['logo']);
             $media = new Media();
            $media->addMedia($med['realPath'])
            ->toMediaCollection('images');
            $media->save(); */
       /*  $marque-> */

        //$marque->addMedia($pathToFile)->toMediaCollection();
        //dd($marque);
        // Save the relationships from the form to the post after it is created.
        //$this->form->model($post)->saveRelationships();

    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                SpatieMediaLibraryFileUpload::make('logo')->disk('local'),
                TextInput::make('name')
                    ->label('nom de la marque')
                    ->required(),
                // ...
            ])
            ->statePath('data')
            ->model(Marque::class);
    }

    public function render()
    {
        return view('livewire.admin.parametres.marque.components.create-marque-form');
    }
}
