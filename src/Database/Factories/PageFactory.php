<?php

namespace MultiCmsLibrary\SharedModels\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use MultiCmsLibrary\SharedModels\Models\Page;

class PageFactory extends Factory
{
    protected $model = Page::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'body' => $this->fakeHtmlContent(),
            'featured_image' => 'https://picsum.photos/seed/' . $this->faker->uuid . '/800/600',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function fakeHtmlContent(): string
    {
        $faker = $this->faker;

        return <<<HTML
    <h1>{$faker->sentence(6)}</h1>
    <p>{$faker->paragraph(5)}</p>
    
    <h2>{$faker->sentence(5)}</h2>
    <p>{$faker->paragraph(4)}</p>
    <img src="https://picsum.photos/seed/{$faker->uuid}/900/400" alt="Section image" />
    
    <h3>{$faker->sentence(4)}</h3>
    <blockquote>{$faker->sentence(12)}</blockquote>
    <p>{$faker->paragraph(3)}</p>
    
    <h3>{$faker->sentence(3)}</h3>
    <ul>
        <li>{$faker->sentence()}</li>
        <li>{$faker->sentence()}</li>
        <li>{$faker->sentence()}</li>
        <li>{$faker->sentence()}</li>
    </ul>
    
    <p>{$faker->paragraph(5)}</p>
    
    <h2>{$faker->sentence(4)}</h2>
    <p>{$faker->paragraph(3)}</p>
    <pre><code>function exampleCode() {
        echo "{$faker->word} is working!";
    }
    </code></pre>
    
    <h3>{$faker->sentence(4)}</h3>
    <p>{$faker->paragraph(3)}</p>
    <img src="https://picsum.photos/seed/{$faker->uuid}/1000/500" alt="Another image" />
    
    <hr>
    
    <h2>Conclusion</h2>
    <p>{$faker->paragraph(4)}</p>
    <blockquote><em>{$faker->sentence(10)}</em></blockquote>
    HTML;
    }
}
