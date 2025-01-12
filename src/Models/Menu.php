<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable = ['name', 'type'];

    /**
     * Relationship to get the columns of the menu.
     */
    public function columns(): HasMany
    {
        return $this->hasMany(Column::class);
    }
    /**
     * Relationship to get the areas of the menu.
     */
    public function areas()
{
    return $this->belongsToMany(Area::class, 'area_menu');
}
}
