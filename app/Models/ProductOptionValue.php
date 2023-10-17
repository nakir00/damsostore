<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;

/**
 * @property int $id
 * @property int $product_option_id
 * @property string $name
 * @property int $position
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class ProductOptionValue extends Model// implements SpatieHasMedia
{
    use HasFactory;
/*     use HasMacros;
    use HasMedia;
    use HasTranslations;
 */
    /**
     * Define which attributes should be cast.
     *
     * @var array
     */
    protected $casts = [
       // 'name' => AsCollection::class,
    ];

    /**
     * Return a new factory instance for the model.
     */
   /*  protected static function newFactory(): ProductOptionValueFactory
    {
        return ProductOptionValueFactory::new();
    } */

    /**
     * Define which attributes should be
     * protected from mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    /* protected function setNameAttribute($value)
    {
        $this->attributes['name'] = json_encode($value);
    }

    public function getNameAttribute($value)
    {
        return json_decode($value);
    } */

    public function option()
    {
        return $this->belongsTo(ProductOption::class, 'product_option_id');
    }

    public function variants():BelongsToMany
    {

        return $this->belongsToMany(
            ProductVariant::class,
            "product_option_value_product_variant",
            'value_id',
            'variant_id',
        )->withPivot('old_price','price');
    }
}
