<?php

namespace App\Filament\Resources\KitResource\Pages;

use App\Filament\Resources\KitResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateKit extends CreateRecord
{
    protected static string $resource = KitResource::class;

    public function mount(): void
    {
        abort_unless(User::isAdmin(), 403);
    }
}
