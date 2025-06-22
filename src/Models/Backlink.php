<?php

namespace MultiCmsLibrary\SharedModels\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MultiCmsLibrary\SharedModels\Database\Factories\BacklinkFactory;
use MultiCmsLibrary\SharedModels\Models\Traits\HasCacheKeys;
use MultiCmsLibrary\SharedModels\Models\Traits\HasSettings;

class Backlink extends Model
{
    use HasFactory;
    use HasSettings, HasCacheKeys;

    protected $fillable = [
        'customer_id',
        'page_id',
        'url',
        'title',
        'rel',
        'backlink_category_id',
        'subscription_type',
        'subscription_end_date',
        'stripe_subscription_id',
        'stripe_subscription_item_id',
        'is_active'
    ];

    public static function newFactory()
    {
        return BacklinkFactory::new();
    }

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
