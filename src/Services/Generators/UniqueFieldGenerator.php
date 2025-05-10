<?php

namespace MultiCmsLibrary\SharedModels\Services\Generators;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class UniqueFieldGenerator
{
    protected Model $model;

    public static function make(Model $model): self
    {
        return new self($model);
    }

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function generate(string $field, string $base, ?int $ignoreId = null, ?Collection $existing = null): string
    {
        $original = $this->normalize($base, $field);
        $value = $original;
        $i = 1;

        $existing = $existing ?? $this->queryExisting($field, $original, $ignoreId);

        while ($existing->contains($value)) {
            $value = "{$original}-{$i}";
            $i++;
        }

        return $value;
    }

    protected function normalize(string $base, string $field): string
    {
        return Str::slug($base);
    }

    protected function queryExisting(string $field, string $original, ?int $ignoreId): Collection
    {
        $query = $this->model->newQuery()->where($field, 'like', "{$original}%");

        if (method_exists($this->model, 'getUniqueScopeFields')) {
            foreach ($this->model->getUniqueScopeFields($field) as $scopeField) {
                $query->where($scopeField, $this->model->{$scopeField});
            }
        }

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->pluck($field);
    }
}
