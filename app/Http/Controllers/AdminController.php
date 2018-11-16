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

class AdminController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(){
    	$historys = new History;
    	$users = new User;
    	$paids = $historys->where('status', '=', 'paid')->get();
		$actives = $historys->where('status', '=', 'active')->get();
		$all = $paids->sum('invest_amount') + $actives->sum('invest_amount');
		$pendings = $historys->where('status', '=', 'pending')->get();
		$rejecteds = $historys->where('status', '=', 'reject')->get();
		$now = Carbon::now();
		//$dues = 

		$admin = $users->where('role','=','admin')->get();
		$cus = $users->where('role','=','cus')->get();
		$mentor = $users->where('mentor', '!=', '')->get();

    	return view('admin.index', compact('users', 'historys', 'paids', 'actives', 'all', 'pendings', 'rejecteds', 'admin', 'cus', 'mentor', 'now'));
    }

    public function notify(){

        return view('admin.notify');
    }

    public function notifypost(Request $request){
        $this->validate($request, [

                    'title' => 'required|string|max:100',
                    'body' => 'required|string|max:1000',

            ]);
        Log::info('App Global Notification: '.$this->app($request->title,$request->body,'global'));
        $request->session()->flash('success', 'Notification sent');
        return back();
    }

    public function identity(){



        return view('admin.identity');

    }

    public function editget(){

    	return view('admin.edit');

    }

    public function editpost(Request $request){


	$this->validate($request, [

    				'name' => 'required|string|max:50',
                    'email' => 'nullable|email|unique:users',
		            'password' => 'nullable|string|min:5|confirmed',

    		]);

	 if ($request->password) {
    Auth::user()->update(['password' => Hash::make($request->password)]);
        
    }

    if ($request->email) {
    Auth::user()->update(['email' => $request->email]);
        
    }

     if ($request->name) {
    Auth::user()->update(['name' => ucwords($request->name)]);
        
    }

	 $request->session()->flash('success', 'Profile edited successfully.');

    return redirect('admin/edit');

    }

    public function addadminget(){

    	return view('admin.addadmin');

    }

    public function addadminpost(Request $request){

    	$this->validate($request, [

    				'name' => 'required|string|max:50',
                    'email' => 'required|email|unique:users',
		            'password' => 'required|string|min:5|confirmed',
		            'number' => 'required|numeric|unique:users',

    		]);

    	User::create([
    		'name' => ucwords($request->name),
    		'email' => $request->email,
    		'password' => Hash::make($request->password),
    		'mentor' => $request->number,
    		'number' => $request->number,
		    'active' => '1',
            'role' => 'admin',

    		]);

    $request->session()->flash('success', 'New admin added successfully.');

    return redirect('admin/addadmin');

    }

    public function investdelete(Request $request, History $history){
        $history->delete();
        $request->session()->flash('success', 'Transaction successfully deleted');

            return redirect('/admin');

    }

    public function investreject(Request $request, History $history){

	    	if ($history->status == 'pending') {
	    	

	    	$history->update([

	    		'approved_date' => Carbon::now(),
	    		'status' => 'reject',

	    		]);

	    $email = $history->user->email;
	    $number = $history->user->number;
   		$subject = 'Investment Rejected';

       $message = 'We are sorry to inform you that Transaction '.$history->tran_id.' was rejected, please retry by uploading a valid proof of payment';

         Log::info($this->sms($number, urlencode($message)));

        Log::info($this->app($subject,$message,$email));

        Mail::to($email)->send(new Honeypays($message, $subject));

	    	$request->session()->flash('success', 'Investment id: '.$history->tran_id.' successfully rejected');

	    	return redirect('/admin');

	    }else{

	    	$request->session()->flash('failed', 'Cant reject, Transaction '.$history->tran_id.' is not pending a pending transaction');

	    	return redirect('/admin');
	    }
	}

	public function investapprove(Request $request, History $history){

	    	if ($history->status == 'pending') {

	    $year = date('Y');
        $date = Carbon::now();
        //$date = Carbon::parse('09/03/18');
        $rate = $history->tenure;
        $amount = $history->invest_amount;

        $holidays = [$year."-01-01", $year."-01-15", $year."-02-12", $year."-02-14", $year."-02-16", $year."-02-19", $year."-03-11", $year."-03-17", $year."-03-20", $year."-03-31", $year."-04-01", $year."-04-25", $year."-05-13", $year."-05-16", $year."-05-20", $year."-05-28", $year."-06-14", $year."-06-17", $year."-06-21", $year."-07-04", $year."-09-03", $year."-09-10", $year."-09-23", $year."-10-08", $year."-10-31", $year."-11-04", $year."-11-11", $year."-11-22", $year."-12-02", $year."-12-21", $year."-12-25", $year."-12-26", $year."-12-31"];

	        if (!$date->isMonday()) {
	        	
	        	$date->addWeekdays(1);
	        }

	        $MyDateCarbon = $date;

	        $MyDateCarbon->addWeekdays($rate);

	        for ($i = 1; $i <= $rate+1; $i++) {

	    	if (in_array(Carbon::now()->addWeekdays($i)->toDateString(), $holidays)/* || in_array(Carbon::now()->toDateString(), $holidays)*/) {

	        $MyDateCarbon->addWeekdays(1);

			    }
			}
           //return $MyDateCarbon;
        $history->update([
			'approved_date' => Carbon::now(),
            'return_date' => $MyDateCarbon,
            'status' => 'active',

			]);

        $email = $history->user->email;
	    $number = $history->user->number;
   		$subject = 'Investment Approved';

       $message = 'Your investment with id: '.$history->tran_id.' has been approved';

          Log::info($this->sms($number, urlencode($message)));
         Log::info($this->app($subject,$message,$email));


        Mail::to($email)->send(new Honeypays($message, $subject));


	    	$request->session()->flash('success', 'Transaction '.$history->tran_id.' successfully Approved' );

	    	return redirect('/admin');

	    }else{

	    	$request->session()->flash('failed', 'Cant approve, Transaction '.$history->tran_id.' is not pending a pending transaction');

	    	return redirect('/admin');
	    }
	}

	public function approvepaid(Request $request, History $history){

	    	if ($history->status == 'active') {
	    	

	    	$history->update([

	    		'paid_date' => Carbon::now(),
	    		'status' => 'paid',

	    		]);

		    $email = $history->user->email;
		    $number = $history->user->number;
	   		$subject = 'Investment Return Paid';

	       $message = 'Your investment with id: '.$history->tran_id.' has successfully been paid';

	         Log::info($this->sms($number, urlencode($message)));
             Log::info($this->app($subject,$message,$email));

	        Mail::to($email)->send(new Honeypays($message, $subject));

	    	$request->session()->flash('success', 'Transaction '.$history->tran_id.' payment successfully verified');

	    	return redirect('/admin');

	    }else{

	    	$request->session()->flash('failed', 'Transaction '.$history->tran_id.' is not active');

	    	return redirect('/admin');
	    }
	}

	public function mentors(){

    $mentors = User::where('mentor','!=', '')->get();
    return view('admin.mentors')->with(['mentors' => $mentors]); 

  }

  public function admins(){

    $admins = User::where('role','=', 'admin')->get();
    return view('admin.admins')->with(['admins' => $admins]); 

  }

	 public function referals(){

    $referals = User::where('referal','=', Auth::user()->mentor)->get();
    return view('admin.referals')->with(['referals' => $referals]); 

  }

  public function transactions(){

        $historys = History::all();

        return view('admin.search.history', compact('historys'));

    }

     public function customers(){

        $cuss = User::where('role','=', 'cus')->get();

        return view('admin.search.cus', compact('cuss'));

    }

}
