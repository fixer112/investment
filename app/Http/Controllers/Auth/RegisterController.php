<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
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
            ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data, Request $request)
    {

        $identityname ="";

        if ($request->file('identity')) {
        $destination = public_path('/images');
        $identity = $request->file('identity');
        $identityname = $data['email']."-"."identity".".".$identity->getClientOriginalExtension();
        $identity->move($destination, $identityname);
            
        }

        return User::create([
            'name' => $data['name'],
            'referral' => $data['mentor'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'number' => $data['number'],
            'acc_no' => $data['acc_no'],
            'acc_name' => $data['acc_name'],
            'bank_name' => $data['bank_name'],
            'addr' => $data['addr'],
            'city' => $data['city'],
            'state' => $data['state'],
            'mode_id' => $data['mode_id'],
            'identity' => $identityname,

        ]);
    $request->session()->flash('success', 'Registration Successful, please login.');

        return redirect('/login');
    }
}
