<?php

namespace App\Http\Controllers;

use App\History;
use App\Mail\Dues;
use App\Mail\Honeypays;
use App\Refund;
use App\Rollover;
use App\User;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Log;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $historys = new History;
        $users = new User;
        $paids = $historys->where('status', '=', 'paid')->get();
        $actives = $historys->where('status', '=', 'active')->get();
        $all = $paids->sum('invest_amount') + $actives->sum('invest_amount');
        $pendings = $historys->where('status', '=', 'pending')->get();
        $rejecteds = $historys->where('status', '=', 'reject')->get();
        $rolls = Rollover::where('status', 0)->get();
        $p_refunds = Refund::where('status', 0)->where('paid', 0)->get();
        $a_refunds = Refund::where('status', 1)->where('paid', 0)->get();
        $refund_dues = $a_refunds->filter(function ($value, $key) {
            return $value->due < carbon::now();
        });
        $refund_paids = Refund::where('paid', 1)->get();
        //return $refund_dues;
        $now = Carbon::now();
        //$dues =

        $admin = $users->where('role', '=', 'admin')->get();
        $cus = $users->where('role', '=', 'cus')->get();
        $mentor = $users->where('mentor', '!=', '')->get();

        return view('admin.index', compact('users', 'historys', 'paids', 'actives', 'all', 'pendings', 'rejecteds', 'admin', 'cus', 'mentor', 'now', 'rolls', 'p_refunds', 'a_refunds', 'refund_dues', 'refund_paids'));
    }

    public function notify()
    {

        return view('admin.notify');
    }

    public function notifypost(Request $request)
    {
        $this->validate($request, [

            'title' => 'required|string|max:100',
            'body' => 'required|string|max:1000',

        ]);
        Log::info('App Global Notification: ' . $this->app($request->title, $request->body, 'global'));
        $request->session()->flash('success', 'Notification sent');
        return back();
    }

    public function identity()
    {

        return view('admin.identity');

    }

    public function editget()
    {

        return view('admin.edit');

    }

    public function editpost(Request $request)
    {

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

    public function addadminget()
    {

        return view('admin.addadmin');

    }

    public function addadminpost(Request $request)
    {

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

    public function investdelete(Request $request, History $history)
    {
        $history->delete();
        $request->session()->flash('success', 'Transaction successfully deleted');

        return back();

    }

    public function investreject(Request $request, History $history)
    {

        if ($history->status == 'pending') {

            $history->update([

                'approved_date' => Carbon::now(),
                'status' => 'reject',

            ]);

            $email = $history->user->email;
            $number = $history->user->number;
            $subject = 'Investment Rejected';

            $message = 'We are sorry to inform you that Transaction ' . $history->tran_id . ' was rejected, please retry by uploading a valid proof of payment';

            Log::info($this->sms($number, urlencode($message)));

            Log::info($this->app($subject, $message, $email));

            Mail::to($email)->send(new Honeypays($message, $subject));

            $request->session()->flash('success', 'Investment id: ' . $history->tran_id . ' successfully rejected');

            return redirect('/admin');

        } else {

            $request->session()->flash('failed', 'Cant reject, Transaction ' . $history->tran_id . ' is not pending a pending transaction');

            return redirect('/admin');
        }
    }

    public function investapprove(Request $request, History $history)
    {
        if (env('error')) {
            return $this->showError(request());
        }

        if ($history->status == 'pending') {

            $year = date('Y');
            $date = Carbon::now();
            //$date = Carbon::parse('09/03/18');

            $MyDateCarbon = $this->return_date($date, $history->tenure);

            //return $MyDateCarbon;
            $history->update([
                'approved_date' => Carbon::now(),
                'return_date' => $MyDateCarbon,
                'status' => 'active',

            ]);

            $email = $history->user->email;
            $number = $history->user->number;
            $subject = 'Investment Approved';

            $message = 'Your investment with id: ' . $history->tran_id . ' has been approved';

            Log::info($this->sms($number, urlencode($message)));
            Log::info($this->app($subject, $message, $email));

            Mail::to($email)->send(new Honeypays($message, $subject));

            $request->session()->flash('success', 'Transaction ' . $history->tran_id . ' successfully Approved');

            return redirect('/admin');

        } else {

            $request->session()->flash('failed', 'Cant approve, Transaction ' . $history->tran_id . ' is not pending a pending transaction');

            return redirect('/admin');
        }
    }

    public function approvepaid(Request $request, History $history)
    {

        if ($history->status == 'active') {

            $history->update([

                'paid_date' => Carbon::now(),
                'status' => 'paid',

            ]);

            $email = $history->user->email;
            $number = $history->user->number;
            $subject = 'Investment Return Paid';

            $message = 'Your investment with id: ' . $history->tran_id . ' has successfully been paid';

            Log::info($this->sms($number, urlencode($message)));
            Log::info($this->app($subject, $message, $email));

            Mail::to($email)->send(new Honeypays($message, $subject));

            $request->session()->flash('success', 'Transaction ' . $history->tran_id . ' payment successfully verified');

            return redirect('/admin');

        } else {

            $request->session()->flash('failed', 'Transaction ' . $history->tran_id . ' is not active');

            return redirect('/admin');
        }
    }

    public function mentors()
    {

        $mentors = User::where('mentor', '!=', '')->get();
        return view('admin.mentors')->with(['mentors' => $mentors]);

    }

    public function admins()
    {

        $admins = User::where('role', '=', 'admin')->get();
        return view('admin.admins')->with(['admins' => $admins]);

    }

    public function referals()
    {

        $referals = User::where('referal', '=', Auth::user()->mentor)->get();
        return view('admin.referals')->with(['referals' => $referals]);

    }

    public function transactions()
    {

        $historys = History::get();

        return view('admin.search.history', compact('historys'));

    }

    public function customers()
    {

        $cuss = User::where('role', '=', 'cus')->get();

        return view('admin.search.cus', compact('cuss'));

    }

    public function postDues(Request $request)
    {
        $year = $request->year ? $request->year : date('Y');

        $month = $request->month ? $request->month : date('m');

        $date = Carbon::createFromDate($year, $month);

        $start = Carbon::createFromDate($year, $month)->startOfMonth();
        $end = Carbon::createFromDate($year, $month)->endOfMonth();

        $dues = History::where('status', 'active')->whereBetween('return_date', [$start, $end])->orderBy('return_date', 'asc')->get();
        $users = new User;

        //return $dues;
        return view('admin.dues', compact('year', 'month', 'dues', 'users'));
    }

    public function getDues(Request $request)
    {
        $year = date('Y');
        $month = date('m');
        $start = Carbon::createFromDate($year, $month)->startOfMonth();
        $end = Carbon::createFromDate($year, $month)->endOfMonth();
        $dues = History::where('status', 'active')->whereBetween('return_date', [$start, $end])->orderBy('return_date', 'asc')->get();
        $users = new User;

        return view('admin.dues', compact('year', 'month', 'dues', 'users'));
    }

    public function dues($month, $year, Request $request)
    {

        $start = Carbon::createFromDate($year, $month)->startOfMonth();
        $end = Carbon::createFromDate($year, $month)->endOfMonth();

        $dues = DB::table('historys')->join('users', 'historys.user_id', '=', 'users.id')->where('historys.status', 'active')->whereBetween('historys.return_date', [$start, $end])->orderBy('historys.return_date', 'asc')->select('historys.return_amount', 'historys.return_date', 'users.email', 'users.referal')->get();
        //return $dues;
        $array_dues = json_decode(json_encode($dues), true);
        if (count($array_dues) == 0) {
            $request->session()->flash('failed', 'No active dues in ' . $month . "/" . $year);
            return back();
        }
        $name = "Dues-" . $month . "-" . $year;
        $e = Excel::create($name, function ($excel) use ($array_dues, $dues) {
            $excel->sheet('Sheet 1', function ($sheet) use ($array_dues, $dues) {
                $sheet->fromArray($array_dues);
                $sheet->appendRow(array(
                    $dues->sum("return_amount"),
                ));
            });
        });

        if ($request->type == "email") {
            $email = "bdm@honeypays.com.ng";
            $e->store('xls', storage_path('excel/dues'));
            Mail::to($email)->send(new Dues($month, $year, $name));
            $request->session()->flash('success', 'Dues ' . $name . ' sent to ' . $email);
            return back();
        } else {
            $e->export('xls');
        }

    }

    public function notify_change(User $user)
    {
        $email = $user->email;
        $number = $user->number;
        $subject = 'Bank details update';

        $message = $user->name . ' kindly correct the bank details provided on your account, as the account number/bank name is incorrect. Kindly notify us when it is done by calling 08168857027.';

        Log::info($this->sms($number, urlencode($message)));

        Log::info($this->app($subject, $message, $email));

        Mail::to($email)->send(new Honeypays($message, $subject));

        request()->session()->flash('success', 'Bank details notification sent to ' . $user->name);

        return back();

    }

    public function stats(Request $request)
    {
        $paids = History::where('status', 'paid');
        $actives = History::where('status', 'active');
        $rejecteds = History::where('status', 'reject');
        $pendings = History::where('status', 'pending');

        $mentor = $request->mentor;
        $mentors = User::distinct()->get(['mentor'])->pluck('mentor');
        $from = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
        $to = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');

        session(['from' => Carbon::now()->format('Y-m-d'), 'to' => Carbon::now()->format('Y-m-d')]);

        if ($mentor) {
            $users_mentors = User::where('referal', $mentor)->pluck('id');
            $paids->whereIn('user_id', $users_mentors);
            $actives->whereIn('user_id', $users_mentors);
            $rejecteds->whereIn('user_id', $users_mentors);
            $pendings->whereIn('user_id', $users_mentors);
        }

        if ($request->from && $request->to) {
            $from = Carbon::createFromFormat('Y-m-d', $request->from)->startOfDay()->format('Y-m-d H:i:s');
            $to = Carbon::createFromFormat('Y-m-d', $request->to)->endOfDay()->format('Y-m-d H:i:s');
            session(['from' => $request->from, 'to' => $request->to]);

        }

        $paids->whereBetween('updated_at', [$from, $to])->get();
        $actives->whereBetween('updated_at', [$from, $to])->get();
        $rejecteds->whereBetween('updated_at', [$from, $to])->get();
        $pendings->whereBetween('updated_at', [$from, $to])->get();

        //return $mentors;
        return view('admin.stats', compact('paids', 'actives', 'rejecteds', 'mentor', 'mentors', 'pendings'));

    }
    /*public function delete(Request $request, History $history){
    if(!$history){
    $request->session()->flash('failed', 'Transaction '.$history->tran_id.' does not exist');
    return back();
    }
    $history->delete();
    }*/
    public function details()
    {
        $this->validate(request(), [
            'details' => 'required',
        ]);
        $details = json_decode(request()->details);
        $users = User::all();
        return view('admin.records', compact('details', 'users'));
    }

    public function rollApprove(Rollover $roll)
    {
        if (env('error')) {
            return $this->showError(request());
        }

        $history = $roll->history;
        if ($history->status == 'paid' /* || carbon::now() > $history->return_date*/) {
            request()->session()->flash('failed', 'Rollover failed, investment paid');
            return back();
        }

        $randomstring = $this->randomstring(2) . date("Hismy");
        $total_amount = $this->return_amount($roll->tenure, $history->return_amount);
        if ($roll->type == '1') {
            $MyDateCarbon = $this->return_date($history->return_date, $roll->tenure);

            History::create([
                'tran_id' => $randomstring,
                'invest_date' => $history->return_date,
                'invest_amount' => $history->return_amount,
                'tenure' => $roll->tenure,
                'return_amount' => $total_amount,
                'proof' => $history->proof,
                'approved_date' => Carbon::now(),
                'return_date' => $MyDateCarbon,
                'status' => 'active',
                'user_id' => $history->user->id,
            ]);
            $roll->update(['status' => 1]);
            $history->update(['paid_date' => Carbon::now(), 'status' => 'paid']);

            request()->session()->flash('success', 'Rollover approved');
            return back();
        }

        if ($roll->type == '0') {
            $first = (5 / 100) * $total_amount;
            $second = $total_amount - ($first * 5);
            $MyDateCarbon = $history->return_date;

            for ($i = 0; $i < 5; $i++) {
                $randomstring = $this->randomstring(2) . date("Hismy");
                $MyDateCarbon = $this->return_date($MyDateCarbon, 1);

                History::create([
                    'tran_id' => $randomstring,
                    'invest_date' => $history->return_date,
                    'invest_amount' => $history->return_amount,
                    'tenure' => $roll->tenure,
                    'return_amount' => $first,
                    'proof' => $history->proof,
                    'approved_date' => Carbon::now(),
                    'return_date' => $MyDateCarbon,
                    'status' => 'active',
                    'user_id' => $history->user->id,
                ]);
            }

            $randomstring = $this->randomstring(2) . date("Hismy");
            $MyDateCarbon = $this->return_date($MyDateCarbon, ($roll->tenure - 5));
            History::create([
                'tran_id' => $randomstring,
                'invest_date' => $history->return_date,
                'invest_amount' => $history->return_amount,
                'tenure' => $roll->tenure,
                'return_amount' => $second,
                'proof' => $history->proof,
                'approved_date' => Carbon::now(),
                'return_date' => $MyDateCarbon,
                'status' => 'active',
                'user_id' => $history->user->id,
            ]);

            $roll->update(['status' => 1]);
            $history->update(['paid_date' => Carbon::now(), 'status' => 'paid']);

            $email = $history->user->email;
            $number = $history->user->number;
            $name = $history->user->name;
            $subject = 'Rollover Approved';

            $message = $name . ' your roll over request of transaction ' . $history->tran_id . ' has been approved.';

            Log::info($this->sms($number, urlencode($message)));

            Log::info($this->app($subject, $message, $email));

            Mail::to($email)->send(new Honeypays($message, $subject));

            request()->session()->flash('success', 'Rollover approved');
            return back();
        }
        return back();
    }

    public function rollDelete(Rollover $roll)
    {
        $roll->delete();

        request()->session()->flash('success', 'Rollover deleted');
        return back();
    }

    public function refundDelete(Refund $refund)
    {
        $refund->delete();

        request()->session()->flash('success', 'Refund deleted');
        return back();
    }

    public function refundApprove(Refund $refund)
    {
        if (env('error')) {
            return $this->showError(request());
        }

        $history = $refund->history;
        //return $refund;
        if ($history->status == 'paid' /* || carbon::now() > $history->return_date*/) {
            request()->session()->flash('failed', 'Refund failed, investment paid');
            return back();
        }
        $due = carbon::now()->addWeekdays(30);

        $refund->update(['status' => 1, 'due' => $due]);

        $history->update(['status' => 'refund']);

        $email = $history->user->email;
        $number = $history->user->number;
        $name = $history->user->name;
        $subject = 'Refund Approved';

        $message = $name . ' your refund of transaction ' . $history->tran_id . ' has been approved. Payment date is 30 working days from approved date - ' . carbon::now();

        Log::info($this->sms($number, urlencode($message)));

        Log::info($this->app($subject, $message, $email));

        Mail::to($email)->send(new Honeypays($message, $subject));

        request()->session()->flash('success', 'Refund approved');
        return back();
    }

    public function refundPay(Refund $refund)
    {
        $history = $refund->history;
        if ($history->status == 'refund_paid') {
            request()->session()->flash('failed', 'Investment already paid');
            return back();
        }

        $refund->update(['paid' => 1]);
        $history->update(['status' => 'refund_paid']);

        request()->session()->flash('success', 'Refund Paid');
        return back();
    }
}