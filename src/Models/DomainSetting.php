<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Model;
use MultiCmsLibrary\SharedModels\Models\Traits\HasCacheKeys;

class DomainSetting extends Model
{
    use HasCacheKeys;
    protected $fillable = ['domain_id', 'key', 'value'];

    public static function getSetting($domainId, $key, $default = null)
    {
        $setting = self::where('domain_id', $domainId)->where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function setSetting($domainId, $key, $value)
    {
        return self::updateOrCreate(
            ['domain_id' => $domainId, 'key' => $key],
            ['value' => $value]
        );
    }
}
