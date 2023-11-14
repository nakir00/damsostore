<?php
namespace app\trait;

use App\Models\home;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait InteractsWithHome
{
    public function home(): BelongsTo
    {
        return $this->belongsTo(home::class);
    }
}
