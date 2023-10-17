<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
/* use Lunar\Base\Casts\AsAttributeData;
use Lunar\Base\Traits\HasAttributes;
use Lunar\Base\Traits\HasMacros;
use Lunar\Base\Traits\HasMedia;
use Lunar\Base\Traits\HasTranslations;
use Lunar\Base\Traits\HasUrls;
use Lunar\Base\Traits\LogsActivity;
use Lunar\Base\Traits\Searchable;
use Lunar\Database\Factories\BrandFactory; */
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property string $name
 * @property ?array $attribute_data
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class Brand extends Model implements HasMedia
{
    use //HasAttributes,
        HasFactory,
        InteractsWithMedia;

        /* HasMacros,
        HasMedia,
        HasTranslations,
        HasUrls,
        LogsActivity,
        Searchable; */

    /**
     * {@inheritDoc}
     */
    protected $guarded = [];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
       // 'attribute_data' => AsAttributeData::class,
    ];

    /**
     * Return a new factory instance for the model.
     */
/*     protected static function newFactory(): BrandFactory
    {
        return BrandFactory::new();
    } */

    /**
     * Get the mapped attributes relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
/*     public function mappedAttributes()
    {
        $prefix = config('lunar.database.table_prefix');

        return $this->morphToMany(
            Attribute::class,
            'attributable',
            "{$prefix}attributables"
        )->withTimestamps();
    } */

    /**
     * Return the product relationship.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

}