<?php

namespace App\Filament\Resources\KitResource\Pages;

use App\Filament\Resources\KitResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKit extends ViewRecord
{
    protected static string $resource = KitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
