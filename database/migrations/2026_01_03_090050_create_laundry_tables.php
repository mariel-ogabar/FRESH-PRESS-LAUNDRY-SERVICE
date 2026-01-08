<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // USERS Table
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id'); 
            $table->enum('role', ['ADMIN', 'CUSTOMER', 'STAFF'])->default('CUSTOMER');
            $table->string('name');
            $table->string('email_add')->unique();
            $table->string('password');
            $table->string('contact_no')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        // MAIN SERVICES Table
        Schema::create('main_services', function (Blueprint $table) {
            $table->id('service_id');
            $table->string('service_name');
            $table->enum('pricing_type', ['PER_KG', 'PER_ITEM']);
            $table->decimal('service_base_price', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ORDERS Table
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('total_price', 10, 2);
            $table->enum('order_status', ['ACTIVE', 'CANCELLED', 'COMPLETED'])->default('ACTIVE');
            $table->timestamps();
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });

        // ORDER SERVICES (Junction Table)
        Schema::create('order_services', function (Blueprint $table) {
            $table->id('order_service_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('service_id');
            $table->decimal('quantity', 10, 2);
            $table->decimal('service_price', 10, 2);
            $table->timestamps();
            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');
            $table->foreign('service_id')->references('service_id')->on('main_services');
        });

        // LAUNDRY STATUS Table
        Schema::create('laundry_statuses', function (Blueprint $table) {
            $table->id('laundry_status_id');
            $table->unsignedBigInteger('order_id');
            $table->enum('current_status', ['PENDING', 'WASHING', 'DRYING', 'FOLDING', 'IRONING', 'READY'])->default('PENDING');
            $table->timestamps();
            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');
        });
    }
};