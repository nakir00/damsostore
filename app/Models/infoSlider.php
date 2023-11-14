<?php

namespace App\Models;

use app\trait\InteractsWithHome;
use app\trait\InteractsWithOneMedia;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class infoSlider extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function home(): BelongsTo
    {
        return $this->belongsTo(home::class);
    }

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'featured_image_id', 'id');
    }
}
