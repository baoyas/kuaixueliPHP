<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserCate extends Model
{
    protected $table='user_cate';
    protected $primaryKey='id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'cate_id'
    ];

    public $timestamps=false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public function cate() {
        return $this->belongsTo(Cate::class, 'cate_id', 'id');
    }
}
