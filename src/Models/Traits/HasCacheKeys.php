<?php

namespace MultiCmsLibrary\SharedModels\Models\Traits;

trait HasCacheKeys
{
    /**
     * Generate a Redis-safe cache key for this model.
     */
    public function getRedisCacheKey(string $prefix = ''): string
    {
        $model = strtolower(class_basename($this));
        $id = $this->getKey();

        return trim("{$prefix}_{$model}_{$id}", '_');
    }

    /**
     * Generate a cache key for spintax rendering.
     */
    public function getSpintaxCacheKey(): string
    {
        return $this->getRedisCacheKey('spintax');
    }

    /**
     * Get model type name (e.g., "page", "product", "category").
     */
    public function getCacheType(): string
    {
        return strtolower(class_basename($this));
    }

    /**
     * Unique cache tag for this model instance (e.g., "page_123").
     */
    public function getCacheTag(): string
    {
        return $this->getCacheType() . '_' . $this->getKey();
    }

    /**
     * Cache tags used with Laravel's tag-based cache for this model.
     */
    public function getCacheTags(int $domainId): array
    {
        return [
            "domain_{$domainId}",
            $this->getViewCacheTag()
        ];
    }

    /**
     * View-level cache tag like "page_view", "product_view", etc.
     */
    public function getViewCacheTag(): string
    {
        return match ($this->getCacheType()) {
            'page'     => 'page_view',
            'product'  => 'product_view',
            'category' => 'category_view',
            default    => 'view',
        };
    }

    /**
     * Full cache key for this model in a domain context.
     */
    public function getCacheKeyForDomain(int $domainId): string
    {
        return "ui:domain_{$domainId}:{$this->getCacheType()}:{$this->getKey()}";
    }

    /**
     * Default TTL for this model's cache (in seconds).
     */
    public function getCacheTTL(): int
    {
        return 3600;
    }
}
