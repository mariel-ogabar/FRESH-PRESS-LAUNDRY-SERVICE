<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // This runs the ServiceSeeder to add your Laundry Services
        $this->call(ServiceSeeder::class);
    }
}