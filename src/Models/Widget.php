<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $fillable = ['widget_area_id', 'class'];
    protected $casts = ['settings' => 'json'];

    public function widgetArea()
    {
        return $this->belongsTo(WidgetArea::class);
    }
}
