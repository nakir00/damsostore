<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use App\Models\Page;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditArticle extends EditRecord
{
    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            RestoreAction::make(),
            Action::make('publier')
            ->disabled($this->record->page_id===null)
            ->hidden($this->record->status==="Publie")
            ->color('success')
            ->requiresConfirmation()
            ->action(function (){
                $this->record->status="Publie";
                $this->record->author_id=Auth::user()->id;
                $this->record->url=$this->record->page()->get()->first()->url();
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
            Action::make('Attacher Page')
            ->fillForm(fn (): array => [
                'page' => $this->record->page_id,
                ])
            ->form([
                Select::make('page')
                ->options(fn()=>Page::query()->get()->pluck('title', 'id'))
                ->searchable()
            ])->action(function (array $data): void {
                $this->record->page_id=$data['page'];
                $this->record->save();
                Notification::make()
                ->title('Page attribuée avec succés')
                ->success()
                ->seconds(8)
                ->send();
            })
        ];
    }
}
