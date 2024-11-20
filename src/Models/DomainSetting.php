<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DomainSetting extends Model
{
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
