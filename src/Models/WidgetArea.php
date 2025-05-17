<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Model;
use MultiCmsLibrary\SharedModels\Models\Traits\HasCacheKeys;
use MultiCmsLibrary\SharedModels\Models\Traits\HasSettings;

class WidgetArea extends Model
{
    use HasSettings, HasCacheKeys;

    protected $fillable = ['name'];

    public function widgets()
    {
        return $this->hasMany(Widget::class);
    }
}

