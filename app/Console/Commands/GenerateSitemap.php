<?php

namespace App\Console\Commands;

use App\Http\Controllers\Frontend\SeoController;
use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     */
    protected $description = 'Generate the sitemap.xml file from the database and write it to public/sitemap.xml';

    /**
     * Execute the console command.
     */
    public function handle(SeoController $seoController): int
    {
        $xml = $seoController->buildSitemapXml();

        $destination = public_path('sitemap.xml');

        file_put_contents($destination, $xml);

        $this->info('Sitemap written to ' . $destination);

        return self::SUCCESS;
    }
}
