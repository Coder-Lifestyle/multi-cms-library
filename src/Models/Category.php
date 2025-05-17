<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use MultiCmsLibrary\SharedModels\Cache\RedisKeyBuilder;
use MultiCmsLibrary\SharedModels\Models\Traits\HasCacheKeys;
use MultiCmsLibrary\SharedModels\Models\Traits\HasSettings;
use MultiCmsLibrary\SharedModels\Models\Traits\HasUniqueField;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class Category extends Model
{
    use HasSettings, HasUniqueField, HasCacheKeys;

    protected $fillable = ['name', 'slug', 'domain_id', 'parent_id', 'image_url'];

    protected $appends = ['full_url'];

    protected array $autoUniqueFields = [
        'slug' => 'name',
    ];

    protected array $uniqueFieldScopes = [
        'slug' => ['domain_id'],
    ];

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    /**
     * Relationship to get the parent category.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Relationship to get the subcategories (children).
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get the full slug path, including parent categories.
     */
    public function getFullSlugPath(): string
    {
        if ($this->parent) {
            return $this->parent->getFullSlugPath() . '/' . $this->slug;
        }

        return $this->slug;
    }

    public function getFullUrlAttribute()
    {
        if (!$this->domain) {
            return null;
        }
        

        $domainUrl = rtrim($this->domain->domain_url, '/');

        $fullSlugPath = $this->getFullSlugPath();

        $fullPath = trim($fullSlugPath, '/');

        return $domainUrl . '/' . $fullPath;
    }

    public function flushCache(): void
    {
        $builder = app(RedisKeyBuilder::class);

        $domainIds = Redis::smembers($builder->modelDomainsKey($this));

        foreach ($domainIds as $domainId) {
            Cache::store('redis')->tags([
                $builder->domainTag($domainId),
                $this->getViewCacheTag(),
                $this->getCacheTag(),
            ])->flush();
        }

        Redis::del($builder->modelDomainsKey($this));
    }
}

