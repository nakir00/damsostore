<?php

namespace App\Filament\Resources\CollectionGroupResource\Pages;

use App\Filament\Resources\CollectionGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCollectionGroups extends ListRecords
{
    protected static string $resource = CollectionGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
