<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Home extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function topSliders(): HasMany
    {
        return $this->hasMany(TopSlider::class);
    }

    public function infoSliders(): HasMany
    {
        return $this->hasMany(InfoSlider::class);
    }

    public function collectionSliders(): HasMany
    {
        return $this->hasMany(CollectionsSlider::class);
    }

    public function productSliders(): HasMany
    {
        return $this->hasMany(ProductSlider::class);
    }

    /**
     * returns the seo data
     *
     * @return Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function seoData(): MorphOne
    {
        return $this->morphOne(SeoInfo::class, 'seoable');
    }
}
