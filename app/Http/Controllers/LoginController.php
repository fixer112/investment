<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\User;

class LoginController extends Controller
{
   public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function login(Request $request)
{
// validate the info, create rules for the inputs
$rules = array(
    'email'    => 'required|exists:users|email', // make sure the email is an actual email
    'password' => 'required|min:5' // password can only be greater than 5 characters
);

// run the validation rules on the inputs from the form
$validator = $this->validate($request, $rules); //Validator::make(Input::all(), $rules);

/*// if the validator fails, redirect back to the form
if ($validator->fails()) {
    return Redirect::to('login')
        ->withErrors($validator) // send back all errors to the login form
        ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
} else {*/

    // create our user data for the authentication
    $userdata = array(
        'email'     => $request->email,
        'password'  => $request->password,
        'active' => '1'
    );

    $user = User::where('email', $request->email)->first();

    // attempt to do the login
    if ($user->active) {
        
    
    if (Auth::attempt($userdata)) {

        
       return redirect('/'.Auth::user()->role);

    } else {        

        // validation not successful, send back to form

        $request->session()->flash('failed', 'Invalid Password');

        return back();

    }
 }else {
     $request->session()->flash('failed', 'Account Inactive ('.$user->reason.')');

        return back();
 }

}

	public function logout()
{
    Auth::logout(); // log the user out of our application
    return redirect('/login'); // redirect the user to the login screen
}

}
