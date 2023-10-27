<?php

namespace App\Filament\Resources\CollectionResource\Pages;

use App\Filament\Resources\CollectionResource;
use App\Models\Collection;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditCollection extends EditRecord
{
    protected static string $resource = CollectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make('supprimer')
            ->requiresConfirmation()
            ->before(function (Collection $record) {
                $fils=Collection::where('parent_id',$record->id)->get();
                if($record->parent_id==null)
                {
                    foreach($fils as $enfant){
                        $enfant->parent_id=null;
                        $enfant->save();
                    }
                }else{
                    foreach($fils as $enfant){
                        $enfant->parent_id=$record->parent_id;
                        $enfant->save();
                    }
                }
            })
            ->successRedirectUrl(route('filament.admin.resources.collections.index'))
            ->after(function () {
                Notification::make()
                    ->success()
                    ->title('diadieuf way')
                    ->body('collection supprimée avec succés.');
            })
        ];
    }

}
