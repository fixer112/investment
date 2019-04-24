<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = 'historys';
	protected $guarded = [];
	protected $dates = ['invest_date', 'return_date', 'approved_date', 'paid_date'];
	
   public function user()
    {
    	return $this->belongsTo('App\User');
    }

    function roll(){
     	return $this->hasOne('App\Rollover');
     }
}
