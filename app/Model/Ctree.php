<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;

class Ctree extends Model
{
    use ModelTree, AdminBuilder;

    protected $table='cate';
    protected $primaryKey='id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cate_name', 'cate_sort', 'cate_power', 'pid', 'cate_level'
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

        $this->setTable('cate');
        $this->titleColumn = 'cate_name';
        $this->orderColumn = 'cate_sort';
        $this->parentColumn = 'pid';
        parent::__construct($attributes);
    }
}
