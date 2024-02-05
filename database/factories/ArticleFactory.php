<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Testing\Fakes\Fake;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'featured_image_id'=> 1,
            'miniature_image_id'=> 1,
            'page_id'=>1,
            'author_id'=>1,
            'url'=>'http://127.0.0.1:8008/client',
            'title'=>fake('fr_FR')->sentence(),
            'subtitle'=>fake('fr_FR')->sentence(4),
            'description'=>fake('fr_FR')->sentences(asText:true)
        ];
    }
}
