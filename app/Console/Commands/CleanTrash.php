<?php

namespace App\Console\Commands;

use App\Models\Blog;
use App\Models\Page;
use Illuminate\Console\Command;

class CleanTrash extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trash:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permanently delete trashed items older than 30 days';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $blogCount = Blog::onlyTrashed()
            ->where('deleted_at', '<', now()->subDays(30))
            ->forceDelete();

        $pageCount = Page::onlyTrashed()
            ->where('deleted_at', '<', now()->subDays(30))
            ->forceDelete();

        $this->info("Cleaned {$blogCount} blog(s) and {$pageCount} page(s) from trash.");

        return Command::SUCCESS;
    }
}
