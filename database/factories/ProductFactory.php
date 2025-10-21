<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Utils\ProductNameGenerator;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomCategoryId = Category::Query()->inRandomOrder()->first()->id ?? 1;
        $productData = ProductNameGenerator::generate();
        
        $baseDescript = "Este " . $productData['name'] . " es un dispositivo de alta calidad que ofrece un rendimiento excepcional. ";
        $baseDescript .= "Diseñado para usuarios exigentes que buscan lo mejor en tecnología. ";
        
        return [
            'name' => $productData['name'],            
            'price' => $this->faker->randomFloat(2,50,100),
            'description' => $baseDescript . $productData['description_suffix'],
            'image_url' => $this->faker->imageUrl(),
            'weight' => $this->faker->randomFloat(2,0,100),
            'stock' => $this->faker->numberBetween(0,1000),
            'is_active' => $this->faker->boolean(80),
            'category_id' => $randomCategoryId,
        ];
    }
}
