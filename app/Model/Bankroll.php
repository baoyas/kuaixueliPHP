<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * 资金流向 后台更新用户金额 用户充值
 * Class Bankroll
 * @package App\Model
 */
class Bankroll extends Model
{
    protected $table='bankroll';
    protected $primaryKey='id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bank_uid', 'bank_event', 'bank_money_type', 'bank_money', 'bank_terrace', 'bank_creatr_time', 'bank_terrace_type', 'serial_number', 'wx_order_sn', 'wx_pay_statues', 'wx_pay_money'
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
