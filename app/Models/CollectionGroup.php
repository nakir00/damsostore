<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
/* use Lunar\Base\Traits\HasMacros;
use Lunar\Database\Factories\CollectionGroupFactory */;

/**
 * @property int $id
 * @property string $name
 * @property string $handle
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class CollectionGroup extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;


    protected $guarded = [];

    /**
     * Return a new factory instance for the model.
     */
    /* protected static function newFactory(): CollectionGroupFactory
    {
        return CollectionGroupFactory::new();
    } */

    public function collections():HasMany
    {
        return $this->hasMany(Collection::class);
    }
}
