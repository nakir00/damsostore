<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;

/**
 * @property int $id
 * @property \Illuminate\Support\Collection $name
 * @property \Illuminate\Support\Collection $label
 * @property int $position
 * @property ?string $handle
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class ProductOption extends Model //implements SpatieHasMedia
{
    use HasFactory;
   /*  use HasMacros;
    use HasMedia;
    use HasTranslations;
    use Searchable; */

    /**
     * Define which attributes should be cast.
     *
     * @var array
     */
    protected $casts = [
        /* 'name' => AsCollection::class,
        'label' => AsCollection::class, */
    ];

    /**
     * Return a new factory instance for the model.
     */
/*     protected static function newFactory(): ProductOptionFactory
    {
        return ProductOptionFactory::new();
    } */

    /* public function getNameAttribute($value)
    {
        return json_decode($value);
    } */

   /*  protected function setNameAttribute($value)
    {
        $this->attributes['name'] = json_encode($value);
    } */

/*     protected function label(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => json_decode($value),
            set: fn ($value) => json_encode($value),
        );
    } */

    /**
     * Define which attributes should be
     * protected from mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the values.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<ProductOptionValue>
     */
    public function values()
    {
        return $this->hasMany(ProductOptionValue::class);
    }
}
