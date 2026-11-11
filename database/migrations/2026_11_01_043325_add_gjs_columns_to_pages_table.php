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
            $table->text('gjs_css')->nullable()->after('content');
            $table->longText('gjs_components')->nullable()->after('gjs_css');
            $table->text('gjs_styles')->nullable()->after('gjs_components');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['gjs_css', 'gjs_components', 'gjs_styles']);
        });
    }
};
