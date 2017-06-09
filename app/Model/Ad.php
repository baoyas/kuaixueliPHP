<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $table='ad_object';
    protected $primaryKey='id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ad_object_name', 'ad_place_id', 'ad_skip_id', 'ad_object_aim', 'ad_start_at', 'ad_end_at', 'ad_object_sort', 'ad_object_power', 'ad_object_thumb'
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
