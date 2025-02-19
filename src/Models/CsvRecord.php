<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Model;

class CsvRecord extends Model {

    protected $fillable = ['csv_file_id', 'data'];

    protected $casts = ['data' => 'array'];
}