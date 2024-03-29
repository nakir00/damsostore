<?php

namespace App\Models;

use Coderflex\Laravisit\Concerns\CanVisit;
use Coderflex\Laravisit\Concerns\HasVisits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;
use Spatie\Tags\HasTags;

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
class CollectionGroup extends Model implements CanVisit, Sitemapable
{
    use HasFactory;
    use HasTags;
    use HasVisits;


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
     * Return the Sitemap tag for the product
     *
     * @return Spatie\Sitemap\Tags\Url
     */
    public function toSitemapTag(): Url | string | array
    {
        $image=$this->featuredImage()->get();
        // Return with fine-grained control:
        if($image->isEmpty())
        {
            return Url::create(route('collection', ['slug'=>$this->slug,'g']))
                ->setLastModificationDate(Carbon::create($this->updated_at))
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
                ->setPriority(0.1);
        }else {
            $image=$image->first()->toArray();
            return Url::create(route('collection', ['slug'=>$this->slug,'g']))
                ->addImage($image['url'],title:$image['alt']??"")
                ->setLastModificationDate(Carbon::create($this->updated_at))
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
                ->setPriority(0.1);
        }

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
     * Return the product option relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productOption():BelongsTo
    {
        return $this->belongsTo(ProductOption::class,'product_option_id','id');

    }

    public function kits()
    {
        return $this->hasMany(kit::class);
    }

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'featured_image_id', 'id');
    }

    public function discounts():BelongsToMany
    {
        return $this->belongsToMany(Discount::class,"group_discount")
        ->where('coupon',null)
        ->whereNotNull('starts_at')
        ->where('starts_at', '<=', now())
        ->where(function ($query) {
            $query->whereNull('ends_at')
                ->orWhere('ends_at', '>', now());
        });
    }

}
