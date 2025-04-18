<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable = ['name', 'type', 'area_id', 'domain_id'];

    /**
     * Relationship to the Area model.
     * Each menu is associated with one area.
     */
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Relationship to get the columns of the menu.
     */
    public function columns(): HasMany
    {
        return $this->hasMany(Column::class);
    }

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
}
