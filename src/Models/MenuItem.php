<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MultiCmsLibrary\SharedModels\Models\Traits\HasCacheKeys;
use MultiCmsLibrary\SharedModels\Models\Traits\HasSettings;

class MenuItem extends Model
{
    use HasSettings, HasCacheKeys;

    protected $fillable = ['column_id', 'parent_id', 'name', 'url'];

    /**
     * Relationship to get the column this menu item belongs to.
     */
    public function column(): BelongsTo
    {
        return $this->belongsTo(Column::class);
    }

    /**
     * Self-referencing relationship to get the parent menu item.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }
}
