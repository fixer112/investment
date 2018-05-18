<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\Honeypays;
use Illuminate\Support\Facades\Mail;
use File;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

     public function mail(Request $request)
    {
        $message = 'You have successfully verified your email, you can now login at '.url("/login");
        $subject = 'Registration successful';
        $mail = 'abula';
        //return (new Honeypays($content))->render();
        try {
            Mail::to('abula3003@gmail.com')->send(new Honeypays($message, $subject));
        return 'successful';
        } catch (Exception $e) {
            return $e->getMessage();
        }
        
       // return url("/verify/".$mail.'/');

        /*File::delete(public_path('/proof/208851524965780.jpg'));
        return 'successful';*/
    }
}
