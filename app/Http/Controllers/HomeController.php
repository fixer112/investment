<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\Honeypays;
use Illuminate\Support\Facades\Mail;

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

     public function mail()
    {
        $message = 'You have successfully verified your email, you can now login at https://investor.honeypays.com.ng/login';
        $subject = 'Registration successful';
        //return (new Honeypays($content))->render();
        //Mail::to('sogbaderofiat@yahoo.com')->send(new Honeypays($message, $subject));
        return (new Honeypays($message))->render();
    }
}
