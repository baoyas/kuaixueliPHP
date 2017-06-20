<?php

namespace App\Model;

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
        'nickname', 'phone', 'accounts', 'address', 'sex', 'area', 'autograph', 'user_face', 'backgroud_pic', 'user_reg_time', 'statues', 'qq_party_login', 'wx_party_login', 'weibo_party_login', 'password',  'token', 'expire', 'is_del', 'push_code', 'model'
    ];

    public $timestamps=false;

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
}
