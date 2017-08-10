<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EducationOrder extends Model
{
    protected $table='education_order';
    protected $primaryKey='id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'education_id',
        'order_no',
        'status',
        'name',
        'level_1_id',
        'level_2_id',
        'level_3_id',
        'school_id',
        'studymode_id',
        'major',
        'fulltime_id',
        'notfulltime_id',
        'length',
        'province_desc',
        'coaches',
        'admission',
        'entry_fee',
        'market_fee',
        'kxl_fee',
    ];

    public $timestamps=true;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function school() {
        return $this->hasOne(EducationSchool::class, 'id', 'school_id');
    }
}
