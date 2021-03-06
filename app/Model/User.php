<?php

namespace App\Model;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Encore\Admin\Traits\AdminBuilder;

class User extends Model
{
    use AdminBuilder;
    protected $table='user';
    protected $primaryKey='id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'realname', 'mobile', 'email', 'password'
    ];

    public $timestamps=true;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('user');

        parent::__construct($attributes);
    }
    
    public static function addPoints($uid, $points) {
        $date = date('Y-m-d');
        $cacheKey = "user_points_day_{$date}_{$uid}";
        $pointsDay = Cache::get($cacheKey);
        if($pointsDay <= 300) {
            User::find($uid)->increment('points', $points);
            Cache::increment($cacheKey, $points);
            //UserShare::create(['user_id'=>$uid, 'biz_type'=>6, config('web.RELEASE_SELL_POINTS')]);
        }
    }

    public static function addMoney($uid, $money, $biz_type) {
        $date = date('Y-m-d');
        $cacheKey = "user_money_day_{$date}_{$uid}";
        $pointsDay = Cache::get($cacheKey);
        if($pointsDay <= 30000) {
            User::find($uid)->increment('money', $money);
            UserMoney::create(['user_id'=>$uid, 'biz_type'=>$biz_type, 'flow_type'=>1, 'value'=>$money]);
            Cache::increment($cacheKey, $money);
        }
    }
}
