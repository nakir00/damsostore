<?php

namespace App\Models;

use Coderflex\Laravisit\Concerns\CanVisit;
use Coderflex\Laravisit\Concerns\HasVisits;
use Awcodes\Curator\Models\Media;
use Spatie\Tags\HasTags;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property int $id
 * @property int $featured_image_id
 * @property ?int $collection_group_id
 * @property string $name
 * @property int $total_price
 * @property int $price
 * @property string $status
 * @property array $description
 * @property array $attribute_data
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 * @property ?\Illuminate\Support\Carbon $deleted_at
 */
class Kit extends Model implements CanVisit
{
    use HasFactory;
    use HasTags;
    use SoftDeletes;
    use HasVisits;

    protected $guarded=[];

    protected $casts = [
        'description' => 'array',
        'attribute_data' => 'array',
    ];

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'featured_image_id', 'id');
    }

    public function collectionGroup()
    {
        return $this->belongsTo(CollectionGroup::class);
    }

    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'kit_product'
        )->withTimestamps();
    }

    /**
     * Get all of the tags for the post.
     */
    public function orders(): MorphToMany
    {
        return $this->morphToMany(Order::class, 'orderables');
    }

    public function discounts(): MorphToMany
    {
        return $this->morphToMany(Discount::class, 'purchasable')
        ->whereNull('coupon')
        ->whereNotNull('starts_at')
        ->where('starts_at', '<=', now())
        ->where(function ($query) {
            $query->whereNull('ends_at')
                ->orWhere('ends_at', '>', now());
        })->withTimestamps();
    }

    public function coupons(): MorphToMany
    {
        return $this->morphToMany(Discount::class, 'purchasable')
        ->whereNotNull('coupon')
        ->whereNotNull('starts_at')
        ->where('starts_at', '<=', now())
        ->where(function ($query) {
            $query->whereNull('ends_at')
                ->orWhere('ends_at', '>', now());
        })->withTimestamps();
    }

    public function orderable(): MorphMany
    {
        return $this->morphMany(Orderable::class, 'orderable');
    }

}
