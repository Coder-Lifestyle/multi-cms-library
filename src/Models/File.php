<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Model;


class File extends Model
{
    protected $fillable = ['domain_id', 'file_name', 'file_path'];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
}
