<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Item;

class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'item_id' => Item::factory(),
            'payment_method' => 'カード支払い',
            'stripe_payment_intent_id' => 'pi_test_' . $this->faker->unique()->lexify('??????????'),
            'shipping_postal_code' => '123-4567',
            'shipping_address' => $this->faker->address(),
            'shipping_building' => $this->faker->secondaryAddress(),
            'price' => $this->faker->numberBetween(1000, 50000),
        ];
    }
}
