<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Company;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'product_name' => $this->faker->word,
            'price' => $this->faker->numberBetween(100, 999),
            'stock' => $this->faker->randomDigit,
            'comment' => $this->faker->sentence,
            'img_path' => 'https://picsum.photos/200/300',
        ];
    }
}
