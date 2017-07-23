<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserRedpack extends Model
{
    use InsertOnDuplicateKey;
    protected $table='user_redpack';
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
    
    public function user() {
        return $this->belongsTo(User::class, 'biz_id', 'id');
    }
}
