<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table='config';
    protected $primaryKey='conf_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'conf_id', 'conf_title', 'conf_name', 'conf_content', 'conf_order', 'conf_tips', 'field_type', 'field_value', 'is_system'
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
