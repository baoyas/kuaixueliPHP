<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $table='education';
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

    public function level() {
        return $this->hasOne(EducationLevel::class, 'id', 'level_id');
    }

    public function school() {
        return $this->hasOne(EducationSchool::class, 'id', 'school_id');
    }
}
