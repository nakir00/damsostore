<?php

namespace App\Models;

use app\Enums\InfoPosition;
use app\trait\InteractsWithHome;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $mobile_image_id
 * @property int $featured_image_id
 * @property int $home_id
 * @property string $button_message
 * @property string $button_link
 * @property string $info
 * @property enum $position
 * @property ?int $order
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class TopSlider extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [];

    public function home(): BelongsTo
    {
        return $this->belongsTo(Home::class);
    }

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'featured_image_id', 'id');
    }

    public function MobileImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'mobile_media_id', 'id');
    }
}
