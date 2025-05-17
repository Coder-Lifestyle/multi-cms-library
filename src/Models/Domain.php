<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use MultiCmsLibrary\SharedModels\Models\Traits\HasCacheKeys;
use MultiCmsLibrary\SharedModels\Models\Traits\HasSettings;
use ReflectionClass;

class Domain extends Model
{
    use HasSettings, HasCacheKeys;
    // Define the table name if it's different from the plural form of the model
    // protected $table = 'domains';

    // Fillable fields allow mass assignment
    protected $fillable = [
        'name',
        'domain_url',
        'page_creation_type',
        'sections',
    ];

    protected $casts = [
        'sections' => 'array',
    ];

    /**
     * A domain has many pages.
     */
    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    /**
     * A domain has many categories.
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * A domain has many tags.
     */
    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    public function backlinks()
    {
        // Assumes each Page model has a 'page_id' and each Backlink belongs to a page via 'page_id'
        return $this->hasManyThrough(Backlink::class, Page::class, 'page_id', 'id');
    }


    public function areas()
    {
        return $this->hasMany(Area::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function widgets()
    {
        return $this->hasMany(Widget::class);
    }

    public function getMetrics()
    {
        return $this->settings
            ->filter(fn($setting) => Str::startsWith($setting->key, 'metrics_'))
            ->mapWithKeys(function ($setting) {
                $cleanKey = Str::after($setting->key, 'metrics_');
                return [$cleanKey => $setting->value];
            });
    }

}
