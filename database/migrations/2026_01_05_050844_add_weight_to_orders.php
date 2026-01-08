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
        Schema::table('orders', function (Blueprint $table) {
            // This adds the weight column to your table
            // '8, 2' allows numbers like 12345.67 (plenty of room for kilos)
            // 'nullable' means it's okay if it's empty sometimes
            $table->decimal('weight', 8, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // This removes the column if you ever need to undo this change
            $table->dropColumn('weight');
        });
    }
};