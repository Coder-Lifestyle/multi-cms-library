<?php

namespace MultiCmsLibrary\SharedModels\Models\Traits;

trait HasCacheKeys
{
    /**
     * Generate a Redis-safe cache key for this model.
     *
     * @param string $prefix Optional prefix (e.g. "spintax", "preview", "seo")
     * @return string
     */
    public function getRedisCacheKey(string $prefix = ''): string
    {
        $model = strtolower(class_basename($this));
        $id    = $this->getKey();

        return trim("{$prefix}_{$model}_{$id}", '_');
    }

    /**
     * Generate a cache key for spintax rendering.
     *
     * @return string
     */
    public function getSpintaxCacheKey(): string
    {
        return $this->getRedisCacheKey('spintax');
    }

    /**
     * Unique cache tag for this model only.
     * Example: "page_123"
     */
    public function getCacheTag(): string
    {
        $model = strtolower(class_basename($this));
        return "{$model}_{$this->getKey()}";
    }

    /**
     * Model-specific cache tags (global + specific)
     * Example: ["page", "page_123"]
     */
    public function getCacheTags(): array
    {
        $model = strtolower(class_basename($this));
        return [$model, $this->getCacheTag()];
    }
}
