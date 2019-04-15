<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Address extends Model
{
    protected $table = 'address';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'longitude',
        'latitude',
        'place_id',
        'name',
        'city_id',
        'region_id'
    ];

    protected $hidden = [
        'place_id',
        'city_id',
        'region_id',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the region.
     */
    public function region()
    {
        return $this->belongsTo('App\Region');
    }

    /**
     * Get the city.
     */
    public function city()
    {
        return $this->belongsTo('App\City');
    }
}
