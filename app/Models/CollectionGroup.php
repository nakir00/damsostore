<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Awcodes\Curator\Models\Media;

/* use Lunar\Base\Traits\HasMacros;
use Lunar\Database\Factories\CollectionGroupFactory */;

/**
 * @property int $id
 * @property string $name
 * @property int $featured_image_id
 * @property int $product_option_id
 * @property array $attribute_data
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 * @property ?\Illuminate\Support\Carbon $deleted_at
 */
class CollectionGroup extends Model
{
    use HasFactory;


    protected $guarded = [];

    /**
     * Return a new factory instance for the model.
     */
    /* protected static function newFactory(): CollectionGroupFactory
    {
        return CollectionGroupFactory::new();
    } */

    /**
     * Return all the collections of the group relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function collections():HasMany
    {
        return $this->hasMany(Collection::class);
    }

    /**
     * Return the product option relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productOption():BelongsTo
    {
        return $this->belongsTo(ProductOption::class,'product_option_id','id');

    }

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
