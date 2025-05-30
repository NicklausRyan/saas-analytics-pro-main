<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

/**
 * Class Website
 *
 * @mixin Builder
 * @package App
 */
class Website extends Model
{
    /**
     * @param Builder $query
     * @param $value
     * @return Builder
     */
    public function scopeSearchDomain(Builder $query, $value)
    {
        return $query->where('domain', 'like', '%' . $value . '%');
    }

    /**
     * Get the user that owns the website.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }

    /**
     * @param Builder $query
     * @param $value
     * @return Builder
     */
    public function scopeOfUser(Builder $query, $value)
    {
        return $query->where('user_id', '=', $value);
    }

    /**
     * Get the visitors count for a specific date range.
     *
     * @return Model|\Illuminate\Database\Eloquent\Relations\HasMany|object|null
     */
    public function visitors()
    {
        return $this->hasMany('App\Models\Stat', 'website_id', 'id')
            ->where('name', '=', 'visitors');
    }

    /**
     * Get the pageviews count for a specific date range.
     *
     * @return Model|\Illuminate\Database\Eloquent\Relations\HasMany|object|null
     */
    public function pageviews()
    {
        return $this->hasMany('App\Models\Stat', 'website_id', 'id')
            ->where('name', '=', 'pageviews');
    }

    /**
     * Get the website's stats.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stats()
    {
        return $this->hasMany('App\Models\Stat')->where('website_id', $this->id);
    }    /**
     * Get the website's recent stats.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recents()
    {
        return $this->hasMany('App\Models\Recent')->where('website_id', $this->id);
    }
    
    /**
     * Get the website's revenue data.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function revenue()
    {
        return $this->hasMany('App\Models\Revenue');
    }

    /**
     * Encrypt the website's password.
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Crypt::encryptString($value);
    }/**
     * Set the website's Stripe API key.
     *
     * @param $value
     */
    public function setStripeApiKeyAttribute($value)
    {
        $this->attributes['stripe_api_key'] = $value ? Crypt::encryptString($value) : null;
    }

    /**
     * Get the decrypted Stripe API key.
     *
     * @param $value
     * @return string|null
     */
    public function getStripeApiKeyAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    /**
     * Decrypt the website's password.
     *
     * @param $value
     * @return string
     */
    public function getPasswordAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return null;
        }
    }
}
