<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MultiCmsLibrary\SharedModels\Cache\RedisKeyBuilder;
use MultiCmsLibrary\SharedModels\Models\Traits\HasCacheKeys;
use MultiCmsLibrary\SharedModels\Models\Traits\HasSettings;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class Product extends Model
{
    use HasSettings, HasCacheKeys;

    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'price',
        'sale_price',
        'domain_id',
        'category_id',
        'image_url',
        'brand_name',
        'stock_quantity',
        'rating',
        'affiliate_link'
    ];

    /**
     * Get the domain that owns the product.
     */
    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
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
