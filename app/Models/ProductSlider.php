<?php

namespace App\Models;

use app\trait\InteractsWithHome;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ProductSlider extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function collectionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function home(): BelongsTo
    {
        return $this->belongsTo(Home::class);
    }
}
