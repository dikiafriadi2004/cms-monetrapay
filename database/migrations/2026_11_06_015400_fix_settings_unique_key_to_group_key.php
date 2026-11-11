<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fix settings table unique constraint.
 * 
 * Previously: unique on `key` only → prevented same key in different groups
 * After: unique on (`group`, `key`) → allows same key in different groups
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // Drop old single-column unique index
            $table->dropUnique('settings_key_unique');

            // Add composite unique index on (group, key)
            $table->unique(['group', 'key'], 'settings_group_key_unique');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropUnique('settings_group_key_unique');
            $table->unique('key', 'settings_key_unique');
        });
    }
};
