<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Stat
 *
 * @mixin Builder
 * @package App
 */
class Recent extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at'];    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'website_id',
        'page',
        'referrer',
        'os',
        'browser',
        'device',
        'country',
        'city',
        'language',
        'ip',
        'created_at'
    ];

    /**
     * Get the website that owns the recent.
     */
    public function website()
    {
        return $this->belongsTo('App\Models\Website');
    }
}
