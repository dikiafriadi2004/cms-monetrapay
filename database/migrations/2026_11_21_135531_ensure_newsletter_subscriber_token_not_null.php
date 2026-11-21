<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // Backfill any existing rows that have a NULL confirmation_token
        DB::table('newsletter_subscribers')
            ->whereNull('confirmation_token')
            ->orWhere('confirmation_token', '')
            ->each(function ($subscriber) {
                DB::table('newsletter_subscribers')
                    ->where('id', $subscriber->id)
                    ->update(['confirmation_token' => Str::random(64)]);
            });

        // Now enforce NOT NULL with default ''
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->string('confirmation_token')->default('')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->string('confirmation_token')->nullable()->default(null)->change();
        });
    }
};
