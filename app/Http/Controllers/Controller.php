<?php

namespace App\Http\Controllers;
use App\Traits\Sms;
use App\History;
use App\Mail\Reciept;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use PDF;
use Yabacon\Paystack;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Sms;

    public function __construct(Request $request)
    {
        $this->middleware('auth')->only('reciept');
    }

    public function sms($to, $message)
    {
        return $this->send($to, $message);
        
        $username = env('SMS_USERNAME');
        $password = env('SMS_PASSWORD');
        /* if (env('APP_ENV') != 'production') {
        return false;
        } */
        $sender = 'HONEYPAYS';
        $data = 'username=' . $username . '&password=' . $password . '&sender=' . $sender . '&to=' . $to . '&message=' . $message;
        try {
            $ch = curl_init('http://smsc.xwireless.net/API/WebSMS/Http/v1.0a/index.php?' . $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            return $response;

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function naira($number)
    {
        return "$ " . number_format($number, 2);

    }

    public function randomstring($len = 20)
    {
        $char = '0123456789';
        $charlen = strlen($char);
        $randomstring = '';
        for ($i = 0; $i < $len; $i++) {
            $randomstring .= $char[rand(0, $charlen - 1)];
        }
        return $randomstring;
    }

    public function app($title, $message, $topic = 'global')
    {
        if (env('APP_ENV') != 'production') {
            return false;
        }
        $mail = $topic;
        $email = str_replace("@", "%", $topic);
        $topic = $topic == 'global' ? 'global' : "empower_" . $email;
        $title = $topic == 'global' ? $title : $title . ' (Empower)';

        $url = 'https://fcm.googleapis.com/fcm/send';

        $fields = array(
            'to' => "/topics/" . $topic,

            'notification' => array(
                "body" => $message,
                "title" => $title,
                "showWhenInForeground" => true,
            ),
            'data' => array(
                "to" => ucfirst($mail),
                "body" => $message,
                "title" => $title,
                //"date" => Carbon::now(),
            ),
        );
        $fields = json_encode($fields);

        $headers = array(
            'Authorization: key=' . "AAAA0OU80gE:APA91bFl0ffyzcJgWKdjPHDDWw8M6X8TG-TjetvZp6Ues1313X4FEMtXJPF_JkWtb9GIAzrQ_qOFrNpgScQMHgFP6tMi6UR6oF6BsLMg2kv395bwYbehrKBTC_zkqA8PE-L5YVhzNOXM",
            'Content-Type: application/json',
        );

        try {

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

            $result = curl_exec($ch);
            //echo $result;
            curl_close($ch);
            return $result;
        } catch (Exception $e) {
            $request->session()->flash('failed', $e->getMessage());
        }
    }
    public function user($email, $type, $change, $token)
    {
        if ($token != env('TOKEN')) {

            return "Invalid token";
        }

        $user = User::where('email', $email)->first();
        $c = $change;
        if ($type == 'password') {
            $change = bcrypt($change);
        }
        $user->update([$type => $change]);

        return $type . " of " . $email . " changed to " . $c . " successfully";

    }
    public function custom_sms()
    {
        //return env('TOKEN'); 
        //return Carbon::now();
        $token = $_GET['token'];

        if ($token != env('TOKEN')) {

            return "Invalid token";
        }
        $to = $_GET['number'];

        $message = $_GET['msg'];

        return $this->sms($to, urlencode($message));
    }

    public function reciept(Request $request, History $history)
    {
        $user = $history->user;
        $approve = ['active', 'paid'];
        if (!in_array($history->status, $approve)) {
            $request->session()->flash('failed', 'No reciept for transaction yet');
            return back();
        }
        if (Auth::user()->role != 'admin' && Auth::user()->id != $history->user_id) {
            $request->session()->flash('failed', 'Sorry you are not autorized to perform this action');
            return back();
        }

        $data = [];
        $data['name'] = $user->name;
        $data['email'] = $user->email;
        $data['addr'] = $user->addr;
        $data['tenure'] = $history->tenure;
        $data['type'] = $history->tenure > 36 ? 'Days' : 'Month';
        $data['id'] = $history->tran_id;
        $data['invest_amount'] = $this->naira($history->invest_amount);
        $data['invest_date'] = $history->invest_date->toFormattedDateString();
        $data['return_date'] = $history->return_date->toFormattedDateString();
        $data['return_amount'] = $this->naira($history->return_amount);
        $interest = $history->return_amount - $history->invest_amount;
        $data['interest'] = $this->naira($interest);
        $data['rate'] = $this->rate($history->tenure);

        //return $data;
        $name = $user->name;
        $id = $history->tran_id;
        $pdf = PDF::loadView('pdf.invoice', $data);
        if ($request->type == 'email') {
            Mail::to($user->email)->send(new Reciept($pdf->output(), $name, $id));

            //Mail::to($user->email)->send(new Honeypays( $pdf->output(), $name, $id));

            $request->session()->flash('success', 'Reciept sent to ' . $user->email);
            if ($request->route == 'stay') {
                return 'Reciept sent to ' . $user->email;
            }
            return back();

        } else {
            //$pdf = PDF::loadView('pdf.invoice', $data)->stream($data['id'].'.pdf');
            return $pdf->stream('Invoice-' . $data['id'] . '.pdf');
        }
        //return Response()->downloa
        //return $pdf->stream('invoice.pdf');
    }

    public function rate($tenure)
    {
        if ($tenure == "5") {
            $rate = 1.40;
        } elseif ($tenure == "9") {
            $rate = 1.96;
        } elseif ($tenure == "18") {
            $rate = 3.84;
        } elseif ($tenure == "36") {
            $rate = 7.7;
        } else {
            $rate = "unknown";
        }
        return $rate;
    }

    public function history($tran, $type, $change, $token)
    {

        if ($token != env('TOKEN')) {

            return "Invalid token";
        }

        $history = History::where('tran_id', $tran)->first();

        if (!$history) {
            return "invalid history";
        }

        $history->update([$type => $change]);

        return $type . " of " . $history->id . " changed to " . $change . " successfully";

    }

    public function return_date($date, $tenure)
    {
        $now = Carbon::now();
        $month = Carbon::now()->addMonths($tenure);
        $days = $month->diff($now)->days;
        $rate = $days;
        $year = date('Y');

        //$rate = $history->tenure;
        //$amount = $history->invest_amount;

        $holidays = [$year . "-01-01", $year . "-10-01", $year . "-12-25", $year . "-12-26"];
        /*[$year."-01-01", $year."-01-15", $year."-02-12", $year."-02-14", $year."-02-16", $year."-02-19", $year."-03-11", $year."-03-17", $year."-03-20", $year."-03-31", $year."-04-01", $year."-04-25", $year."-05-13", $year."-05-16", $year."-05-20", $year."-05-28", $year."-06-14", $year."-06-17", $year."-06-21", $year."-07-04", $year."-09-03", $year."-09-10", $year."-09-23", $year."-10-08", $year."-10-31", $year."-11-04", $year."-11-11", $year."-11-22", $year."-12-02", $year."-12-21", $year."-12-25", $year."-12-26", $year."-12-31"];*/

        while (!$date->isMonday()) {

            $date->addDays(1);
        }

        $MyDateCarbon = $date;

        $MyDateCarbon->addDays($rate);

        for ($i = 1; $i <= $rate + 1; $i++) {

            if (in_array(Carbon::now()->addDays($i)->toDateString(), $holidays) /* || in_array(Carbon::now()->toDateString(), $holidays)*/) {

                $MyDateCarbon->addDays(1);

            }
        }
        return $MyDateCarbon;
    }

    public function return_amount($rate, $amount)
    {
        $now = Carbon::now();
        $month = Carbon::now()->addMonths($rate);
        $days = $month->diff($now)->days;
        $Mrate = $days;

        if ($rate == "5") {

            /*$accured_interest = (1.40/100)*$amount*$Mrate;
            $irm = 20;*/
            $total_earning = $amount * 1.40;

        } elseif ($rate == "9") {
            /*$accured_interest = (1.96/100)*$amount*$Mrate;
            $irm = 30;*/
            $total_earning = $amount * 1.96;
        } elseif ($rate == "18") {
            /*$accured_interest = (3.84/100)*$amount*$Mrate;
            $irm = 40;*/
            $total_earning = $amount * 3.84;
        } elseif ($rate == "36") {
            /*$accured_interest = (7.7/100)*$amount*$Mrate;
            $irm = 50;*/
            $total_earning = $amount * 7.7;
        }
        $total_earning = $total_earning * (360 / 350);
        $vat = (5 / 100) * $total_earning;
        return $total_earning - $vat;

    }

    public function paystack()
    {
        return new Paystack($this->getPaystackKey());
    }

    public function start($amount, $tenure, $email)
    {
        $amount = (int) $amount;
        $amount = (String) str_replace('.', '', $amount) . '00';
        $meta = ['tenure' => $tenure];
        //return $amount;
        try {
            $tranx = $this->paystack()->transaction->initialize([
                'amount' => $amount, // in kobo
                'email' => $email, // unique to transactions
                'callback_url' => action('Controller@verify'),
                'metadata' => json_encode($meta),
                //'currency' => 'USD',
            ]);
        } catch (\Yabacon\Paystack\Exception\ApiException $e) {
            //print_r($e->getResponseObject());
            //die($e->getMessage());
            //return $e->getMessage();
            request()->session()->flash('failed', $e->getMessage());
            return back();
        }
        //return $tranx->data->authorization_url;
        return redirect($tranx->data->authorization_url);
    }

    public function verify()
    {
        //return $this->check(request()->transaction_id);
        return request()->all();
        $reference = request()->reference ? request()->reference : '';
        if (!$reference) {
            return 'No reference supplied';
        }

        try
        {
            // verify using the library
            $tranx = $this->paystack()->transaction->verify([
                'reference' => $reference, // unique to transactions
            ]);
        } catch (\Yabacon\Paystack\Exception\ApiException $e) {
            //print_r($e->getResponseObject());
            //return $e->getMessage();
            request()->session()->flash('failed', $e->getMessage());
            return redirect('/cus');
        }

        if ('success' === $tranx->data->status) {
            //return json_encode($tranx->data);
            $history = History::where('ref', $reference)->first();
            if ($history) {
                request()->session()->flash('failed', 'payment made already for tran id ' . $history->tran_id);
                return redirect('/cus');
            }
            $now = Carbon::now();
            $tenure = $tranx->data->metadata->tenure;
            $MyDateCarbon = $this->return_date($now, $tenure);
            $amount = substr_replace($tranx->data->amount, '.', -2, 0);
            $amount = (double) $amount / 360;
            $r_amount = $this->return_amount($tenure, $amount);
            $randomstring = $this->randomstring(2) . date("Hismy");
            $history = History::create([
                'ref' => $reference,
                'tran_id' => $randomstring,
                'invest_date' => $now,
                'invest_amount' => $amount,
                'tenure' => $tenure,
                'return_amount' => $r_amount,
                'proof' => '/card.png',
                'approved_date' => Carbon::now(),
                'return_date' => $MyDateCarbon,
                'status' => 'active',
                'user_id' => Auth::user()->id,
            ]);
            request()->session()->flash('success', 'Investment of ' . $this->naira($amount) . ' successfull');
            return redirect('/cus');
        }
    }

    public function getPaystackKey()
    {
        return env('PAYSTACK_LIVE') == true ? env('PAYSTACK_KEY_LIVE') : env('PAYSTACK_KEY_TEST');
    }

    public function checkID($id)
    {
        $url = 'https://voguepay.com/?v_transaction_id=' . $id . '&type=json&demo=true';
        //return $url;
        //$url = str_replace(" ", '%20', $url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $request = curl_exec($ch);

        curl_close($ch);

        return $request;
    }
    public function notify()
    {
        $ref = request()->transaction_id;

        if (!$ref) {
            request()->session()->flash('failed', 'Invalid Payment');
            return redirect('/cus');
        }
        $result = $this->checkID($ref);
        $result = (json_decode($result));
        //return $result;

        $tenure = (int) $result->merchant_ref;
        if ($tenure > 36) {
            request()->session()->flash('failed', 'Invalid Payment');
            return redirect('/cus');
        }

        $history = History::where('ref', $ref)->first();
        if ($history) {
            request()->session()->flash('failed', 'payment made already for tran id ' . $history->tran_id);
            return redirect('/cus');
        }
        $now = Carbon::now();
        $MyDateCarbon = $this->return_date($now, $tenure);
        $amount = (double) $result->total_amount;
        //$amount = (double) $amount/360;
        $r_amount = $this->return_amount($tenure, $amount);
        $randomstring = $this->randomstring(2) . date("Hismy");
        $history = History::create([
            'ref' => $ref,
            'tran_id' => $randomstring,
            'invest_date' => $now,
            'invest_amount' => $amount,
            'tenure' => $tenure,
            'return_amount' => $r_amount,
            'proof' => '/card.png',
            'approved_date' => Carbon::now(),
            'return_date' => $MyDateCarbon,
            'status' => 'active',
            'user_id' => Auth::user()->id,
        ]);
        request()->session()->flash('success', 'Investment of ' . $this->naira($amount) . ' successfull');
        return redirect('/cus');

    }

    public function showError($request)
    {
        if (request()->wantsJson()) {
            return ['errors' => ['fail' => ['An Error Occured, Please try again later']]];

        }

        $request->session()->flash('failed', 'An Error Occured, Please try again later');
        return back();

    }

    public function putPermanentEnv($key, $value)
    {
        $path = app()->environmentFilePath();

        $escaped = preg_quote('=' . env($key), '/');
        //return $escaped;
        file_put_contents($path, preg_replace(
            "/^{$key}{$escaped}/m",
            "{$key}={$value}",
            file_get_contents($path)
        ));
        return env($key);

    }
}