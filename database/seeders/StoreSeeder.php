<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\StoreBalance;
use App\Models\StoreBalanceHistory;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Store::factory()->count(10)->create()->each(function ($store) {
            $storeBalance = StoreBalance::factory()->create(['store_id' => $store->id]);
            StoreBalanceHistory::factory()->create([
                'store_balance_id' => $storeBalance->id,
                'amount' => $storeBalance->balance
            ]);
        });
    }
}
