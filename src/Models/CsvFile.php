<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Model;
use MultiCmsLibrary\SharedModels\Models\Traits\HasSettings;

class CsvFile extends Model {
    
    use HasSettings;

    protected $fillable = ['name', 'upload_method', 'file_path', 'file_url', 'delimiter', 'update_interval'];
    
    public function records() { return $this->hasMany(CsvRecord::class); }
}