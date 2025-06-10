<?php

namespace MultiCmsLibrary\SharedModels\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use MultiCmsLibrary\SharedModels\Models\Domain;
use Illuminate\Support\Facades\DB;

class DomainFactory extends Factory
{
    protected $model = Domain::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'domain_category' => $this->faker->word,
            'domain_url' => $this->faker->unique()->domainName,
            'page_creation_type' => 'manual',
            'sections' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Domain $domain) {
            $faker = $this->faker;
            $now = now();

            $domainSettingKeys = [
                'template_name'    => $this->randomTemplateName(),
                'footer_text'      => $faker->sentence(3),
                'logo_url'         => $faker->imageUrl(200, 100),
                'title'            => $faker->company . ' - ' . $faker->catchPhrase,
                'meta_description' => $faker->sentence(6),
                'caching'          => '0',
                'caching_time'     => '0',
            ];

            foreach ($domainSettingKeys as $key => $value) {
                DB::table('settings')->insert([
                    'entity_type' => Domain::class,
                    'entity_id' => $domain->id,
                    'key' => $key,
                    'value' => $value,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        });
    }


    private function randomTemplateName(): string
    {
        return collect([
            'tm-589-lugx-gaming',
            'html5up-zerofour',
            'html5up-verti',
        ])->random();
    }
}
