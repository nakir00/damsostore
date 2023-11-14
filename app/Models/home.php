<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class home extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function topSliders(): HasMany
    {
        return $this->hasMany(topSlider::class);
    }

    public function infoSliders(): HasMany
    {
        return $this->hasMany(infoSlider::class);
    }

    public function collectionSliders(): HasMany
    {
        return $this->hasMany(collectionsSlider::class);
    }

    public function productSliders(): HasMany
    {
        return $this->hasMany(ProductSlider::class);
    }

}
