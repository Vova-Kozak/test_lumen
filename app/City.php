<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class City extends Model
{

    protected $table = 'city';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'region_id'
    ];

    protected $hidden = [
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
}
