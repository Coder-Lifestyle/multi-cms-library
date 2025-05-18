<?php

namespace MultiCmsLibrary\SharedModels\Cache;

use Illuminate\Database\Eloquent\Model;

class RedisKeyBuilder
{
    protected string $prefix;

    public function __construct()
    {
        $this->prefix = 'page_cache';
    }

    public function prefix(): string
    {
        return $this->prefix;
    }

    public function domainTag(int $domainId): string
    {
        return "domain_{$domainId}";
    }

    public function viewTag(Model $model): string
    {
        return method_exists($model, 'getViewCacheTag')
            ? $model->getViewCacheTag()
            : strtolower(class_basename($model)) . '_view';
    }

    public function modelDomainsKey(Model $model): string
    {
        return "{$this->prefix}:{$model->getCacheTag()}:domains";
    }

    public function modelUsedPagesKey(Model $model): string
    {
        return "{$this->prefix}:{$model->getCacheTag()}:used_pages";
    }

    public function domainPagesKey(int $domainId): string
    {
        return "{$this->prefix}:domain_{$domainId}:pages";
    }

    public function domainRelatedKey(int $domainId, string $type): string
    {
        return "{$this->prefix}:domain_{$domainId}:related_{$type}";
    }

    public function staticKey(string $type, int $domainId, string $name = 'main'): string
    {
        return "ui:domain_{$domainId}:{$type}:{$name}";
    }
}
