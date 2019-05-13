<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\History;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Mail\Honeypays;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Log;

class ViewcusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(User $user){

        $now = Carbon::now();

    	return view('admin.cus.index', compact('now', 'user'));

    }

    public function transactions(User $user){

        $historys = $user->history;

        return view('admin.cus.search.history', compact('user','historys'));

    }

    public function getinvest(User $user){

        return view('admin.cus.invest', compact('user'));

    }

    public function postinvest(Request $request, User $user){

      $this->validate($request, [

                'invest_amount' => 'required|numeric|min:10',
                'return_amount' => 'required|numeric',
                'tenure' => 'required|numeric',
                'invest_date' => 'required|date_format:d/m/y',
                'return_date' => 'required|date_format:d/m/y',

        ]);

        $randomnumber = $this->randomstring(2).date("Hismy");

        $user->history()->create([

          'invest_amount' => $request->invest_amount,
          'return_amount' => $request->return_amount,
          'tenure' => $request->tenure,
          'invest_date' => Carbon::createFromFormat('d/m/y', $request->invest_date),
          'return_date' => Carbon::createFromFormat('d/m/y', $request->return_date),
          'status' => 'active',
          'approved_date' => Carbon::createFromFormat('d/m/y', $request->invest_date),
          'proof' => '/images/verify.jpg',
          'tran_id' => $randomnumber,
          ]);

        $email = $user->email;
      $number = $user->number;
      $subject = 'Investment Approved';

       $message = 'Your investment with id: '.$randomnumber.' has been approved';

         Log::info($this->sms($number, urlencode($message)));

        //Mail::to($email)->send(new Honeypays($message, $subject));

        $request->session()->flash('success', 'Investment Created successfully ');

        return back();

    }

    public function editget(User $user){

    	return view('admin.cus.edit', compact('user'));

    }

    public function editpost(Request $request, User $user){


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
		            'identity' => 'nullable|image|mimes:jpeg,jpg,png|max:250',

    		]);

	 if ($request->file('identity')) {
        $destination = public_path('/images');
        $identity = $request->file('identity');
        $identityname =$user->email."-"."identity".".".$identity->getClientOriginalExtension();
        $identity->move($destination, $identityname);
       $user->update(['identity' => '/images'.'/'.$identityname]);
            
        }

    if ($request->password) {
   $user->update(['password' => Hash::make($request->password)]);
        
    }

    if ($request->number) {
   $user->update(['number' => $request->number]);
        
    }

    if ($request->email) {
   $user->update(['email' => $request->email]);
        
    }

   $user->update([
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

    return back();

    }


  public function referals(User $user){

    $referals = User::where('referal','=',$user->mentor)->paginate(500);
    return view('admin.cus.referals')->with(['referals' => $referals, 'user' => $user]); 

  }

public function changeid(Request $request, User $user){

	if ($user->id_change != '1') {

    $user->update(['id_change' => '1']);

    $link = url("/cus/edit");
    $email = $user->email;
    $number = $user->number;
		$subject = 'Change Identity';

   $message = 'Hello '.$user->name.' You have an invalid Identity, please change it @ '.$link;

     Log::info($this->sms($number, urlencode($message)));

        Mail::to($email)->send(new Honeypays($message, $subject));
    $request->session()->flash('success', 'Customer successfully notified to change identity');

    return back(); 
	}else {
		$request->session()->flash('failed', 'Rquest to change id already sent');

    return back(); 
	}

  }

  public function verifyid(Request $request, User $user){
  	if ($user->id_change != '0') {
  		
  	

    $user->update(['id_change' => '0']);

    $email = $user->email;
    $number = $user->number;
		$subject = 'Identity Verified';

   $message = 'Hello '.$user->name.' Your identity has successfully been verified';

     Log::info($this->sms($number, urlencode($message)));

        Mail::to($email)->send(new Honeypays($message, $subject));
    $request->session()->flash('success', 'Customer identity verified');

    return back(); 
	}else {
		$request->session()->flash('failed', 'Identity already verified');

    return back(); 
	}
  }

  public function suspend(Request $request, User $user){
  	if ($user->active) {
  		
  	
  	$this->validate($request, [

    				'reason' => 'required|string|max:50',

    		]);

  	$user->update([
  		'active' => '0',
  		'reason' => $request->reason,
  		]);

  	$email = $user->email;
    $number = $user->number;
		$subject = 'You are suspended';

   $message = 'Hello '.$user->name.' we are sorry to inform you that you are currently suspended. Reason: '.$request->reason;

     Log::info($this->sms($number, urlencode($message)));

        Mail::to($email)->send(new Honeypays($message, $subject));
    $request->session()->flash('success', 'Customer suspended successfully.');

    return back(); 
	}else {
		$request->session()->flash('failed', 'Customer is currently suspended.');

    return back(); 
	}
  }
  public function unsuspend(Request $request, User $user){
  	if (!$user->active) {

  	$user->update([
  		'active' => '1',
  		]);

  	$email = $user->email;
    $number = $user->number;
		$subject = 'You are Unsuspended';

   $message = 'Hello '.$user->name.' you are now active';

     Log::info($this->sms($number, urlencode($message)));

        Mail::to($email)->send(new Honeypays($message, $subject));
    $request->session()->flash('success', 'Customer unsuspended successfully.');

    return back(); 
	}else {
		$request->session()->flash('failed', 'Customer is currently active.');

    return back(); 
	}
  }
  public function makementor(Request $request, User $user){
  	if (empty($user->mentor)) {

  	$user->update([
  		'mentor' => $user->number,
  		]);

  	$email = $user->email;
    $number = $user->number;
		$subject = 'You are now a mentor';

   $message = 'Hello '.$user->name.' you are now made a mentor with mentor number:'.$user->mentor;

     Log::info($this->sms($number, urlencode($message)));

        Mail::to($email)->send(new Honeypays($message, $subject));
    $request->session()->flash('success', 'Customer now a mentor.');

    return back(); 
	}else {
		$request->session()->flash('failed', 'Customer is already a mentor.');

    return back(); 
	}
  }
public function delete(Request $request, User $user){

 

  	$email = $user->email;
    $number = $user->number;
		$subject = 'Account Deleted';

   $message = 'Hello '.$user->name.' your account is now deleted from our record';

   History::where('user_id', '=', $user->id)->delete();
    $user->delete();

     Log::info($this->sms($number, urlencode($message)));

        Mail::to($email)->send(new Honeypays($message, $subject));

    $request->session()->flash('success', 'Customer record deleted successfully.');

    return redirect('/admin'); 
	
  }

}
