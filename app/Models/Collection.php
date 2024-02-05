<?php

namespace App\Models;

use Coderflex\Laravisit\Concerns\CanVisit;
use Coderflex\Laravisit\Concerns\HasVisits;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;
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
class Collection extends Model implements CanVisit, Sitemapable
{
    use HasFactory;
    use HasTags;
    use HasVisits;
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

    public function discounts():BelongsToMany
    {
        return $this->belongsToMany(Discount::class,"collection_discount")
        ->where('coupon',null)
        ->whereNotNull('starts_at')
        ->where('starts_at', '<=', now())
        ->where(function ($query) {
            $query->whereNull('ends_at')
                ->orWhere('ends_at', '>', now());
        });
    }

    /**
     * Return the Sitemap tag for the product
     *
     * @return Spatie\Sitemap\Tags\Url
     */
    public function toSitemapTag(): Url | string | array
    {
        $image=$this->featuredImage()->get();
        if($image->isEmpty())
        {
            return Url::create(route('collection', ['slug'=>$this->slug]))
            ->setLastModificationDate(Carbon::create($this->updated_at))
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
            ->setPriority(0.1);
        }else {
            $image=$image->first()->toArray();
            return Url::create(route('collection', ['slug'=>$this->slug]))
            ->addImage($image['url'],title:$image['alt']??"")
            ->setLastModificationDate(Carbon::create($this->updated_at))
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
            ->setPriority(0.1);
        }
        // Return with fine-grained control:

    }

    /**
     * returns the seo data
     *
     * @return Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function seoData(): MorphOne
    {
        return $this->morphOne(SeoInfo::class, 'seoable');
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
