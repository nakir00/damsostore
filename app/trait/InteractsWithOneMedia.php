<?php

namespace app\trait;

use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait InteractsWithOneMedia
{
    /**
     * Return the featured image relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'featured_image_id', 'id');
    }
}
