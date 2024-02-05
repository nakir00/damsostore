<?php

namespace App\Models;

use Coderflex\Laravisit\Concerns\CanVisit;
use Coderflex\Laravisit\Concerns\HasVisits;
use App\Notifications\customVerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser,MustVerifyEmail,CanVisit
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles, HasVisits;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
        'social_id',
        'social_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return in_array(auth()->user()->role ,['admin','assistant']);
    }

    public static function canAccesdDashboard(): bool
    {
        return in_array(auth()->user()->role ,['admin','assistant']);
    }

    public static function isAdmin(): bool
    {
        return auth()->user()->role ==='admin';
    }

    public static function isAssistant(): bool
    {
        return auth()->user()->role ==='assistant';
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->teams;
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }

    public function loginTokens():HasOne
    {
    return $this->hasOne(RegisterToken::class);
    }


}
