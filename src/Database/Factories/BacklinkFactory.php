<?php

namespace MultiCmsLibrary\SharedModels\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use MultiCmsLibrary\SharedModels\Models\Backlink;

class BacklinkFactory extends Factory
{
    protected $model = Backlink::class;

    public function definition(): array
    {
        return [
            'url' => $this->faker->url . ':' . $this->faker->numberBetween(1000, 9999),
            'title' => $this->faker->sentence(2),
            'rel' => 'follow',
            'subscription_type' => null,
            'subscription_end_date' => null,
            'stripe_subscription_id' => null,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
