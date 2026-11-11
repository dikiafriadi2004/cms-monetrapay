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
        Schema::dropIfExists('themes');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // intentionally empty — themes feature removed
    }
};
