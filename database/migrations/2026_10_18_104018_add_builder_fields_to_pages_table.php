<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->boolean('use_page_builder')->default(false)->after('content');
            $table->string('layout_type')->default('default')->after('use_page_builder'); // default, full-width, sidebar-left, sidebar-right
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['use_page_builder', 'layout_type']);
        });
    }
};
