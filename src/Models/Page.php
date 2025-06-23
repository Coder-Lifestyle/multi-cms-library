<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MultiCmsLibrary\SharedModels\Cache\RedisKeyBuilder;
use MultiCmsLibrary\SharedModels\Models\Traits\HasSettings;
use MultiCmsLibrary\SharedModels\Models\Traits\HasCacheKeys;
use Illuminate\Support\Facades\Cache;
use MultiCmsLibrary\SharedModels\Database\Factories\PageFactory;

class Page extends Model
{
    use HasSettings, HasCacheKeys, HasFactory;

    public static function newFactory()
    {
        return PageFactory::new();
    }

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

    public function backlinks()
    {
        return $this->hasMany(Backlink::class)->where('is_active', true);
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
        return $this->domain?->getSetting('backlink_price', 12);
    }

    public function getStripeProductIdAttribute()
    {
        return $this->domain?->getSetting('stripe_product_id', null);
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

    public function flushCache(): void
    {
        $builder = app(RedisKeyBuilder::class);
        $pageId  = $this->getKey();

        // 1) Flush per-domain tagged caches
        $domainIds = Cache::get($builder->modelDomainsKey($this), []);
        foreach ($domainIds as $domainId) {
            Cache::tags([
                $builder->domainTag($domainId),
                $this->getViewCacheTag(),
                $this->getCacheTag(),
            ])->flush();
        }

        // 2) Flush any pages this page “uses”
        $usedPageIds = Cache::get($builder->modelUsedPagesKey($this), []);
        foreach ($usedPageIds as $relatedPageId) {
            if ($relatedPage = self::find($relatedPageId)) {
                $relatedPage->flushCache();
            }
        }

        // 3) Remove the stored lists
        Cache::forget($builder->modelDomainsKey($this));
        Cache::forget($builder->modelUsedPagesKey($this));
    }
}

