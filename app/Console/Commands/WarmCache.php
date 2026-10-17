<?php

namespace App\Console\Commands;

use App\Services\ContentCacheService;
use Illuminate\Console\Command;

class WarmCache extends Command
{
    protected $signature = 'cache:warm';

    protected $description = 'Pre-warm frontend content caches (latest blogs, categories) for faster first-hit response';

    public function handle(ContentCacheService $cache): int
    {
        $this->info('Warming content cache...');

        $cache->flushAll();

        $cache->latestBlogs(3);
        $this->line('  ✓ Latest blogs (3)');
        $cache->latestBlogs(5);
        $this->line('  ✓ Latest blogs (5)');
        $cache->categoriesWithBlogCount();
        $this->line('  ✓ Categories with count');

        $this->info('Cache warmed successfully.');

        return self::SUCCESS;
    }
}
