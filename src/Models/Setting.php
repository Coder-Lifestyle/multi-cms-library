<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'entity_type',
        'entity_id',
        'key',
        'value',
    ];

    public function entity(): MorphTo
    {
        return $this->morphTo('entity');
    }
}
