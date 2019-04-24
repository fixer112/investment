<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\History;
use App\User;
use App\Rollover;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Mail\Contact;
use Illuminate\Support\Facades\Mail;
use Log;

class CustomerController extends Controller
{
    public function __construct(Request $request)
    {
        if ($request->ajax() || $request->expectsJson()) {
            $this->middleware('auth:api');
        }else{
            $this->middleware('auth');
        }
        //$this->middleware('auth');
        $this->middleware('cus');
    }

    public function index(Request $request){

        $now = Carbon::now();

        if ($request->ajax() || $request->expectsJson()) {
            $user = Auth::user();
            $paids = Auth::user()->history()->where('status', '=', 'paid')->orderby('id','desc')->get();
            $actives = Auth::user()->history()->where('status', '=', 'active')->orderby('id','desc')->get();
            $all = $paids->sum('invest_amount') + $actives->sum('invest_amount');
            $pendings = Auth::user()->history()->where('status', '=', 'pending')->orderby('id','desc')->get();
            $rejecteds = Auth::user()->history()->where('status', '=', 'reject')->orderby('id','desc')->get();
            $tpr = $paids->sum('return_amount');
            $ter = $actives->sum('return_amount');
               $data = compact('user','now', 'paids', 'actives', 'all', 'pendings', 'rejecteds', 'tpr', 'ter');
                return \Response::json($data, 200);
            }
    	return view('cus.index', compact('now'));

    }

    public function transactions(Request $request){

        $historys = History::where('user_id', '=', Auth::user()->id)->orderby('id','desc')->get();

        if ($request->ajax() || $request->expectsJson()) {
            
               $data = compact('historys');
                return \Response::json($data, 200);
            }

        return view('cus.search.history', compact('historys'));

    }

    public function editget(Request $request){

    	return view('cus.edit');

    }

    public function getcontact(Request $request){

        return view('cus.contact');

    }

    public function getrefund(Request $request){

        return view('cus.refund');

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

    if ($request->ajax() || $request->expectsJson()) {
            
               $data['message'] = 'Profile edited successfully.';
                return \Response::json($data, 200);
            }

    return redirect('/cus/edit');

    }


    public function postcontact(Request $request){

        $this->validate($request, [

                    'subject' => 'required|string|max:50',
                    'message' => 'required|string|max:255',
                    'to' => 'email',

            ]);


        $email = $request->email ? $request->email : Auth::user()->email;
        $from = $email;//Auth::user()->email;
        $subject = $request->subject;
        $to = $request->to ? $request->to : 'support@honeypays.com.ng';

       $message = $request->message;

        Mail::to($to)->send(new Contact($from, $message, $subject));


        $request->session()->flash('success', 'Message send successfully to admin from '.$from.', you will get a reply in your email soon');
        if ($request->ajax() || $request->expectsJson()) {
            
               $data['message'] = 'Message send successfully to admin from '.$from.', you will get a reply in your email soon';
                return \Response::json($data, 200);
            }
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

        $total_earning = $this->return_amount($rate, $amount);
        

		/*if ($rate == "30") {

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
		}*/

        /*$gain = $amount + $accured_interest;
		$interest = ($irm/100)*$gain;
		$total_earning = $gain - $interest;*/

		$randomstring = $this->randomstring(2).date("Hismy");

		$destination = public_path('/proof');
        $proof = $request->file('proof');
        $proofname = $randomstring.".".$proof->getClientOriginalExtension();
        $proof->move($destination, $proofname);

		Auth::user()->history()->create([

			'tran_id' => $randomstring,
			'invest_date' => Carbon::now(),
			'invest_amount' => $amount,
			'tenure' => $rate,
			'return_amount' => $total_earning,
			'proof' => '/proof'.'/'.$proofname,

			]);

		$request->session()->flash('success', 'Awaiting approval For N'.$amount);
        if ($request->ajax() || $request->expectsJson()) {
            
               $data['message'] = 'Awaiting approval For N'.$amount;
                return \Response::json($data, 200);
            }
    	return redirect('/cus/invest');
  }

  public function referals(Request $request){

    $referals = User::where('referal','=', Auth::user()->mentor)->orderby('id','desc')->get();
     if ($request->ajax() || $request->expectsJson()) {
            
               $data = compact('referals');
                return \Response::json($data, 200);
            }
    return view('cus.referals')->with(['referals' => $referals]); 

  }

  public function mentorcus(User $user, Request $request){
    if ($user->referal == Auth::user()->mentor) {

    $referals = $user->history;
     $now = Carbon::now();
      if ($request->ajax() || $request->expectsJson()) {
            $paids = $referals->where('status', 'paid');
            $actives = $referals->where('status', 'active');
            $all = $paids->sum('invest_amount') + $actives->sum('invest_amount');
            $pendings = $referals->where('status', 'pending');
            $rejecteds = $referals->where('status', 'reject');
            $tpr=$paids->sum('return_amount');
            $ter=$actives->sum('return_amount');
               $data = compact('referals','user','now','paids','actives','all','pendings','rejecteds','tpr','ter');
                return \Response::json($data, 200);
            }
    return view('cus.mentorcus.index')->with(['referals' => $referals, 'user' => $user, 'now' => $now]); 

    }else {
        if ($request->ajax() || $request->expectsJson()) {
            
               $data['errors'] = ['fail' => ['User not a referal']];
               //Auth::logout();
                return \Response::json($data, 403);
            }
        abort(404);
    }
  }

  public function mentorcushistory(User $user){
    if ($user->referal == Auth::user()->mentor) {

    $historys = $user->history;

    if ($request->ajax() || $request->expectsJson()) {
            
               $data = compact('historys', 'user');
               //Auth::logout();
                return \Response::json($data, 403);
            }

    return view('cus.mentorcus.history')->with(['historys' => $historys, 'user' => $user]); 

    }else {
        if ($request->ajax() || $request->expectsJson()) {
            
               $data['errors'] = ['fail' => ['User not a referal']];
               //Auth::logout();
                return \Response::json($data, 403);
            }
        abort(404);
    }
  }

  function showRoll(){
    $actives = Auth::user()->history()->where('status', '=', 'active')->latest()->get();
    return view('cus.roll',compact('actives'));
  }
  public function roll(){

    $history = History::find(request()->trans);
    if (!$history) {
      //request()->session()->flash('failed', '');
      return back();
    }

    $roll = Rollover::create([
      'history_id' => $history->id,
      'tenure' => request()->tenure,
      'type' => request()->type,
      'user_id' => $history->user->id,
    ]);

    $history->update(['rollover_id' => $roll->id]);

    request()->session()->flash('success', 'Rollover pending, await admin approval');
    return back();
  }
}
