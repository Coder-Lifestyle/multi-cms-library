<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Model;
use MultiCmsLibrary\SharedModels\Models\Traits\HasCacheKeys;

class SettingDefinition extends Model
{
    use HasCacheKeys;
    protected $fillable = [
        'entity_type',
        'key',
        'type',
        'default_value',
        'description',
        'required'
    ];

    protected $casts = [
        'required' => 'boolean',
    ];
    public static function forEntity(string $entityType)
    {
        return static::where('entity_type', $entityType)->get();
    }

    public function settings()
    {
        return $this->hasMany(Setting::class, 'key', 'key')
            ->where('entity_type', $this->entity_type);
    }
}
