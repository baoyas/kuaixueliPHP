<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Push extends Model
{
    protected $table='push';
    protected $primaryKey='id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'push_title', 'push_content', 'push_model'
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
