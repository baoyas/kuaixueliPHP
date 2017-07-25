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
    public static $province  = [
        '1' => '北京市',
        '2' => '天津市',
        '3' => '上海市',
        '4' => '重庆市',
        '5' => '河北省',
        '6' => '山西省',
        '7' => '辽宁省',
        '8' => '吉林省',
        '9' => '黑龙江省',
        '10' => '江苏省',
        '11' => '浙江省',
        '12' => '安徽省',
        '13' => '福建省',
        '14' => '江西省',
        '15' => '山东省',
        '16' => '河南省',
        '17' => '湖北省',
        '18' => '湖南省',
        '19' => '广东省',
        '20' => '海南省',
        '21' => '四川省',
        '22' => '贵州省',
        '23' => '云南省',
        '24' => '陕西省',
        '25' => '甘肃省',
        '26' => '青海省',
        '27' => '台湾省',
        '28' => '内蒙古自治区',
        '29' => '广西壮族自治区',
        '30' => '西藏自治区',
        '31' => '宁夏回族自治区',
        '32' => '新疆维吾尔自治区',
        '33' => '香港特别行政区',
        '34' => '澳门特别行政区',
    ];

    public function level() {
        return $this->hasOne(EducationLevel::class, 'id', 'level_id');
    }

    public function school() {
        return $this->hasOne(EducationSchool::class, 'id', 'school_id');
    }

    public function provinces() {
        return $this->belongsToMany(Province::class, 'education_province', 'education_id', 'province_id');
    }
}
