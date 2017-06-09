<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Send extends Model
{
    protected $table='send';
    protected $primaryKey='id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'send_form_uid', 'send_form_username', 'send_content', 'send_users', 'send_time', 'send_group_id'
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
