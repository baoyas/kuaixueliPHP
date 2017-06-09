<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Userremark extends Model
{
    protected $table='user_remark';
    protected $primaryKey='id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid', 'friends_phone', 'remarks', 'describes', 'remark_phone'
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
