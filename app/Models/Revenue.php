<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Revenue extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'website_id', 'amount', 'currency', 'order_id', 'visitor_id', 'source', 'date'
    ];

    /**
     * Get the website that owns the revenue entry.
     */
    public function website()
    {
        return $this->belongsTo('App\Models\Website');
    }

    /**
     * Scope a query to filter by website ID.
     *
     * @param Builder $query
     * @param int $websiteId
     * @return Builder
     */
    public function scopeOfWebsite(Builder $query, $websiteId)
    {
        return $query->where('website_id', '=', $websiteId);
    }

    /**
     * Scope a query to filter by date range.
     *
     * @param Builder $query
     * @param string $from
     * @param string $to
     * @return Builder
     */
    public function scopeBetweenDates(Builder $query, $from, $to)
    {
        return $query->whereBetween('date', [$from, $to]);
    }
}
