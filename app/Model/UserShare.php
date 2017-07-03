<?php

namespace App\Model;

use App\Model\User;
use Illuminate\Database\Eloquent\Model;

class UserShare extends Model
{
    const BIZ_TYPE_BUY = 1;    //购买
    const BIZ_TYPE_SELL = 2;   //出售
    const BIZ_TYPE_CIRCLE = 3; //朋友圈
    const BIZ_TYPE_LDL = 4;    //了当了

    const CHANNEL_MICRO_BLOG = 1;
    const CHANNEL_QQ_FRINED = 3;
    const CHANNEL_QQ_ZONE = 4;
    const CHANNEL_WECHAT_FRIEND = 5;
    const CHANNEL_WECHAT_ZONE = 6;
    const CHANNEL_LDL = 7;

    use InsertOnDuplicateKey;
    protected $table='user_share';
    protected $primaryKey='id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'biz_type', 'biz_id', 'channel', 'created_at', 'updated_at'
    ];

    public $timestamps=true;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public function user() {
        //return $this->hasOne(User::class, 'id', 'user_id');
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
