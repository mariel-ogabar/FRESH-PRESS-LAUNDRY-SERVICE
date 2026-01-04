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
        Schema::create('order_addons', function (Blueprint $table) {
            $table->id('order_addon_id'); 
            $table->foreignId('order_service_id')->constrained('order_services')->onDelete('cascade');     
            $table->foreignId('addon_id')->constrained('add_ons');
            $table->integer('addon_qty');
            $table->decimal('addon_price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_addons');
    }
};
