<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name', 'slug', 'domain_id'];

    public function pages()
    {
        return $this->belongsToMany(Page::class);
    }

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
}

