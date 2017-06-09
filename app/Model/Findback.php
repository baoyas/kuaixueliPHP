<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Findback extends Model
{
    protected $table='findback';
    protected $primaryKey='id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'findback_uid', 'findback_content', 'findback_time', 'findback_handle'
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
