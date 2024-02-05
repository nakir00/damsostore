<?php

namespace App\Models;

use App\Notifications\RegisterLinkNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $email
 * @property string $role
 * @property string $token
 * @property ?\Illuminate\Support\Carbon $consumed_at
 * @property ?\Illuminate\Support\Carbon $expires_at
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class RegisterToken extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $dates = [
        'expires_at', 'consumed_at',
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function make($email,$role)
    {
        $plaintext = Str::random(32);
        RegisterToken::create([
        'token' => $hash=hash('sha256', $plaintext),
        'email'=>$email,
        'role'=>$role,
        'expires_at' => now()->addDay(3),
        ]);

        Notification::route('mail',$email)->notify(new RegisterLinkNotification($role,$hash));
    }

    
}
