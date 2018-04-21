<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = 'historys';
	protected $guarded = [];
	
   public function user()
    {
    	return $this->belongsTo('App\User');
    }
}
