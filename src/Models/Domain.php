<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use MultiCmsLibrary\SharedModels\Models\Traits\HasCacheKeys;
use MultiCmsLibrary\SharedModels\Models\Traits\HasSettings;
use ReflectionClass;

class Domain extends Model
{

    public const SHOP_PATH         = 'shop';
    public const CATEGORY_PATH     = 'category';
    public const CATEGORY_LIST_PATH = 'categories';
    public const PRODUCT_PATH      = 'product';

    use HasSettings, HasCacheKeys;
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


    public function areas()
    {
        return $this->hasMany(Area::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function widgets()
    {
        return $this->hasMany(Widget::class);
    }

    public function getMetrics()
    {
        return $this->settings
            ->filter(fn($setting) => Str::startsWith($setting->key, 'metrics_'))
            ->mapWithKeys(function ($setting) {
                $cleanKey = Str::after($setting->key, 'metrics_');
                return [$cleanKey => $setting->value];
            });
    }

    public function getDomainCacheTag(): string
    {
        return "domain:{$this->id}";
    }

    public function getRouteCacheKey(string $path): string
    {
        return "route:{$this->id}:" . md5($path);
    }

    public function getCacheTagsForPath(string $path, ?Model $model = null): array
    {
        $tags = [$this->getDomainCacheTag()];

        $segments = array_filter(explode('/', $path), fn($s) => $s !== '');

        foreach (array_intersect($segments, self::getStaticPaths()) as $seg) {
            $tags[] = $seg;
        }

        if ($model && method_exists($model, 'getCacheTags')) {
            $tags = array_merge($tags, $model->getCacheTags());
        }

        return array_unique($tags);
    }

    /**
     * Builds cache tags for a specific model within this domain.
     *
     * Always includes domain tag, and model tags if available.
     *
     * @param Model $model
     * @return string[]
     */
    public function getCacheTagsForModel(Model $model): array
    {
        $tags = [$this->getDomainCacheTag()];

        if (method_exists($model, 'getCacheTags')) {
            $tags = array_merge($tags, $model->getCacheTags());
        }

        return array_unique($tags);
    }

    /**
     * Returns all static route segment values defined via *_PATH constants.
     *
     * @return string[]
     */
    public static function getStaticPaths(): array
    {
        $rc = new ReflectionClass(self::class);

        return array_values(array_filter(
            $rc->getConstants(),
            fn($val, $key) => str_ends_with($key, '_PATH'),
            ARRAY_FILTER_USE_BOTH
        ));
    }

    /**
     * Returns a cache configuration array for a given path and optional model.
     *
     * Used for cache read/write operations (key + tags).
     *
     * @param string $path
     * @param Model|null $model
     * @return array{key: string, tags: string[]}
     */
    public function getCacheConfigForPath(string $path, ?Model $model = null): array
    {
        return [
            'key'  => $this->getRouteCacheKey($path),
            'tags' => $this->getCacheTagsForPath($path, $model),
        ];
    }

    public function flushModelCache(Model $model): void
    {
        // Flush only the specific model's tag (e.g. "page_805")
        Cache::store('redis')
            ->tags([$model->getCacheTag()])
            ->flush();
    }


    public function flushModelGroupCache(Model $model): void
    {
        if (!method_exists($model, 'getCacheTags')) {
            return;
        }

        Cache::store('redis')
            ->tags([$this->getDomainCacheTag(), $model->getCacheTags()])->flush();
    }

    public function flushByPath(string $path): void
    {
        Cache::store('redis')
            ->tags($this->getCacheTagsForPath($path))
            ->flush();
    }
}
