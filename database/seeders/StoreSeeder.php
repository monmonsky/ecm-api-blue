<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\StoreBalance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Store::factory()
            ->count(10) // Adjust the count as needed
            ->create()
            ->each(function ($store) {
                // You can add additional logic here if needed, such as seeding related models
                StoreBalance::factory()
                    ->create([
                        'store_id' => $store->id
                    ]);
            });
    }
}
