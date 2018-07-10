<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\History;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Mail\Contact;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('cus');
    }

    public function index(){

        $now = Carbon::now();

    	return view('cus.index', compact('now'));

    }

    public function transactions(){

        $historys = History::where('user_id', '=', Auth::user()->id)->get();

        return view('cus.search.history', compact('historys'));

    }

    public function editget(){

    	return view('cus.edit');

    }

    public function getcontact(){

        return view('cus.contact');

    }

    public function editpost(Request $request){


	$this->validate($request, [

    				'name' => 'required|string|max:50',
                    'email' => 'nullable|email|unique:users',
		            'password' => 'nullable|string|min:5|confirmed',
		            'number' => 'nullable|numeric|unique:users',
		            'acc_no' => 'required|numeric',
		            'acc_name' => 'required|string',
		            'bank_name' => 'required|string',
		            'addr' => 'required|string',
		            'city' => 'required|string',
		            'state' => 'required|string',
		            'mode_id' => 'required|string',
		            'identity' => 'nullable|image|mimes:jpeg,jpg,png|max:1024',

    		]);

	 if ($request->file('identity')) {
        $destination = public_path('/images');
        $identity = $request->file('identity');
        $identityname = Auth::user()->email."-"."identity".".".$identity->getClientOriginalExtension();
        $identity->move($destination, $identityname);
        Auth::user()->update([
            'identity' => '/images'.'/'.$identityname,
            'id_change' => '2',

            ]);
            
        }

    if ($request->password) {
    Auth::user()->update(['password' => Hash::make($request->password)]);
        
    }

    if ($request->number) {
    Auth::user()->update(['number' => $request->number]);
        
    }

    if ($request->email) {
    Auth::user()->update(['email' => $request->email]);
        
    }

    Auth::user()->update([
            'name' => ucwords($request->name),
            'acc_no' => $request->acc_no,
            'acc_name' => $request->acc_name,
            'bank_name' => $request->bank_name,
            'addr' => $request->addr,
            'city' => $request->city,
            'state' => $request->state,
            'mode_id' => $request->mode_id,

        ]);

    $request->session()->flash('success', 'Profile edited successfully.');

    return redirect('/cus/edit');

    }


    public function postcontact(Request $request){

        $this->validate($request, [

                    'subject' => 'required|string|max:50',
                    'message' => 'required|string|max:255',

            ]);

        
        $from = Auth::user()->email;
        $subject = $request->subject;

       $message = $request->message;

        Mail::to('support@honeypays.com.ng')->send(new Contact($from, $message, $subject));


        $request->session()->flash('success', 'Message send successfully to '.$from.', you will get a reply in your email soon');

        return back();

    }


  public function investget(){
  	return view('cus.invest');
  }

  public function investpost(Request $request){

  	$this->validate($request, [
            'amount' => 'required|numeric|min:5000|max:5000000',
            'rate' => 'required',
            'proof' => 'required|image|mimes:jpeg,jpg,png|max:1024'
        ]);

  		$rate = $request->input('rate');
        $amount = $request->input('amount');

		if ($rate == "30") {

			$accured_interest = (1/100)*$amount*$rate;
            $irm = 20;
			
		}elseif ($rate == "90") {
			$accured_interest = (2/100)*$amount*$rate;
             $irm = 30;
		}elseif ($rate == "180") {
			$accured_interest = (3/100)*$amount*$rate;
             $irm = 40;
		}elseif ($rate == "360") {
			$accured_interest = (4/100)*$amount*$rate;
             $irm = 50;
		}
        $gain = $amount + $accured_interest;
		$interest = ($irm/100)*$gain;
		$total_earning = $gain - $interest;

		$randomnumber = Auth::user()->id.$this->randomnumber(4).time();

		$destination = public_path('/proof');
        $proof = $request->file('proof');
        $proofname = $randomnumber.".".$proof->getClientOriginalExtension();
        $proof->move($destination, $proofname);

		Auth::user()->history()->create([

			'tran_id' => $randomnumber,
			'invest_date' => Carbon::now(),
			'invest_amount' => $amount,
			'tenure' => $rate,
			'return_amount' => $total_earning,
			'proof' => '/proof'.'/'.$proofname,

			]);

		$request->session()->flash('success', 'Awaiting approval For N'.$amount);

    	return redirect('/cus/invest');
  }

  public function referals(){

    $referals = User::where('referal','=', Auth::user()->mentor)->get();
    return view('cus.referals')->with(['referals' => $referals]); 

  }

  public function mentorcus(User $user){
    if ($user->referal == Auth::user()->mentor) {

    $referals = $user->history;
    return view('cus.mentorcus.index')->with(['referals' => $referals, 'user' => $user]); 

    }else {
        abort(404);
    }
  }

  public function mentorcushistory(User $user){
    if ($user->referal == Auth::user()->mentor) {

    $historys = $user->history;
    return view('cus.mentorcus.history')->with(['historys' => $historys, 'user' => $user]); 

    }else {
        abort(404);
    }
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
