<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\RegisterToken;
use App\Models\User;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Notification;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    public function mount(): void
    {
        abort_unless(User::isAdmin(), 403);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Ajouter Staff')//annule
            ->form([
                TextInput::make('email')
                ->label('adresse email')
                ->email()
                ->required()
                ->autocomplete()
                ->autofocus(),
                Select::make('role')
                ->required()
                ->options([
                    'assistant' => 'Assistant',
                    'admin' => 'Admin',
                ])
            ])
            ->action(function (array $data):void{
                RegisterToken::make($data['email'],$data['role']);
            }),
        ];
    }
}
