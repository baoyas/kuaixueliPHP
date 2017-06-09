<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table='group';
    protected $primaryKey='id';
    /**
     * @group_name 群组名称 此属性为必须的
     * @group_desc 群组描述 此属性为必须的
     * @group_public 是否是公开群  此属性为必须的 true or false
     * @group_maxusers  群组成员最大数（包括群主），值为数值类型，默认值200，最大值2000，此属性为可选的。
     * @group_approval 加入公开群是否需要批准 没有这个属性的话默认是true, 此属性为可选的
     * @group_owner 群组的管理员 此属性为必须的
     */
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_name', 'group_desc', 'group_public', 'group_maxusers',  'group_approval', 'group_owner', 'owner_uid', 'group_id', 'group_face', 'group_create_time'
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
