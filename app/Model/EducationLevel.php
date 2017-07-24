<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EducationLevel extends Model
{
    protected $table='education_level';
    protected $primaryKey='id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
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
