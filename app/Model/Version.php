<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    protected $table='version';
    protected $primaryKey='id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ver_number', 'ver_terminal', 'ver_content', 'ver_create_at'
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
