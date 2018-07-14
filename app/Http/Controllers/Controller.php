<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sms($to, $message){
    	$username = env('SMS_USERNAME');
    	$password = env('SMS_PASSWORD');
    	$sender = 'HONEYPAYS';
    	$data = 'username='.$username.'&password='.$password.'&sender='.$sender.'&to='.$to.'&message='.$message;

    	$ch = curl_init('http://smsc.xwireless.net/API/WebSMS/Http/v1.0a/index.php?'.$data);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	$response = curl_exec($ch);
    	curl_close($ch);
    	return $response;
    }
    
    public function naira($number){
    return "N". number_format($number, 2);

    }

    public function randomnumber($len = 20){
	$char = '0123456789';
	$charlen = strlen($char);
	$randomstring = '';
	for ($i = 0; $i < $len ; $i++) {
		$randomstring .= $char[rand(0, $charlen-1)];
	}
	return $randomstring;
	}
}
