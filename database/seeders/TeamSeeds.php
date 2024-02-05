<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamSeeds extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $directeur=Team::factory()->create(["name"=>'Directeur',"slug"=>'directeur']);
        Team::factory()->create(["name"=>'Assistant',"slug"=>'assistant']);
        $directeur->members()->attach([1,2]);
    }
}
