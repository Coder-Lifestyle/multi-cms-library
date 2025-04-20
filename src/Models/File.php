<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Model;
use MultiCmsLibrary\SharedModels\Models\Traits\HasSettings;


class File extends Model
{
    use HasSettings;

    protected $fillable = ['domain_id', 'file_name', 'file_path'];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
}
