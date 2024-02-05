<?php

namespace App\Console\Commands;

use App\Models\Collection;
use App\Models\CollectionGroup;
use App\Models\Kit;
use App\Models\Product;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically Generate an XML Sitemap';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $sitmap = Sitemap::create();

        $sitmap->add(CollectionGroup::query()->get()->all());
        $sitmap->add(Collection::query()->get()->all());
        $sitmap->add(Product::query()->get()->all());
        $sitmap->add(Kit::query()->get()->all());



        $sitmap->writeToFile(public_path('sitemap.xml'));

    }
}
