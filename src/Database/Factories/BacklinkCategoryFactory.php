<?php

namespace MultiCmsLibrary\SharedModels\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use MultiCmsLibrary\SharedModels\Models\BacklinkCategory;

class BacklinkCategoryFactory extends Factory
{
    protected $model = BacklinkCategory::class;

    public function definition(): array
    {
        $name = $this->faker->words(2, true);

        return [
            'slug' => Str::slug($name),
            'name_nl' => ucfirst($name),
            'name_en' => null,
            'name_de' => null,
            'name_fr' => null,
            'name_es' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
