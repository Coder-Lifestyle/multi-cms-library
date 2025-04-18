<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['title', 'body', 'domain_id', 'category_id', 'slug', 'featured_image'];

    protected $appends = ['backlink_price', 'full_url'];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * A page can have multiple tags.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps(); // Many-to-many relation
    }

    public function getBacklinkPriceAttribute()
    {
        return DomainSetting::getSetting($this->domain_id, 'backlink_price', 12);
    }

    public function getStripeProductIdAttribute()
    {
        return DomainSetting::getSetting($this->domain_id, 'stripe_product_id', 0);
    }

    public function getFullUrlAttribute()
    {
        if (!$this->domain) {
            return null;
        }

        $domainUrl = rtrim($this->domain->domain_url, '/');

        $categoryPath = $this->category ? $this->category->getFullSlugPath() : '';

        $pageSlug = $this->slug;

        $fullPath = trim($categoryPath . '/' . $pageSlug, '/');

        return $domainUrl . '/' . $fullPath;
    }
}

