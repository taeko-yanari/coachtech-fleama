<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;

class ItemImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'item_id' => Item::factory(),
            'image_path' => 'items/sample.jpg',
            'sort_order' => 1,
        ];
    }
}
