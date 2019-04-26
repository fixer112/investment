<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    //
    protected $guarded = [];
    
    function history(){
     	return $this->hasOne('App\History');
     }

     public function user()
    {
    	return $this->belongsTo('App\User');
    }
}
