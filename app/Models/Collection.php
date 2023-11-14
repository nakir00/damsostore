<?php

namespace App\Models;

use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\Tags\HasTags;

/* use Kalnoy\Nestedset\NodeTrait; */

/**
 * @property int $id
 * @property int $featured_image_id
 * @property int $collection_group_id
 * @property string $name
 * @property ?int $parent_id
 * @property string $type
 * @property array description
 * @property ?array $attribute_data
 * @property string $sort
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 * @property ?\Illuminate\Support\Carbon $deleted_at
 */
class Collection extends Model
{
    use HasFactory;
    use HasTags;
    /**
     * Define which attributes should be cast.
     *
     * @var array
     */
    protected $casts = [
        /* 'attribute_data' => AsAttributeData::class, */
    ];

    protected $guarded = [];

    /**
     * Return a new factory instance for the model.
     */
    /* protected static function newFactory(): CollectionFactory
    {
        return CollectionFactory::new();
    } */

    /**
     * Return the group relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(CollectionGroup::class, 'collection_group_id');
    }

    /**
     * Return the parent collection relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent():BelongsTo{
        return $this->belongsTo(Collection::class);
    }

    /**
     * Return the child collection relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function enfants():HasMany
    {
        return $this->hasMany(Collection::class);
    }

    /**
     * checks if the collection has a parent collection
     *
     * @return bool
     */
    public function hasParent():bool
    {
        return $this->parent_id?true:false;
    }

    public function productSlider(): MorphOne
    {
        return $this->morphOne(ProductSlider::class, 'collectionable');
    }

    public function collectionSlider(): MorphOne
    {
        return $this->morphOne(collectionsSlider::class, 'collectionable');
    }

    /**
     * Return the featured image relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */



/*     public function scopeInGroup(Builder $builder, $id)
    {
        return $builder->where('collection_group_id', $id);
    }
 */
    /**
     * Return the products relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            "collection_product"
        )->withPivot([
            'position',
        ])->withTimestamps()->orderByPivot('position');
    }


    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'featured_image_id', 'id');
    }

    /**
     * Get the translated name of ancestor collections.
     *
     * @return Illuminate\Support\Collection
     */
/*     public function getBreadcrumbAttribute()
    {
        return $this->ancestors->map(function ($ancestor) {
            return $ancestor->translateAttribute('name');
        });
    }
 */
    /**
     * Return the customer groups relationship.
     */
    /* public function customerGroups(): BelongsToMany
    {


        return $this->belongsToMany(
            CustomerGroup::class,
            "collection_customer_group"
        )->withPivot([
            'visible',
            'enabled',
            'starts_at',
            'ends_at',
        ])->withTimestamps();
    } */
}
