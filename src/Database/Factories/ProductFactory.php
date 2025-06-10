<?php

namespace MultiCmsLibrary\SharedModels\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use MultiCmsLibrary\SharedModels\Models\Product;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $faker = $this->faker;

        $shortDescription = <<<HTML
<p><strong>{$faker->words(2, true)}:</strong> {$faker->sentence(6)}</p>
<ul>
    <li>{$faker->sentence()}</li>
</ul>
HTML;

        $description = <<<HTML
<h3>{$faker->sentence(4)}</h3>
<p>{$faker->paragraph(3)}</p>
<p>{$faker->paragraph(3)}</p>
<blockquote>{$faker->sentence(10)}</blockquote>
<ul>
    <li>{$faker->sentence(5)}</li>
    <li>{$faker->sentence(5)}</li>
    <li>{$faker->sentence(5)}</li>
</ul>
<p><em>{$faker->sentence(6)}</em></p>
HTML;

        return [
            'name' => $faker->words(3, true),
            'slug' => Str::slug($faker->words(3, true)),
            'short_description' => $shortDescription,
            'description' => $description,
            'price' => $faker->numberBetween(50, 999),
            'sale_price' => $faker->numberBetween(10, 49),
            'image_url' => 'https://picsum.photos/seed/' . $faker->uuid . '/800/600',
            'brand_name' => $faker->name,
            'stock_quantity' => $faker->numberBetween(0, 500),
            'rating' => $faker->numberBetween(1, 5),
            'affiliate_link' => $faker->url,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
