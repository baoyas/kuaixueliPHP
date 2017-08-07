<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;

class EducationLevel extends Model
{
    use ModelTree, AdminBuilder;

    protected $table='education_level';
    protected $primaryKey='id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    ];

    public $timestamps=true;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public function parent()
    {
        return $this->belongsTo(EducationLevel::class, 'pid');
    }

    public function children()
    {
        return $this->hasMany(EducationLevel::class, 'pid');
    }

    public function brothers()
    {
        return $this->parent->children();
    }

    public static function options($id)
    {
        if (! $self = static::find($id)) {
            return [];
        }

        return $self->brothers()->pluck('name', 'id');
    }

    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('education_level');
        $this->titleColumn = 'name';
        $this->orderColumn = 'sort';
        $this->parentColumn = 'pid';
        parent::__construct($attributes);
    }
}
