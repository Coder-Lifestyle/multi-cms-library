<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['title', 'body', 'domain_id', 'category_id', 'slug', 'featured_image'];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * A page can have multiple tags.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps(); // Many-to-many relation
    }
}

