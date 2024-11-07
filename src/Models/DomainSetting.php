<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Model;

class DomainSetting extends Model
{
    protected $fillable = ['domain_id', 'key', 'value'];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
}
