<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Model;
use MultiCmsLibrary\SharedModels\Models\Traits\HasSettings;

class WidgetArea extends Model
{
    use HasSettings;

    protected $fillable = ['name'];

    public function widgets()
    {
        return $this->hasMany(Widget::class);
    }
}

