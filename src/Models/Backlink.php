<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Backlink extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'domain_id',
        'url',
        'title',
        'rel',
        'backlink_category_id',
    ];

    /**
     * Get the category associated with the backlink.
     */
    public function category()
    {
        return $this->belongsTo(BacklinkCategory::class, 'backlink_category_id');
    }

    /**
     * Get the page associated with the backlink.
     */
    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    /**
     * Get the page associated with the backlink.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'page_id');
    }


    // You can add other relationships here (e.g., customer, domain) if those models exist.
}
