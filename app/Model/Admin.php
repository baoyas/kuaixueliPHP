<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    //后台用户登录模型
    protected $table='admin';
    protected $primaryKey='admin_id';
    public $timestamps=false;
}
