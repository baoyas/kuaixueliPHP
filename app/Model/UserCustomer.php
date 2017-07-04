<?php

namespace App\Model;

use App\Model\User;
use Illuminate\Database\Eloquent\Model;

class UserCustomer extends Model
{
    use InsertOnDuplicateKey;
    protected $table='user_customer';
    protected $primaryKey='id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'service_user_id', 'created_at', 'updated_at'
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
        //return $this->hasOne(User::class, 'id', 'user_id');
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
