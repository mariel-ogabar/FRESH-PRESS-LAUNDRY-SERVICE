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
        Schema::create('main_services', function (Blueprint $table) {
            $table->id();
            $table->string('service_name');
            $table->enum('pricing_type', ['PER_KG','PER_ITEM']);
            $table->decimal('service_base_price', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_services');
    }
};
