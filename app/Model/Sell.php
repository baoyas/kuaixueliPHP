<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Sell extends Model
{
    protected $table='sell';
    protected $primaryKey='id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sell_title', 'sell_pic', 'cate_id', 'sell_describe', 'sell_price', 'sell_price_max', 'sell_area', 'sell_auth', 'is_del', 'recommend', 'is_sell', 'sell_time', 'sell_order', 'sell_uid', 'is_circle', 'sell_video', 'sell_video_pic', 'sell_up_time'
    ];

    public $timestamps=false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
