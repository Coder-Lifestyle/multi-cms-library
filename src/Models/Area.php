<?php 

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = ['name', 'menu_id'];

    /**
     * Relationship to the Menu model.
     * Each area is associated with one menu.
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
