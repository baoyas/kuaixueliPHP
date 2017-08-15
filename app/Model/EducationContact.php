<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;

class EducationContact extends Model
{
    use ModelTree, AdminBuilder;

    protected $table='education_contact';
    protected $primaryKey='id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'education_id', 'account', 'atype', 'realname', 'scope_desc', 'headurl'
    ];

    public $timestamps=true;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
