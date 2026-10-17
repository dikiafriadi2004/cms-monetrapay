<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add indexes to blogs table if they don't exist
        if (! $this->indexExists('blogs', 'blogs_status_index')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->index('status');
            });
        }

        if (! $this->indexExists('blogs', 'blogs_published_at_index')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->index('published_at');
            });
        }

        // Add composite index to comments table (status+created_at)
        // Note: Individual indexes already exist, adding composite for better query performance
        if (! $this->indexExists('comments', 'comments_status_created_at_index')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->index(['status', 'created_at']);
            });
        }

        // Note: contact_submissions.is_read already has an index from table creation
    }

    public function down(): void
    {
        // Drop composite index from comments table
        if ($this->indexExists('comments', 'comments_status_created_at_index')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->dropIndex(['status', 'created_at']);
            });
        }

        // Drop indexes from blogs table
        if ($this->indexExists('blogs', 'blogs_published_at_index')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->dropIndex(['published_at']);
            });
        }

        if ($this->indexExists('blogs', 'blogs_status_index')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->dropIndex(['status']);
            });
        }
    }

    private function indexExists(string $table, string $index): bool
    {
        $connection = Schema::getConnection();
        $driver = $connection->getDriverName();

        if ($driver === 'mysql') {
            $result = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$index]);

            return count($result) > 0;
        }

        if ($driver === 'sqlite') {
            $result = DB::select("SELECT name FROM sqlite_master WHERE type='index' AND name = ?", [$index]);

            return count($result) > 0;
        }

        return false;
    }
};
