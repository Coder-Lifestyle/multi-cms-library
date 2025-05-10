<?php

namespace MultiCmsLibrary\SharedModels\Models\Traits;

use Illuminate\Support\Str;

trait HasCacheKeys
{
    /**
     * Generate a Redis-safe cache key for any model.
     *
     * @param string $prefix Optional prefix (e.g. 'spintax', 'preview', 'seo').
     * @return string Fully-qualified cache key, like 'spintax_page_123'
     */
    public function getRedisCacheKey(string $prefix = ''): string
    {
        $model = strtolower(class_basename($this));
        $id = $this->getKey();

        return trim($prefix . '_' . $model . '_' . $id, '_');
    }

    /**
     * Generate a cache key specific to spintax rendering for this model.
     *
     * @return string Example: 'spintax_page_123'
     */
    public function getSpintaxCacheKey(): string
    {
        return $this->getRedisCacheKey('spintax');
    }
}
