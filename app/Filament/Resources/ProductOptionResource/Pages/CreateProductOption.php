<?php

namespace App\Filament\Resources\ProductOptionResource\Pages;

use App\Filament\Resources\ProductOptionResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductOption extends CreateRecord
{
    protected static string $resource = ProductOptionResource::class;

    public function mount(): void
    {
        abort_unless(User::isAdmin(), 403);
    }
}
