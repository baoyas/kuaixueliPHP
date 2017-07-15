<?php

namespace App\Model;

use App\Model\User;
use Illuminate\Database\Eloquent\Model;

class Thumbs extends Model
{
    protected $table='sell_thumbs';
    protected $primaryKey='id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'thumbs_uid', 'thumbs_sell_id', 'thumbs_time', 'is_friends'
    ];

    public $timestamps=false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];
    
    public function User() {
        return $this->belongsTo(User::class, 'thumbs_uid', 'id');
    }
}
