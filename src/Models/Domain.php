<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Domain extends Model
{
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



    public function settings()
    {
        return $this->hasMany(DomainSetting::class);
    }

    public function areas()
    {
        return $this->hasMany(Area::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function getSetting($key, $default = null)
    {
        return $this->settings()->where('key', $key)->value('value') ?? $default;
    }

    public function getMetrics()
    {
        return $this->settings()
            ->where('key', 'like', 'metrics_%')
            ->get()
            ->mapWithKeys(function ($setting) {
                // Remove the "metrics_" prefix from the key
                $newKey = Str::after($setting->key, 'metrics_');
                return [$newKey => $setting->value];
            });
    }
}
