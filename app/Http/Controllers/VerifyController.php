<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\Honeypays;
use Illuminate\Support\Facades\Mail;
use App\User;
use Log;

class VerifyController extends Controller
{
   public function verifyUser(Request $request, $email, $token){

   	$user = User::where('email', '=', $email)->where('verify', '=', $token)->where('active', '=', 0)->first();

   	if ($user) {

   		$user->update(['active' => 1, 'verify' => '' ]);

   		$email = $user->email;
      $number = $user->number;
      $subject = 'Registration successful';

      $message = 'You have successfully verified your email, you can now login at https://empower.honeypays.com.ng/login';
      
        Log::info($this->sms($number, urlencode($message)));
        Log::info($this->app($subject,$message,$email));

        Mail::to($email)->send(new Honeypays($message, $subject));

   		$request->session()->flash('success', 'Email verification successful, you can now login');
   		return redirect('/login');
   		
   	}else {
   		$request->session()->flash('failed', 'Invalid user email or token or user is already active.');
   		return redirect('/login');
   	}

   }


}
