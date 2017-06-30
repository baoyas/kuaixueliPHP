<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;

class UserArea extends Model
{
    use ModelTree, AdminBuilder;

    protected $table='user_area';
    protected $primaryKey='id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'real_name', 'mobile', 'detail', 'province_id', 'city_id', 'area_id', 'is_default', 'is_delete'
    ];

    public $timestamps=true;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public function province() {
        return $this->belongsTo(Area::class, 'province_id', 'id');
    }

    public function city() {
        return $this->belongsTo(Area::class, 'city_id', 'id');
    }

    public function area() {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);
        parent::__construct($attributes);
    }
}
