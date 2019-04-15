<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'region';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
     * Get the addresses for the region.
     */
    public function addresses()
    {
        return $this->hasMany('App\Address');
    }

    /**
     * Get the cities for the region.
     */
    public function cities()
    {
        return $this->hasMany('App\City');
    }
}
