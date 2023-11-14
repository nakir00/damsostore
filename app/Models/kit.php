<?php

namespace App\Models;

use Awcodes\Curator\Models\Media;
use Spatie\Tags\HasTags;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
class kit extends Model
{
    use HasFactory;
    use HasTags;
    use SoftDeletes;

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
}
