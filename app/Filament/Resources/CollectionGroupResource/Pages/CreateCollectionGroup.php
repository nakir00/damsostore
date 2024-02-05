<?php

namespace App\Filament\Resources\CollectionGroupResource\Pages;

use App\Filament\Resources\CollectionGroupResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCollectionGroup extends CreateRecord
{
    protected static string $resource = CollectionGroupResource::class;

    public function mount(): void
    {
        abort_unless(User::isAdmin(), 403);
    }
}
