<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property int $id
 * @property ?int $user_id
 * @property ?int $address_id
 * @property string $status
 * @property int $total
 * @property ?string $notes
 * @property ?\Illuminate\Support\Carbon $date_commande
 * @property ?\Illuminate\Support\Carbon $date_confirmation
 * @property ?\Illuminate\Support\Carbon $date_livraison
 * @property ?\Illuminate\Support\Carbon $placed_at
 * @property ?array $attribute_data
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class Order extends Model
{
    use HasFactory;

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'attribute_data' => 'array',
        'placed_at' => 'datetime',
    ];

    /**
     * {@inheritDoc}
     */
    protected $guarded = [];

    /**
     * Return the lines relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderables()
    {
        return $this->hasMany(Ord::class);
    }


    /**
     * Return the shipping address relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Address():BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    /**
     * Return the customer relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(User::class,'user_id');
    }

        /**
     * Get all of the posts that are assigned this ProductVariant.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function products(): MorphToMany
    {
        return $this->morphedByMany(ProductVariant::class, 'orderable');
    }

    /**
     * Get all of the videos that are assigned this kit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function kits(): MorphToMany
    {
        return $this->morphedByMany(kit::class, 'orderable');
    }

}
