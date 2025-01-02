<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = ['name', 'menu_id'];

    /**
     * Relationship to the Menu model.
     */
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'area_menu');
    }
}
