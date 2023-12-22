<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Collection;
use App\Models\CollectionGroup;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use App\Models\ProductType;
use App\Models\User;
use Database\Factories\ProductFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
            'password' => bcrypt('1234567890'),
            'role' => 'admin'
        ]);

        User::factory()->create([
            'name' => 'ahmad',
            'email' => 'damsostore@gmail.com',
            'password' => bcrypt('1234567890'),
            'role' => 'admin'
        ]);

        $stock=ProductType::factory()->create([
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

        $sansOption=ProductOption::factory()->create([
            'name'=>'sans option'
        ]);

        $sneakerCollectionGroup=CollectionGroup::factory()->create([
            'name'=>'Sneakers',
            'slug'=>Str::slug('Sneakers'),
            'product_option_id'=>$Pointure->id,
            'onNavBar'=>true,
        ]);

        $accessoiresCollectionGroup=CollectionGroup::factory()->create([
            'name'=>'Accessoires',
            'slug'=>Str::slug('Accessoires'),
            'product_option_id'=>$sansOption->id,
            'onNavBar'=>true,
        ]);

        $kitsCollectionGroup=CollectionGroup::factory()->create([
            'name'=>'Kits',
            'slug'=>Str::slug('Kits'),
            'product_option_id'=>null,
            'onNavBar'=>true,
        ]);

        $freeCollectionGroup=CollectionGroup::factory()->create([
            'name'=>'free',
            'slug'=>Str::slug('free'),
            'product_option_id'=>null,
            'onNavBar'=>false,
        ]);

        $dataCollections=[
                            'VANS',
                            'AIR FORCE ONE',
                            'AIR JORDAN 3',
                            'AIR JORDAN 1',
                            'ADIDAS YEEZY 700V3',
                            'AIR JORDAN 4',
                            'DUNK LOW',
                            'AIR FORCE ONE LOW',
                            'AIR FORCE CLASSIC',
                            'AIR FORCE ONE HIGH',
                            'Nike x Sacai',
                            'AIR JORDAN 6',
                            'NEW BALANCE',
                            'Non classé',
                            'LVMH',
                            'CONVERSE',
                            'ADIDAS YEEZY 500',
                            'AIR JORDAN 11,',
                            'Nike x Cortez',
                            'BAPE',
                            'AIR JORDAN 5',
                            'ADIDAS YEEZY 700v1',
                            'ADIDAS YEEZY 350',
                            'AIR FORCE CLASSIC-group',
                            'Amiri',
                            'AIR JORDAN 1 LOW',
                            'Balenciaga',
                            'Adidas X Bad Bunny',
                            'AIR FORCE LOW',
                            'AIR MAX 1',
                            'Nike Air Tn',
                            ];

        foreach($dataCollections as $name)
        {
            Collection::factory()->create([
                'collection_group_id'=>$sneakerCollectionGroup->id,
                'name'=>$name,
                'slug'=>Str::slug($name),
            ]);
        }

        $dataProduits=
        [
            ['name'=>'Vans Old Skool','old_price'=>0,'collection_name'=>'VANS',],
            ['name'=>'AIR FORCE ONE LOW WHITE','old_price'=>0,'collection_name'=>'AIR FORCE ONE',],
            ['name'=>'Air Jordan 3 Retro Racer Blue','old_price'=>25000,'collection_name'=>'AIR JORDAN 3',],
            ['name'=>'Air Jordan 3 Retro Laser Orange','old_price'=>25000,'collection_name'=>'AIR JORDAN 3',],
            ['name'=>'AIR JODAN 1 TRAVIS SCOTT','old_price'=>30000,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'ADIDAS YEEZY 700V3 Glay Brown','old_price'=>25000,'collection_name'=>'ADIDAS YEEZY 700V3',],
            ['name'=>'Adidas Yeezy 700 V3 Arzareth','old_price'=>0,'collection_name'=>'ADIDAS YEEZY 700V3',],
            ['name'=>' Yeezy 700 V3 Azael','old_price'=>0,'collection_name'=>'ADIDAS YEEZY 700V3',],
            ['name'=>'Air Jordan 4 Retro Fire Red (2020)','old_price'=>30000,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'Air Jordan 4 Retro Tour Yellow (Lightning)','old_price'=>35000,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'Air Jordan 4 Retro Red Thunder','old_price'=>35000,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'Air Jordan 4 Tech White (White Oreo)','old_price'=>35000,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'Air Jordan 4 Shimmer','old_price'=>35000,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'Air Jordan 4 Retro University Blue','old_price'=>35000,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'Air Jordan 4 Taupe Haze','old_price'=>35000,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'Air Jordan 4 Retro Metallic Green','old_price'=>35000,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'Air Jordan 4 Retro Metallic Purple','old_price'=>35000,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'Air Jordan 4 Retro Union Guava Ice','old_price'=>35000,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'Air Jordan 4 Retro Union Desert Moss','old_price'=>35000,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'Air Jordan 4 Bred 2019','old_price'=>35000,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'Air Jordan 4 Retro Off-White Sail','old_price'=>35000,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'Air Jordan 4 PSG Neutral Grey Bordeaux','old_price'=>35000,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'Air Jordan 4 Neon Volt','old_price'=>35000,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'Air Jordan 4 Retro Union Off Noir','old_price'=>35000,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'Air Jordan 4 Retro Union Taupe Haze','old_price'=>35000,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'Air Jordan 4 Retro Metallic Red','old_price'=>35000,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'Air Jordan 4 Retro Travis Scott Cactus Jack','old_price'=>35000,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'Jordan 4 Rétro Chat noir (2020)','old_price'=>35000,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'AIR JORDAN 4 RETRO \'PUR $\'','old_price'=>35000,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'AIR JORDAN 4 RETRO \'COOL GREY\' 2019','old_price'=>35000,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'AIR JORDAN 4 RETRO \'MILITAIRE NOIR\'','old_price'=>35000,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'Jordan 4 Rétro Ciment blanc (2016)','old_price'=>35000,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'Air Jordan 1 High OG Atmosphere (Bubble Gum)','old_price'=>30000,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 1 Retro High OG Hyper Royal','old_price'=>30000,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 1 Retro High University Blue','old_price'=>35000,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 1 Retro High Court Purple (2021)','old_price'=>35000,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 1 High OG Patent Bred','old_price'=>30000,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 1 Retro High Light Smoke Grey','old_price'=>30000,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 1 Retro High Fearless OG','old_price'=>35000,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 1 Retro High OG Obsidian UNC 2019','old_price'=>30000,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 1 Retro High Satin Black Toe','old_price'=>30000,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 1 Retro High UNC Patent','old_price'=>0,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 1 Retro High OG 0ot For Resale\" Red"','old_price'=>35000,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 1 Retro High Flight Nostalgia','old_price'=>30000,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 1 Retro High Travis Scott \Cactus Jack\""','old_price'=>30000,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 1 Retro High Spider-Man \Origin Story\""','old_price'=>30000,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 1 Retro High Bred Toe','old_price'=>30000,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 1 High Dior','old_price'=>0,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 1 High Dark Mocha','old_price'=>30000,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 1 Mid Chicago Black Toe','old_price'=>30000,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 1 Mid Triple White','old_price'=>30000,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 1 Mid Triple Black','old_price'=>30000,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 1 Mid Light Smoke Grey','old_price'=>30000,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 1 Mid Pink Quartz','old_price'=>25000,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 1 Mid Fearless Maison Château Rouge','old_price'=>30000,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 1 Mid Digital Pink','old_price'=>25000,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 1 Mid Milan','old_price'=>30000,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 1 Mid Carbon Fiber All-Star (2021)','old_price'=>0,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 1 Mid Banned (2020)','old_price'=>25000,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 3 Retro Rust Pink','old_price'=>25000,'collection_name'=>'AIR JORDAN 3',],
            ['name'=>'AIR JORDAN 3 RETRO \'BLACK CAT\'','old_price'=>25000,'collection_name'=>'AIR JORDAN 3',],
            ['name'=>'Dunk Low Black White','old_price'=>30000,'collection_name'=>'DUNK LOW',],
            ['name'=>'Dunk Low Next Nature Gym Red','old_price'=>30000,'collection_name'=>'DUNK LOW',],
            ['name'=>'Dunk Low Green Glow','old_price'=>30000,'collection_name'=>'DUNK LOW',],
            ['name'=>'Dunk Low Team Red','old_price'=>30000,'collection_name'=>'DUNK LOW',],
            ['name'=>'Dunk Low SE Easter','old_price'=>30000,'collection_name'=>'DUNK LOW',],
            ['name'=>'Dunk Low Sail Coast','old_price'=>30000,'collection_name'=>'DUNK LOW',],
            ['name'=>'Dunk Low Laser Orange','old_price'=>0,'collection_name'=>'DUNK LOW',],
            ['name'=>'Dunk Low SP Orange Blaze (Syracuse)','old_price'=>0,'collection_name'=>'DUNK LOW',],
            ['name'=>'Air Force 1 Low \'07 LX UV Reactive Multi','old_price'=>0,'collection_name'=>'AIR FORCE ONE LOW',],
            ['name'=>'Air Force 1 Low \'07 FM Cut Out Swoosh White Black','old_price'=>15000,'collection_name'=>'AIR FORCE ONE LOW',],
            ['name'=>'Air Force 1 Low \'07 FM Cut Out Swoosh White Game Royal','old_price'=>15000,'collection_name'=>'AIR FORCE ONE LOW',],
            ['name'=>'Air Force 1 Low Love Letter Valentine\'s Day (2021)','old_price'=>0,'collection_name'=>'AIR FORCE ONE LOW',],
            ['name'=>'Air Force 1 Low \'07 Hare Space Jam','old_price'=>0,'collection_name'=>'AIR FORCE ONE LOW',],
            ['name'=>'Air Force 1 Low Supreme Flax','old_price'=>0,'collection_name'=>'AIR FORCE ONE LOW',],
            ['name'=>'Air Force 1 Low \'07 White Black Pebbled Leather','old_price'=>0,'collection_name'=>'AIR FORCE ONE LOW',],
            ['name'=>'Air Force 1 Low Undefeated 5 On It','old_price'=>0,'collection_name'=>'AIR FORCE ONE LOW',],
            ['name'=>'Air Force 1 Low SP Undefeated 5 On It Blue Yellow Croc','old_price'=>0,'collection_name'=>'AIR FORCE ONE LOW',],
            ['name'=>'Air Force 1 Low Wheat Dark Mocha','old_price'=>0,'collection_name'=>'AIR FORCE ONE LOW',],
            ['name'=>'Air Force 1 Low Drop Type Triple Black','old_price'=>25000,'collection_name'=>'AIR FORCE ONE LOW',],
            ['name'=>'Nike Air Force 1 LE','old_price'=>0,'collection_name'=>'AIR FORCE CLASSIC',],
            ['name'=>'Nike Air Force 1 Mid LE','old_price'=>25000,'collection_name'=>'AIR FORCE ONE HIGH',],
            ['name'=>'Air Force 1 Mid LE','old_price'=>25000,'collection_name'=>'AIR FORCE ONE HIGH',],
            ['name'=>'AFI Mid','old_price'=>25000,'collection_name'=>'AIR FORCE ONE HIGH',],
            ['name'=>'Vaporwaffle Sacai Dark Iris','old_price'=>30000,'collection_name'=>'Nike x Sacai',],
            ['name'=>'Vaporwaffle Sacai Black Gum','old_price'=>30000,'collection_name'=>'Nike x Sacai',],
            ['name'=>'Vaporwaffle Sacai Tan Navy','old_price'=>30000,'collection_name'=>'Nike x Sacai',],
            ['name'=>'Air Jordan 6 Travis Scott Medium Olive','old_price'=>35000,'collection_name'=>'AIR JORDAN 6',],
            ['name'=>'Air Jordan 6 Retro Travis Scott British Khaki','old_price'=>35000,'collection_name'=>'AIR JORDAN 6',],
            ['name'=>'Air Jordan 6 Retro DMP','old_price'=>35000,'collection_name'=>'AIR JORDAN 6',],
            ['name'=>'Air Jordan 11 Retro Cool Grey (2021)','old_price'=>25000,'collection_name'=>'AIR JORDAN 11',],
            ['name'=>'Air Jordan 11 Retro Bred','old_price'=>25000,'collection_name'=>'AIR JORDAN 11',],
            ['name'=>'Air Jordan 1 Mid','old_price'=>35000,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'New Balance 550','old_price'=>30000,'collection_name'=>'NEW BALANCE',],
            ['name'=>'Vans MSCHF','old_price'=>0,'collection_name'=>'VANS',],
            ['name'=>'Nike Sakai','old_price'=>0,'collection_name'=>'Nike x Sacai',],
            ['name'=>'AIR JORDAN 3 DARK MOCHA','old_price'=>0,'collection_name'=>'AIR JORDAN 3',],
            ['name'=>'Air Force 1 Low Louis Vuitton White','old_price'=>0,'collection_name'=>'AIR FORCE ONE LOW',],
            ['name'=>'Air Jordan 3 Brown','old_price'=>0,'collection_name'=>'AIR JORDAN 3',],
            ['name'=>'Jordan low Travis','old_price'=>0,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'AFI LVMH','old_price'=>0,'collection_name'=>'AIR FORCE ONE LOW',],
            ['name'=>'Dunk Low Rose Whisper','old_price'=>0,'collection_name'=>'DUNK LOW',],
            ['name'=>'Dunk low HUF Black','old_price'=>0,'collection_name'=>'DUNK LOW',],
            ['name'=>'Air Jordan 6 Pink','old_price'=>0,'collection_name'=>'AIR JORDAN 6',],
            ['name'=>'Dunk low Nuage Bleue','old_price'=>0,'collection_name'=>'DUNK LOW',],
            ['name'=>'Dunk low Why so Sad ?','old_price'=>0,'collection_name'=>'DUNK LOW',],
            ['name'=>'Air Jordan 6 Gold Hoops “Feminino”','old_price'=>0,'collection_name'=>'AIR JORDAN 6',],
            ['name'=>'Timberland Boots','old_price'=>0,'collection_name'=>'Non classé',],
            ['name'=>'Dunk low HUF Blue','old_price'=>0,'collection_name'=>'outofstock,DUNK LOW',],
            ['name'=>'AFI Off white Triple White','old_price'=>0,'collection_name'=>'AIR FORCE ONE LOW',],
            ['name'=>'Dunk Low Chlorophyll','old_price'=>0,'collection_name'=>'DUNK LOW',],
            ['name'=>'Air Force 1 Low White Pink (2022)','old_price'=>0,'collection_name'=>'AIR FORCE ONE LOW',],
            ['name'=>'LV Trainers Female','old_price'=>0,'collection_name'=>'LVMH',],
            ['name'=>'Nike AFI Supreme','old_price'=>0,'collection_name'=>'AIR FORCE ONE LOW',],
            ['name'=>'Dunk Low Winter Solstice','old_price'=>0,'collection_name'=>'DUNK LOW',],
            ['name'=>'Jordan 4 “A ma manière”','old_price'=>0,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'Chuck Taylor','old_price'=>0,'collection_name'=>'CONVERSE',],
            ['name'=>'Nike dunk low judge grey','old_price'=>0,'collection_name'=>'DUNK LOW',],
            ['name'=>'Nike dunk low lottery green','old_price'=>0,'collection_name'=>'DUNK LOW',],
            ['name'=>'AFI Off white Triple Black','old_price'=>0,'collection_name'=>'AIR FORCE ONE LOW',],
            ['name'=>'Yeezy 500 Salt','old_price'=>0,'collection_name'=>'ADIDAS YEEZY 500',],
            ['name'=>'Nike Nocta Drake Black','old_price'=>0,'collection_name'=>'Non classé',],
            ['name'=>'Air Jordan 11','old_price'=>0,'collection_name'=>'AIR JORDAN 11',],
            ['name'=>'Union X nike cortez light smoke','old_price'=>0,'collection_name'=>'Nike x Cortez',],
            ['name'=>'Jordan 4 Pink','old_price'=>0,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'Yeezy 500','old_price'=>0,'collection_name'=>'ADIDAS YEEZY 500',],
            ['name'=>'Yeezy 500 All Black','old_price'=>0,'collection_name'=>'ADIDAS YEEZY 500',],
            ['name'=>'A bathing ape bape sk8 sta navy khaki','old_price'=>0,'collection_name'=>'BAPE',],
            ['name'=>'A Bathing Ape Bape Sta','old_price'=>0,'collection_name'=>'BAPE',],
            ['name'=>'We the best air Jordan rétro 5 Dj khaled','old_price'=>0,'collection_name'=>'AIR JORDAN 5',],
            ['name'=>'Yeezy 700v1','old_price'=>0,'collection_name'=>'ADIDAS YEEZY 700v1',],
            ['name'=>'Jordan 4 Soft Pink','old_price'=>0,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'Nike Dunk Low Jackie Robinson','old_price'=>0,'collection_name'=>'DUNK LOW',],
            ['name'=>'Yeezy 350 MX OAT','old_price'=>25000,'collection_name'=>'ADIDAS YEEZY 350',],
            ['name'=>'Dunk Low Nuage Blue','old_price'=>30000,'collection_name'=>'DUNK LOW',],
            ['name'=>'LV Dark Blue','old_price'=>0,'collection_name'=>'LVMH',],
            ['name'=>'LV Green','old_price'=>0,'collection_name'=>'LVMH',],
            ['name'=>'AFI Collection','old_price'=>0,'collection_name'=>'AIR FORCE CLASSIC-group',],
            ['name'=>'Air Jordan 1 Pink','old_price'=>0,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'LV Orange &amp; White','old_price'=>0,'collection_name'=>'LVMH',],
            ['name'=>'Amiri Blue','old_price'=>0,'collection_name'=>'Amiri',],
            ['name'=>'LV Sail &amp; White','old_price'=>0,'collection_name'=>'LVMH',],
            ['name'=>'LV Triple Black','old_price'=>0,'collection_name'=>'LVMH',],
            ['name'=>'LV Green &amp; White','old_price'=>0,'collection_name'=>'LVMH',],
            ['name'=>'LV Triple White','old_price'=>0,'collection_name'=>'LVMH',],
            ['name'=>'LV Black &amp; White','old_price'=>0,'collection_name'=>'LVMH',],
            ['name'=>'LV Sail','old_price'=>0,'collection_name'=>'LVMH',],
            ['name'=>'Amiri Black','old_price'=>0,'collection_name'=>'Amiri',],
            ['name'=>'Amiri Grey','old_price'=>0,'collection_name'=>'Amiri',],
            ['name'=>'LV Yellow','old_price'=>0,'collection_name'=>'LVMH',],
            ['name'=>'LV Grey','old_price'=>0,'collection_name'=>'LVMH',],
            ['name'=>'Air Jordan 5 Wings','old_price'=>0,'collection_name'=>'AIR JORDAN 5',],
            ['name'=>'First Look PSG x Air Jordan 5 Low','old_price'=>0,'collection_name'=>'AIR JORDAN 5',],
            ['name'=>'LV Red','old_price'=>0,'collection_name'=>'LVMH',],
            ['name'=>'Air Jordan 1 LV','old_price'=>0,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 5','old_price'=>0,'collection_name'=>'AIR JORDAN 5',],
            ['name'=>'Dunk Low Grey Fog','old_price'=>0,'collection_name'=>'DUNK LOW',],
            ['name'=>'Dunk Low Hyper Cobalt','old_price'=>0,'collection_name'=>'DUNK LOW',],
            ['name'=>'Dunk Low Ottomo','old_price'=>0,'collection_name'=>'DUNK LOW',],
            ['name'=>'Concepts x nike Dunk low Orange','old_price'=>0,'collection_name'=>'DUNK LOW',],
            ['name'=>'Dunk Low Spartan Green','old_price'=>0,'collection_name'=>'DUNK LOW',],
            ['name'=>'Dunk Low Union Passport Pack Court Purple','old_price'=>0,'collection_name'=>'DUNK LOW',],
            ['name'=>'Dunk Low Union Passport Pack Argon','old_price'=>0,'collection_name'=>'DUNK LOW',],
            ['name'=>'Dunk Low Union Passport Pack Pistachio','old_price'=>0,'collection_name'=>'DUNK LOW',],
            ['name'=>'Dunk Low Kasina','old_price'=>0,'collection_name'=>'DUNK LOW',],
            ['name'=>'Air Jordan 11 HURRICANES','old_price'=>0,'collection_name'=>'AIR JORDAN 11',],
            ['name'=>'Air Jordan 4 KAWS - Cool Grey','old_price'=>0,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'Air Jordan 1 “Rebellion”','old_price'=>0,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 1 Low Year of the rabbit','old_price'=>0,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 1 OG Denim','old_price'=>0,'collection_name'=>'AIR JORDAN 1',],
            ['name'=>'Air Jordan 1 Low Cactus Jack Black Phantom','old_price'=>0,'collection_name'=>'AIR JORDAN 1 LOW',],
            ['name'=>'Air Jordan 1 Low Cactus Jack On Feet','old_price'=>0,'collection_name'=>'AIR JORDAN 1 LOW',],
            ['name'=>'Air Jordan 1 Low Cactus Jack Fragment','old_price'=>0,'collection_name'=>'AIR JORDAN 1 LOW',],
            ['name'=>'Nike Cortez Union','old_price'=>0,'collection_name'=>'Nike x Cortez',],
            ['name'=>'Balenciaga Triple S','old_price'=>0,'collection_name'=>'Balenciaga',],
            ['name'=>'Balenciaga Track','old_price'=>0,'collection_name'=>'Balenciaga',],
            ['name'=>'Adidas X Bad Bunny Grey','old_price'=>0,'collection_name'=>'Adidas X Bad Bunny',],
            ['name'=>'Air Force 1 Low NOCTA Drake Certified Lover Boy','old_price'=>0,'collection_name'=>'AIR FORCE ONE LOW',],
            ['name'=>'Air Force 1 Low SP Tiffany And Co.','old_price'=>0,'collection_name'=>'AIR FORCE ONE LOW',],
            ['name'=>'Air Force 1 Low LX Lucky Charms White','old_price'=>0,'collection_name'=>'AIR FORCE LOW',],
            ['name'=>'Dunk Low Essential Paisley Pack Barley','old_price'=>0,'collection_name'=>'DUNK LOW',],
            ['name'=>'Dunk Low Paisley Black','old_price'=>0,'collection_name'=>'DUNK LOW',],
            ['name'=>'Dunk Low Essential Paisley Pack Orange','old_price'=>0,'collection_name'=>'DUNK LOW',],
            ['name'=>'Jordan 4 Rétro','old_price'=>0,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'Yeezy 700 V3 fade Carbon','old_price'=>0,'collection_name'=>'ADIDAS YEEZY 700V3',],
            ['name'=>'Air Force One Corde','old_price'=>0,'collection_name'=>'AIR FORCE ONE LOW',],
            ['name'=>'Born x Raised x Nike dunk low SB One block at a time','old_price'=>0,'collection_name'=>'DUNK LOW',],
            ['name'=>'Yuto Horigome Nike Sb Dunk Low','old_price'=>0,'collection_name'=>'DUNK LOW',],
            ['name'=>'Off white X air Jordan 1 low Neutral Grey','old_price'=>0,'collection_name'=>'DUNK LOW',],
            ['name'=>'Nike dunk low MeGa Green','old_price'=>0,'collection_name'=>'DUNK LOW',],
            ['name'=>'Nike dunk low Retro PRM Year of The rabbit ????','old_price'=>0,'collection_name'=>'DUNK LOW',],
            ['name'=>'Nike dunk low premium Co. JP Brawn','old_price'=>0,'collection_name'=>'DUNK LOW',],
            ['name'=>'Nike air max 1 Amsterdam','old_price'=>0,'collection_name'=>'AIR MAX 1',],
            ['name'=>'Jordan 1 low SE Arctic punch','old_price'=>0,'collection_name'=>'AIR JORDAN 1 LOW',],
            ['name'=>'Jordan 4 KAWS Black','old_price'=>0,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'Jordan 4 Rétro Military Blue (2012)','old_price'=>0,'collection_name'=>'AIR JORDAN 4',],
            ['name'=>'New Balance 2002R','old_price'=>0,'collection_name'=>'NEW BALANCE',],
            ['name'=>'New Balance 9060 Inside Voices','old_price'=>0,'collection_name'=>'NEW BALANCE',],
            ['name'=>'Nike Air Max 1','old_price'=>30000,'collection_name'=>'AIR MAX 1',],
            ['name'=>'Nike Air Max 95 X Corteiz','old_price'=>30000,'collection_name'=>'Nike Air Tn',],
        ];

        foreach ($dataProduits as $product) {
            $collection=Collection::query()->where('name',$product['name'])->get()->first();

            Product::create([
                'name'=>$product['name'],
                'slug'=>Str::slug($product['name']),
                'product_type_id'=>$stock->id,
                'product_option_id'=>$Pointure->id,
                'collection_id'=>$collection?->id,
                'old_price'=>$product['old_price'],
                'status'=>'enPreparation'
            ]);
        }

    }
}
