<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'price' => $this->faker->randomFloat(0, 10000, 50000),
            'stock' => $this->faker->numberBetween(0, 100),
            'description' => $this->faker->sentence(),
            'category_id' => function () {
                return Category::inRandomOrder()->first()->id;
            },
            'photo' => 'default.jpg',
            'is_active' => 1
        ];
    }
}
