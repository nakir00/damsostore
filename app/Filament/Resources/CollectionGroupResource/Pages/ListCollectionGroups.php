<?php

namespace App\Filament\Resources\CollectionGroupResource\Pages;

use App\Filament\Resources\CollectionGroupResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCollectionGroups extends ListRecords
{
    protected static string $resource = CollectionGroupResource::class;

    public function mount(): void
    {
        abort_unless(User::isAdmin(), 403);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
