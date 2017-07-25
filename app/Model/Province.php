<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;


class Province extends Model
{
    protected $table='province';
    protected $primaryKey='id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'level', 'name'
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
