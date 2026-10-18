<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes for comments table
        if ($this->indexNotExists('comments', 'idx_comments_status')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->index('status', 'idx_comments_status');
            });
        }

        // Add indexes for contact_submissions table
        if ($this->indexNotExists('contact_submissions', 'idx_contact_submissions_is_read')) {
            Schema::table('contact_submissions', function (Blueprint $table) {
                $table->index('is_read', 'idx_contact_submissions_is_read');
            });
        }

        // Add indexes for blogs table (if not exists from previous migration)
        if ($this->indexNotExists('blogs', 'idx_blogs_status')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->index('status', 'idx_blogs_status');
            });
        }

        // Add composite index for blogs sorting
        if ($this->indexNotExists('blogs', 'idx_blogs_status_published')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->index(['status', 'published_at'], 'idx_blogs_status_published');
            });
        }

        // Add indexes for pages table
        if ($this->indexNotExists('pages', 'idx_pages_status')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->index('status', 'idx_pages_status');
            });
        }

        // Add indexes for newsletter_subscribers table
        if ($this->indexNotExists('newsletter_subscribers', 'idx_newsletter_confirmed')) {
            Schema::table('newsletter_subscribers', function (Blueprint $table) {
                $table->index('confirmed_at', 'idx_newsletter_confirmed');
            });
        }

        // Add indexes for ads table
        if ($this->indexNotExists('ads', 'idx_ads_status_dates')) {
            Schema::table('ads', function (Blueprint $table) {
                $table->index(['is_active', 'start_date', 'end_date'], 'idx_ads_status_dates');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex('idx_comments_status');
        });

        Schema::table('contact_submissions', function (Blueprint $table) {
            $table->dropIndex('idx_contact_submissions_is_read');
        });

        Schema::table('blogs', function (Blueprint $table) {
            if ($this->indexExists('blogs', 'idx_blogs_status')) {
                $table->dropIndex('idx_blogs_status');
            }
            if ($this->indexExists('blogs', 'idx_blogs_status_published')) {
                $table->dropIndex('idx_blogs_status_published');
            }
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->dropIndex('idx_pages_status');
        });

        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->dropIndex('idx_newsletter_confirmed');
        });

        Schema::table('ads', function (Blueprint $table) {
            $table->dropIndex('idx_ads_status_dates');
        });
    }

    /**
     * Check if index exists on a table.
     */
    private function indexExists(string $table, string $indexName): bool
    {
        $connection = DB::connection();
        $driverName = $connection->getDriverName();

        if ($driverName === 'mysql') {
            $database = $connection->getDatabaseName();
            $result = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
            return !empty($result);
        }

        if ($driverName === 'sqlite') {
            $result = DB::select("SELECT name FROM sqlite_master WHERE type='index' AND name = ?", [$indexName]);
            return !empty($result);
        }

        // For other drivers, assume index doesn't exist
        return false;
    }

    /**
     * Check if index NOT exists on a table.
     */
    private function indexNotExists(string $table, string $indexName): bool
    {
        return !$this->indexExists($table, $indexName);
    }
};
