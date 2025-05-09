<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Model;
use MultiCmsLibrary\SharedModels\Models\Traits\HasSettings;
use MultiCmsLibrary\SharedModels\Models\Traits\HasUniqueField;

class Area extends Model
{
    use HasSettings, HasUniqueField;

    protected $fillable = ['name', 'domain_id'];

    protected array $autoUniqueFields = [
        'name' => 'name',
    ];

    protected array $uniqueFieldScopes = [
        'name' => ['domain_id'],
    ];

    protected static function booted()
    {
        static::updated(function (Area $area) {
            if ($area->wasChanged('domain_id')) {
                $area->menus()->update([
                    'domain_id' => $area->domain_id,
                ]);
            }
        });
    }

    /**
     * Relationship to the Menu model.
     * Each area is associated with one menu.
     */
    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
}
