<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property int $id
 * @property int $order_id
 * @property string $orderable_type
 * @property int $orderable_id
 * @property string $type
 * @property string $description
 * @property ?string $option
 * @property int $unit_price
 * @property int $unit_quantity
 * @property int $quantity
 * @property int $total
 * @property ?string $notes
 * @property ?array $meta
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class Orderable extends Model
{
    use HasFactory;

    /**
     * Define which attributes should be
     * protected from mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'unit_quantity' => 'integer',
        'quantity' => 'integer',
        'meta' => 'array',
        'unit_price' => 'integer',
        'total' => 'integer',
    ];

    /**
     * Return the order relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }





}
