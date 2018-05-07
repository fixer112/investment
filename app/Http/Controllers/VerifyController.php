<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\Honeypays;
use Illuminate\Support\Facades\Mail;
use App\User;

class VerifyController extends Controller
{
   public function verify(Request $request, $email, $token){

   	$user = User::where('email', '=', $email)->where('verify', '=', $token)->where('active', '=', 0)->first();

   	if ($user) {

   		$user->update(['active' => 1, 'verify' => '' ]);

   		$email = $user->email;
      $number = $user->number;
      $subject = 'Registration successful';

      $message = 'You have successfully verified your email, you can now login at https://investor.honeypays.com.ng/login';
      
        $this->sms($number, urlencode($message));

        Mail::to($email)->send(new Honeypays($message, $subject));

   		$request->session()->flash('success', 'Email verification successful, you can now login');
   		return redirect('/login');
   		
   	}else {
   		$request->session()->flash('failed', 'Invalid user email or token or user is already active.');
   		return redirect('/login');
   	}

   }

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
}
