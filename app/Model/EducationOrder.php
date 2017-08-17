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
        'order_no',
        'status',
        'fee',
    ];

    public $timestamps=true;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function products() {
        return $this->hasMany(EducationOrderProduct::class, 'order_id');
    }
}
