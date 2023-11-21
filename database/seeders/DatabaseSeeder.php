<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\CollectionGroup;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use App\Models\ProductType;
use App\Models\User;
use Database\Factories\ProductFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

         User::factory()->create([
            'name' => 'naby',
            'email' => 'naby@naby.com',
            'password' => bcrypt('1234567890')
        ]);

        ProductType::factory()->create([
            'name'=>'Stock'
        ]);

        $Pointure=ProductOption::factory()->create([
            'name'=>'Pointure'
        ]);

        for ($i=35; $i < 48; $i++) {
            ProductOptionValue::factory()->create([
                'name'=>"$i",
                'active'=>true,
                'product_option_id'=>$Pointure->id
            ]);
        }

        $Pointure=ProductOption::factory()->create([
            'name'=>'sans option'
        ]);
    }
}
