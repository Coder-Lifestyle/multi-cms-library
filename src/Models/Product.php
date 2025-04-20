<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MultiCmsLibrary\SharedModels\Models\Traits\HasSettings;

class Product extends Model
{
    use HasSettings;

    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'price',
        'sale_price',
        'domain_id',
        'category_id',
        'image_url',
        'brand_name',
        'stock_quantity',
        'rating'
    ];

    /**
     * Get the domain that owns the product.
     */
    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
