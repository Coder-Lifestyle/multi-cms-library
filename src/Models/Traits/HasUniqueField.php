<?php

namespace MultiCmsLibrary\SharedModels\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use MultiCmsLibrary\SharedModels\Services\Generators\UniqueFieldGenerator;

trait HasUniqueField
{
    public static function bootHasUniqueField()
    {
        static::creating(function (Model $model) {
            foreach ($model->getAutoUniqueFields() as $field => $source) {
                $model->{$field} = UniqueFieldGenerator::make($model)
                    ->generate($field, $model->{$field} ?? $model->{$source});
            }
        });

        static::updating(function (Model $model) {
            foreach ($model->getAutoUniqueFields() as $field => $source) {
                if ($model->isDirty($field) || $model->isDirty(...$model->getUniqueScopeFields($field))) {
                    $model->{$field} = UniqueFieldGenerator::make($model)
                        ->generate($field, $model->{$field} ?? $model->{$source}, $model->getKey());
                }
            }
        });
    }

    public function getAutoUniqueFields(): array
    {
        return property_exists($this, 'autoUniqueFields') ? $this->autoUniqueFields : [];
    }

    public function getUniqueScopeFields(string $field): array
    {
        return $this->uniqueFieldScopes[$field] ?? [];
    }
}
