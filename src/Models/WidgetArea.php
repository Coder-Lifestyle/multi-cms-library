<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Model;

class WidgetArea extends Model
{
    protected $fillable = ['name'];

    public function widgets()
    {
        return $this->hasMany(Widget::class);
    }
}

