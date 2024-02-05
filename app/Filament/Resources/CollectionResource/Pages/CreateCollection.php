<?php

namespace App\Filament\Resources\CollectionResource\Pages;

use App\Filament\Resources\CollectionResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCollection extends CreateRecord
{
    protected static string $resource = CollectionResource::class;

    public function mount(): void
    {
        abort_unless(User::isAdmin(), 403);
    }
}
