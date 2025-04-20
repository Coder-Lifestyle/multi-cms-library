<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Model;
use MultiCmsLibrary\SharedModels\Models\Traits\HasSettings;

class Area extends Model
{
    use HasSettings;

    protected $fillable = ['name', 'menu_id', 'domain_id'];

    /**
     * Relationship to the Menu model.
     * Each area is associated with one menu.
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
}
