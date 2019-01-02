<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
         $this->middleware('auth')->only('logout');
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
                if ($request->ajax() || $request->expectsJson()) {
                    if (Auth::user()->role!='cus') {
                     $data['errors'] = ['fail' => ['Only Customers allowed']];
                     Auth::logout();
                     return \Response::json($data, 403);
                 }

                $version= $request->version;
               

                 if (!$verson ||  $version < "1.1.3") {
                     $data['errors'] = ['fail' => ['Service Unavailable or Update App']];
                     Auth::logout();
                     return \Response::json($data, 403);
                 }
                 Auth::user()->update(['api_token' => str_random(60)]);
                 return \Response::json(['user' => Auth::user()]);
             }


             return redirect('/'.Auth::user()->role);

         } else {        

        // validation not successful, send back to form
            if ($request->ajax() || $request->expectsJson()) {
             $data['errors'] = ['fail' => ['Invalid Password']];
             return \Response::json($data, 403);
         }
         $request->session()->flash('failed', 'Invalid Password');

         return back();

     }
 }else {
   if ($request->ajax() || $request->expectsJson()) {
     $data['errors'] = ['fail' => ['Account Inactive ('.$user->reason.')']];
     return \Response::json($data, 403);
 }
 $request->session()->flash('failed', 'Account Inactive ('.$user->reason.')');

 return back();
}

}

public function logout(Request $request)
{
    Auth::logout(); // log the user out of our application
    $request->session()->invalidate();

    if ($request->ajax() || $request->expectsJson()) { 
        return \Response::json([ 'message' => 'logged out']);
    }

    return redirect('/login'); // redirect the user to the login screen
}

}
