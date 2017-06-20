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
        'cate_name', 'cate_sort', 'cate_power', 'pid'
    ];

    public $timestamps=false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public function trees ()
    {
        $categorys = $this->orderBy('cate_sort', 'asc')->get();
        return $this->gettree($categorys, '_cate_name', 'cate_name', 'id', 'pid', 0);
    }

    //树形结构
    public function gettree($data, $prefix, $cate_name, $field_id='id',$field_pid='pid', $pid=0)
    {
        $arr = [];
        foreach($data as $k => $v)
        {
            if ($v->$field_pid == $pid)
            {
                $data[$k][$prefix] = $data[$k][$cate_name];
                $data[$k]['over'] = 0;
                $arr[] = $data[$k];
                foreach($data as $m => $n)
                {
                    if ($n->$field_pid == $v->$field_id)
                    {
                        $data[$m][$prefix] = '├─ '. $data[$m][$cate_name];
                        $data[$m]['over'] = 0;
                        $arr[] = $data[$m];
                        foreach ($data as $j=>$l)
                        {
                            if ($l->$field_pid == $n->$field_id)
                            {
                                $data[$j][$prefix] = '　├─' . $data[$j][$cate_name];
                                $data[$j]['over'] = 1;
                                $arr[] = $data[$j];
                            }
                        }
                    }
                }
            }
        }
        return $arr;
    }

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
