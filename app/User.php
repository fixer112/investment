<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    /*protected $fillable = [
        'name', 'email', 'password',
    ];*/

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $guarded = [];

    protected $table = 'users';

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function history()
    {
        return $this->hasMany('App\History');
    }

    public function roll()
    {
        return $this->hasMany('App\Rollover');
    }

    public function refund()
    {
        return $this->hasMany('App\Refund');
    }
}
