<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Mail\Honeypays;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function register(Request $request)
{
// validate the info, create rules for the inputs
		$rules = array(
		    		'name' => 'required|string|max:255',
		            'email' => 'required|string|email|max:255|unique:users',
		            'password' => 'required|string|min:5|confirmed',
		            'number' => 'required|numeric|unique:users',
		            'mentor' => 'required|numeric|exists:users',
		            'acc_no' => 'required|numeric',
		            'acc_name' => 'required|string',
		            'bank_name' => 'required|string',
		            'addr' => 'required|string',
		            'city' => 'required|string',
		            'state' => 'required|string',
		            'identity' => 'image|mimes:jpeg,jpg,png|max:1024',
		            'accept' => 'required_without_all',
		);

	$validator = $this->validate($request, $rules);

	$identityname ="";
	$verify = $this->randomstring();

        if ($request->file('identity')) {
        $destination = public_path('/images');
        $identity = $request->file('identity');
        $identityname = $request->email."-"."identity".".".$identity->getClientOriginalExtension();
        $identity->move($destination, $identityname);
            
        }

       User::create([
            'name' => $request->name,
            'referral' => $request->mentor,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'number' => $request->number,
            'acc_no' => $request->acc_no,
            'acc_name' => $request->acc_name,
            'bank_name' => $request->bank_name,
            'addr' => $request->addr,
            'city' => $request->city,
            'state' => $request->state,
            'mode_id' => $request->mode_id,
            'identity' => '/images'.'/'.$identityname,
            'verify' => $verify,

        ]);

       $to = $request->email;
       $subject = 'Verify Email';

       $link = 'https://investor.honeypays.com.ng/verify/'.$request->email.'/'.$verify;

       $message = 'Please complete your registration by verifing your email, follow link below to verify your email '.$link;

        //$this->sms($to, urlencode($message));

        Mail::to($to)->send(new Honeypays($message, $subject));
    			
        $request->session()->flash('success', 'Successful, please check your email inbox or email spam folder to verify email and complete registration.');

        return redirect('/register');
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

    public function naira($number){
	return "N". number_format($number, 2);

	}

	public function randomstring($len = 20){
	$char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charlen = strlen($char);
	$randomstring = '';
	for ($i = 0; $i < $len ; $i++) {
		$randomstring .= $char[rand(0, $charlen-1)];
	}
	return $randomstring;
	}

	
}
