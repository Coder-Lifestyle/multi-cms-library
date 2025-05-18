<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use MultiCmsLibrary\SharedModels\Models\Traits\HasCacheKeys;
use MultiCmsLibrary\SharedModels\Models\Traits\HasSettings;

class Column extends Model
{
    use HasSettings, HasCacheKeys;

    protected $fillable = ['menu_id', 'name', 'position'];

    /**
     * Relationship to get the menu this column belongs to.
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Relationship to get the menu items of the column.
     */
    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }
}
