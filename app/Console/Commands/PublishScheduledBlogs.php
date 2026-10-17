<?php

namespace App\Console\Commands;

use App\Models\Blog;
use Illuminate\Console\Command;

class PublishScheduledBlogs extends Command
{
    protected $signature = 'blogs:publish-scheduled';

    protected $description = 'Publish blog posts that have reached their scheduled publish date/time';

    public function handle(): int
    {
        $count = Blog::where('status', 'draft')
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now())
            ->update([
                'status' => 'published',
                'published_at' => now(),
            ]);

        if ($count > 0) {
            $this->info("Published {$count} scheduled blog post(s).");
        }

        return self::SUCCESS;
    }
}
