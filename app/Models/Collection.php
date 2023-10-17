<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/* use Kalnoy\Nestedset\NodeTrait; */

/**
 * @property int $id
 * @property int $collection_group_id
 * @property-read  int $_lft
 * @property-read  int $_rgt
 * @property ?int $parent_id
 * @property string $type
 * @property ?array $attribute_data
 * @property string $sort
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 * @property ?\Illuminate\Support\Carbon $deleted_at
 */
class Collection extends Model implements HasMedia
{
    use
        HasFactory,
        //NodeTrait;
        InteractsWithMedia;
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

    public function parent():BelongsTo{
        return $this->belongsTo(Collection::class);
    }

    public function enfants():HasMany
    {
        return $this->hasMany(Collection::class);
    }

    public function hasParent():bool
    {
        return $this->parent_id?true:false;
    }

    

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
