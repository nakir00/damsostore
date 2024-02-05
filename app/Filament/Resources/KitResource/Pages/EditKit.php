<?php

namespace App\Filament\Resources\KitResource\Pages;

use App\Filament\Resources\KitResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditKit extends EditRecord
{
    protected static string $resource = KitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make("supprimer")
            ->hidden($this->record->status==="Publie")
            ->before(function () {
                $this->record->products()->detach();
            }),
            Action::make('publier')
            ->disabled(count($this->record->products()->get()->toArray())<2)
            ->hidden($this->record->status==="Publie")
            ->color('success')
            ->requiresConfirmation()
            ->action(function (){
                $this->record->status="Publie";
                $this-> record ->collection_group_id=3;
                $this->record->save();
                Notification::make()
                ->title('publié avec succés')
                ->success()
                ->seconds(8)
                ->body('le produit est desormais accéssible directement')
                ->send();
            }),
            Action::make('cacher')
            ->hidden($this->record->status==="cache"||$this->record->status==="enPreparation")
            ->color('danger')
            ->requiresConfirmation()
            ->action(function (){
                $this->record->status="cache";
                $this->record->save();
                Notification::make()
                ->title('caché avec succés')
                ->success()
                ->seconds(8)
                ->body('le produit est invisible directement')
                ->send();
            }),
        ];
    }
}
