<?php

namespace Database\Factories;

use App\Models\Store;
use App\Models\StoreBalance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StoreBalance>
 */
class StoreBalanceFactory extends Factory
{
    protected $model = StoreBalance::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'balance' => $this->faker->randomFloat(2, 0, 100000),
        ];
    }
}
