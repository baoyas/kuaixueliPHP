<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RewardConf extends Model
{
    use InsertOnDuplicateKey;
    protected $table='reward_conf';
    protected $primaryKey='id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rname', 'type'
    ];

    public $timestamps=true;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
