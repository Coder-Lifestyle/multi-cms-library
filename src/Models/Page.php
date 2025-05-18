<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Model;
use MultiCmsLibrary\SharedModels\Cache\RedisKeyBuilder;
use MultiCmsLibrary\SharedModels\Models\Traits\HasSettings;
use MultiCmsLibrary\SharedModels\Models\Traits\HasCacheKeys;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class Page extends Model
{
    use HasSettings, HasCacheKeys;

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
        $pageId = $this->getKey();

        $domainIds = Redis::smembers($builder->modelDomainsKey($this));

        foreach ($domainIds as $domainId) {
            Cache::store('redis')->tags([
                $builder->domainTag($domainId),
                $this->getViewCacheTag(),
                $this->getCacheTag(),
            ])->flush();
        }

        $usedKeyPattern = "{$builder->prefix()}:page_*:used_pages";

        foreach (Redis::keys($usedKeyPattern) as $usedKey) {
            if (Redis::sismember($usedKey, $pageId)) {
                if (preg_match("/page_(\d+):used_pages$/", $usedKey, $m)) {
                    $relatedPageId = $m[1];
                    if ($relatedPage = self::find($relatedPageId)) {
                        $relatedPage->flushCache(); 
                    }
                }
            }
        }

        Redis::del($builder->modelDomainsKey($this));
        Redis::del($builder->modelUsedPagesKey($this));
    }
}

