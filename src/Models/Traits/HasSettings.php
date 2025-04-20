<?php

namespace MultiCmsLibrary\SharedModels\Models\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use MultiCmsLibrary\SharedModels\Models\Setting;
use MultiCmsLibrary\SharedModels\Models\SettingDefinition;

trait HasSettings
{

    public function settings(): MorphMany
    {
        return $this->morphMany(Setting::class, 'entity');
    }


    public function getSetting(string $key, $default = null)
    {
        return $this->settings()
                    ->where('key', $key)
                    ->value('value')
            ?? $default;
    }


    public function setSetting(string $key, $value)
    {
        return $this->settings()->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }


    public static function allowedSettingsDefinitions()
    {
        return SettingDefinition::forEntity(static::class);
    }


    public static function allowedSettingsKeys(): array
    {
        return static::allowedSettingsDefinitions()
                     ->pluck('key')
                     ->toArray();
    }
}
