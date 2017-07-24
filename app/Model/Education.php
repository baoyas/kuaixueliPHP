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

    public static $studyMode = ['1'=>'自学考试', '2'=>'电大', '3'=>'远程教育', '4'=>'成人高考'];
    public static $fullTime  = ['1'=>'是', '2'=>'否'];

    public function level() {
        return $this->hasOne(EducationLevel::class, 'id', 'level_id');
    }

    public function school() {
        return $this->hasOne(EducationSchool::class, 'id', 'school_id');
    }
}
