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
		    		'name' => 'required|string|max:50',
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
                    'mode_id' => 'required|string',
		            'identity' => 'nullable|image|mimes:jpeg,jpg,png|max:1024',
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
            'name' => ucwords($request->name),
            'referal' => $request->mentor,
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

        $email = $request->email;
      $number = $request->number;
      $subject = 'Verify Email';

       $link = url("/verify/".$request->email.'/'.$verify);

       $message = 'Please complete your registration by verifing your email, follow link below to verify your email '.$link;
      
        $this->sms($number, urlencode($message));

        Mail::to($email)->send(new Honeypays($message, $link, $subject));
    			
        $request->session()->flash('success', 'Successful, please check your email inbox or email spam folder to verify email and complete registration.');

        return back();
}

	
}
