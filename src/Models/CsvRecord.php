<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Model;

class CsvRecord extends Model {
    use MultiCmsLibrary\SharedModels\Models\Traits\HasSettings;

    use HasSettings;

    protected $fillable = ['csv_file_id', 'data'];

    protected $casts = ['data' => 'array'];
}