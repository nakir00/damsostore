<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoInfo extends Model
{
    use HasFactory;

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}
