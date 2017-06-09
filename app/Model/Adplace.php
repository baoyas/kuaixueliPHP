<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Adplace extends Model
{
    protected $table='ad_place';
    protected $primaryKey='id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ad_place_name', 'ad_place_power'
    ];

    public $timestamps=false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
