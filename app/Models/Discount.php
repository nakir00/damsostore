<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $handle
 * @property ?string $coupon
 * @property string $type
 * @property \Illuminate\Support\Carbon $starts_at
 * @property \Illuminate\Support\Carbon $ends_at
 * @property int $uses
 * @property ?int $max_uses
 * @property int $priority
 * @property bool $stop
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class Discount extends Model
{
    use
        HasFactory
        ;

    protected $guarded = [];

    /**
     * Define which attributes should be cast.
     *
     * @var array
     */
    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'data' => 'array',
    ];

    /**
     * Return a new factory instance for the model.
     */
    /* protected static function newFactory(): DiscountFactory
    {
        return DiscountFactory::new();
    } */

    public function users()
    {

        return $this->belongsToMany(
            User::class,
            "discount_user"
        )->withTimestamps();
    }

    /**
     * Return the purchasables relationship.
     *
     * @return HasMany
     */
    public function purchasables()
    {
        
        return $this->hasMany(DiscountPurchasable::class);
    }

    public function purchasableConditions()
    {
        return $this->hasMany(DiscountPurchasable::class)->whereType('condition');
    }

    public function purchasableLimitations()
    {
        return $this->hasMany(DiscountPurchasable::class)->whereType('limitation');
    }

    public function purchasableRewards()
    {
        return $this->hasMany(DiscountPurchasable::class)->whereType('reward');
    }

    public function getType()
    {
        return app($this->type)->with($this);
    }

    /**
     * Return the collections relationship.
     *
     * @return BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(
            CollectionGroup::class,
            "group_discount"
        )->withTimestamps();
    }

    /**
     * Return the collections relationship.
     *
     * @return HasMany
     */
    public function collections()
    {
        return $this->belongsToMany(
            Collection::class,
            "collection_discount"
        )->withTimestamps();
    }

    public function Kits(): MorphToMany
    {
        return $this->morphedByMany(kit::class, 'purchasable');
    }

    public function products(): MorphToMany
    {
        return $this->morphedByMany(Product::class, 'purchasable');
    }


    /**
     * Return the active scope.
     *
     * @return Builder
     */
    /* public function scopeActive(Builder $query)
    {
        return $query->whereNotNull('starts_at')
            ->where('starts_at', '<=', now())
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>', now());
            });
    } */

    /**
     * Return the products scope.
     *
     * @return Builder
     */
    /* public function scopeProducts(Builder $query, iterable $productIds = [], string $type = null)
    {
        if (is_array($productIds)) {
            $productIds = collect($productIds);
        }

        return $query->where(
            fn ($subQuery) => $subQuery->whereDoesntHave('purchasables')
                ->orWhereHas('purchasables',
                    fn ($relation) => $relation->whereIn('purchasable_id', $productIds)
                        ->wherePurchasableType(Product::class)
                        ->when(
                            $type,
                            fn ($query) => $query->whereType($type)
                        )
                )
        );
    } */

    /* public function scopeUsable(Builder $query)
    {
        return $query->where(function ($subQuery) {
            $subQuery->whereRaw('uses < max_uses')
                ->orWhereNull('max_uses');
        });
    } */
}
