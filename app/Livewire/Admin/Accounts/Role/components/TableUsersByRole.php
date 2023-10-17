<?php

namespace App\Livewire\Admin\Accounts\Role\Components;

use App\Models\User;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Filament\Tables\Actions\Action;
use Spatie\Permission\Models\Role;
use Livewire\Component;

class TableUsersByRole extends Component implements HasForms, HasTable
{

    use InteractsWithTable;
    use InteractsWithForms;

    public Role $role;

    public function mount(Role $role)
    {
        $this->role = $role;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query()->role($this->role->name))
            ->columns([
                TextColumn::make('name')->label("nom d'utilisateur"),
                TextColumn::make('email')->label("email"),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
                Action::make('edit')
                ->label("voir")
                ->color('gray')
                ->icon('heroicon-o-eye')
               // ->url(fn (): string => route('posts.edit', ['post' => $this->role]))
               //->url(fn (Role $record): string => route('admin.accounts.roles.show', $record))
            ])
            ->headerActions([
                // ...

            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        return view('livewire.admin.accounts.role.components.table-users-by-role');
    }
}
