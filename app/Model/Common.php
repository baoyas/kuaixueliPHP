<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Common extends Model
{
    protected $table='common';
    protected $primaryKey='id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'common_id', 'form_uid', 'to_uid', 'common_time', 'common_content', 'common_type'
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
