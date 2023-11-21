<?php

namespace App\Models;

use Spatie\Tags\HasTags;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property int $id
 * @property int $product_type_id
 * @property ?int $collection_id
 * @property string $name
 * @property string $slug
 * @property int $old_price
 * @property string $status
 * @property array $description
 * @property array $attribute_data
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 * @property ?\Illuminate\Support\Carbon $deleted_at
 */
class Product extends Model
{
    use HasFactory;
    use HasTags;
    use SoftDeletes;

    /**
     * Return a new factory instance for the model.
     */
    /* protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    } */

    /**
     * Define which attributes should be
     * fillable during mass assignment.
     *
     * @var array
     */
    /*  protected $fillable = [
       'attribute_data',
        'product_type_id',
        'status',
        'brand_id',
    ]; */

    protected $guarded=[];

    /**
     * Define which attributes should be cast.
     *
     * @var array
     */
     protected $casts = [
        'description' => 'array',
        'attribute_data' => 'array',
    ];

    /**
     * Returns the attributes to be stored against this model.
     *
     * @return array
     */
   /*  public function mappedAttributes()
    {
        return $this->productType->mappedAttributes;
    } */

    /**
     * Return the product type relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    /**
     * Return the product images relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
 /*    public function images()
    {
        return $this->media()->where('collection_name', 'images');
    } */

    /**
     * Return the product variants relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Return the product collections relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function collections()
    {
        return $this->belongsToMany(
            Collection::class,
            'collection_product'
        )->withPivot(['position'])->withTimestamps();
    }

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    /**
     * Return the associations relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function associations():BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_associations','product_target_id','product_parent_id')->withPivot('type' ,'times' )->withTimestamps();
    }

    /**
     * Return the associations relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
   /*  public function inverseAssociations()
    {
        return $this->belongsToMany(Product::class, 'product_associations','product_parent_id','product_target_id')->withPivot('type','times')->withTimestamps();
    } */

    public function images()
    {
        return $this->belongsToMany(Media::class, "media_product", 'product_id', 'media_id')->withPivot('order')->orderBy('order')->withTimestamps();
    }

    public function kits():BelongsToMany
    {
        return $this->belongsToMany(kit::class,'kit_product','product_id','kit_id')->withTimestamps();;
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

    /**
     * Get all of the tags for the post.
     */
    public function orders(): MorphToMany
    {
        return $this->morphToMany(Order::class, 'orderable');
    }

    /**
     * Associate a product to another with a type.
     *
     * @param  mixed  $product
     * @param  string  $type
     * @return void
     */
  /*   public function associate($product, $type)
    {
        Associate::dispatch($this, $product, $type);
    } */

    /**
     * Dissociate a product to another with a type.
     *
     * @param  mixed  $product
     * @param  string  $type
     * @return void
     */
   /*  public function dissociate($product, $type = null)
    {
        Dissociate::dispatch($this, $product, $type);
    } */

    /**
     * Return the customer groups relationship.
     */
   /*  public function customerGroups(): BelongsToMany
    {
        $prefix = config('lunar.database.table_prefix');

        return $this->belongsToMany(
            CustomerGroup::class,
            "{$prefix}customer_group_product"
        )->withPivot([
            'purchasable',
            'visible',
            'enabled',
            'starts_at',
            'ends_at',
        ])->withTimestamps();
    } */

    /**
     * Return the brand relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
/*     public function brand()
    {
        return $this->belongsTo(Brand::class);
    } */

    /**
     * Apply the status scope.
     *
     * @param  string  $status
     * @return Builder
     */
 /*    public function scopeStatus(Builder $query, $status)
    {
        return $query->whereStatus($status);
    } */

    /**
     * Return the prices relationship.
     *
     * @return HasManyThrough
     */
    /* public function prices()
    {
        return $this->hasManyThrough(
            Price::class,
            ProductVariant::class,
            'product_id',
            'priceable_id'
        )->wherePriceableType(ProductVariant::class);
    } */



}
