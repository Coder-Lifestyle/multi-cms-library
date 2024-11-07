<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'domain_id', 'parent_id'];

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }


    /**
     * Relationship to get the parent category.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Relationship to get the subcategories (children).
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get the full slug path, including parent categories.
     */
    public function getFullSlugPath(): string
    {
        if ($this->parent) {
            return $this->parent->getFullSlugPath() . '/' . $this->slug;
        }

        return $this->slug;
    }

}

