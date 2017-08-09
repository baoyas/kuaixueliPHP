<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EducationSchool extends Model
{
    protected $table='education_school';
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

    public function province() {
        return $this->hasOne(Province::class, 'id', 'province_id');
    }
}
