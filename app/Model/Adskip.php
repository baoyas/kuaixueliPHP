<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Adskip extends Model
{
    protected $table='ad_skip';
    protected $primaryKey='id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ad_skip_name', 'ad_skip_describe', 'ad_skip_power'
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
