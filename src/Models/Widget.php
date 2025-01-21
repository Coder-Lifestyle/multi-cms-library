<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $fillable = [
        'domain_id',
        'widget_area_id',
        'title',
        'type',
        'category',
        'content',
        'settings',
        'is_active',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
    
    public function widgetArea()
    {
        return $this->belongsTo(WidgetArea::class);
    }
}
