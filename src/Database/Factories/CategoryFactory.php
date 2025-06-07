<?php

namespace MultiCmsLibrary\SharedModels\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use MultiCmsLibrary\SharedModels\Models\Category;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = $this->faker->words(2, true);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'image_url' => 'https://picsum.photos/seed/' . $this->faker->uuid . '/600/400',
            'parent_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
