<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MultiCmsLibrary\SharedModels\Database\Factories\BacklinkCategoryFactory;
use MultiCmsLibrary\SharedModels\Models\Traits\HasCacheKeys;
use MultiCmsLibrary\SharedModels\Models\Traits\HasSettings;

class BacklinkCategory extends Model
{
    use HasFactory;
    use HasSettings, HasCacheKeys;

    protected $fillable = [
        'slug',
        'name_nl',
        'name_en',
        'name_de',
        'name_fr',
        'name_es',
    ];

    public static function newFactory()
    {
        return BacklinkCategoryFactory::new();
    }

    /**
     * Get all backlinks associated with the category.
     */
    public function backlinks()
    {
        return $this->hasMany(Backlink::class, 'backlink_category_id');
    }
}
