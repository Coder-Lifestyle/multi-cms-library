<?php

namespace MultiCmsLibrary\SharedModels\Models\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use MultiCmsLibrary\SharedModels\Models\Setting;
use MultiCmsLibrary\SharedModels\Models\SettingDefinition;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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

    /**
     * Apply an array of ['key'=>'…','value'=>'…'] to this model.
     */
    public function applySettings(array $settings): void
    {
        foreach ($settings as $s) {
            if (! is_null($s['value'])) {
                $this->setSetting($s['key'], (string) $s['value']);
            }
        }
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

    /**
     * Validation rules for incoming settings payload.
     */
    public static function settingsValidationRules(): array
    {
        return [
            'settings'            => ['nullable', 'array'],
            'settings.*.key'      => ['required', Rule::in(static::allowedSettingsKeys())],
            'settings.*.value'    => ['nullable', 'string'],
        ];
    }

    /**
     * Create a new model *and* its settings, all inside one DB-transaction,
     * and also run settings validation automatically.
     *
     * @param  array  $attributes   The full payload, including any 'settings' key.
     * @param  array  $baseRules    Your model-specific validation rules.
     * @param  array  $messages     (optional) custom validation messages.
     * @return static
     */
    public static function createWithSettingsTransaction(
        array $attributes,
        array $baseRules,
        array $messages = []
    ) {
        // 1) merge base rules + settings rules
        $rules = array_merge($baseRules, static::settingsValidationRules());

        // 2) validate everything in one go
        $validated = Validator::make($attributes, $rules, $messages)
            ->validate();

        // 3) inside a transaction, extract settings, create & apply
        return DB::transaction(function () use ($validated) {
            $settings = $validated['settings'] ?? [];
            unset($validated['settings']);

            /** @var static $model */
            $model = static::create($validated);

            $model->applySettings($settings);

            return $model;
        });
    }
}
