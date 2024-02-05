<?php

namespace App\Models;

use App\Models\Page as ModelsPage;
use Awcodes\Curator\Curations\ThumbnailPreset;
use Awcodes\Curator\Models\Media;
use Coderflex\Laravisit\Concerns\CanVisit;
use Coderflex\Laravisit\Concerns\HasVisits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Tags\HasTags;
use Z3d0X\FilamentFabricator\Models\Page;

class Article extends Model implements CanVisit
{
    use HasTags;
    use HasVisits;
    use SoftDeletes;
    use HasFactory;

    protected $guarded = [];

    /**
     * Return the media relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'featured_image_id', 'id');
    }

    /**
     * Return the Page relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(ModelsPage::class);
    }

    /**
     * Return the media miniature relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function miniatureImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'miniature_image_id', 'id');
    }
}
