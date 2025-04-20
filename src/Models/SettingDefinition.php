<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Model;

class SettingDefinition extends Model
{
    protected $fillable = [
        'entity_type',
        'key',
        'type',
        'default_value',
        'description',
    ];

    public static function forEntity(string $entityType)
    {
        return static::where('entity_type', $entityType)->get();
    }
}
