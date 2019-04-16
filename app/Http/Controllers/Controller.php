<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;
use App\User;
use App\History;
use Illuminate\Support\Facades\Auth;
use PDF;
use App\Mail\Reciept;
use App\Mail\Honeypays;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

     public function __construct(Request $request)
    {
            $this->middleware('auth')->only('reciept');
    }

    public function sms($to, $message){
    	$username = env('SMS_USERNAME');
    	$password = env('SMS_PASSWORD');
        if (env('APP_ENV') != 'production') {
            return false;
        }
    	$sender = 'HONEYPAYS';
    	$data = 'username='.$username.'&password='.$password.'&sender='.$sender.'&to='.$to.'&message='.$message;
        try {
    	$ch = curl_init('http://smsc.xwireless.net/API/WebSMS/Http/v1.0a/index.php?'.$data);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	$response = curl_exec($ch);
    	curl_close($ch);
    	return $response;
            
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
    public function naira($number){
    return "N ". number_format($number, 2);

    }

    public function randomstring($len = 20){
	$char = '0123456789';
	$charlen = strlen($char);
	$randomstring = '';
	for ($i = 0; $i < $len ; $i++) {
		$randomstring .= $char[rand(0, $charlen-1)];
	}
	return $randomstring;
	}

    public function app($title, $message, $topic='global') {
        if (env('APP_ENV') != 'production') {
            return false;
        }
    $mail = $topic;
    $email = str_replace("@", "%", $topic);
    $topic = $topic =='global'? 'global' : "empower_".$email;
    $title = $topic == 'global'? $title : $title.' (Empower)';

    $url = 'https://fcm.googleapis.com/fcm/send';

    $fields = array (
            'to' => "/topics/".$topic,

            'notification' => array (
                    "body" => $message,
                    "title"=> $title,
                    "showWhenInForeground" => true
            ),
             'data' => array (
                    "to" => ucfirst($mail),
                    "body" => $message,
                    "title"=> $title,
                    //"date" => Carbon::now(),
            )
    );
    $fields = json_encode ( $fields );

    $headers = array (
            'Authorization: key=' . "AAAA0OU80gE:APA91bFl0ffyzcJgWKdjPHDDWw8M6X8TG-TjetvZp6Ues1313X4FEMtXJPF_JkWtb9GIAzrQ_qOFrNpgScQMHgFP6tMi6UR6oF6BsLMg2kv395bwYbehrKBTC_zkqA8PE-L5YVhzNOXM",
            'Content-Type: application/json'
    );

    try {
        
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

    $result = curl_exec ( $ch );
    //echo $result;
    curl_close ( $ch );
    return $result;
    } catch (Exception $e) {
        $request->session()->flash('failed', $e->getMessage());
    }
}
public function user($email, $type, $change, $token){
    if ($token != env('TOKEN')) {
        
        return "Invalid token";
    }

    $user = User::where('email',$email)->first();
    $c = $change;
    if ($type=='password') {
        $change = bcrypt($change);
    }
    $user->update([$type => $change]);

    return $type." of ".$email." changed to ".$c." successfully";

}
public function custom_sms(){
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

public function reciept(Request $request, History $history){
    $user = $history->user;
    $approve = ['active','paid'];
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
    $name =$user->name;
    $id = $history->tran_id;
    $pdf = PDF::loadView('pdf.invoice', $data);
    if ($request->type == 'email') {
     Mail::to($user->email)->send(new Reciept( $pdf->output(), $name, $id));

     //Mail::to($user->email)->send(new Honeypays( $pdf->output(), $name, $id));

     $request->session()->flash('success', 'Reciept sent to '.$user->email);
     if ($request->route == 'stay') {
         return 'Reciept sent to '.$user->email;
     }
     return back();

    }else {
    //$pdf = PDF::loadView('pdf.invoice', $data)->stream($data['id'].'.pdf');
    return $pdf->stream('Invoice-'.$data['id'].'.pdf');
    }
    //return Response()->downloa
    //return $pdf->stream('invoice.pdf');
}

public function rate($tenure){
     if ($tenure == "5") {
            $rate = 1.40;
        }elseif ($tenure == "9") {
             $rate =  1.96;
        }elseif ($tenure == "18") {
             $rate = 3.84;
        }elseif ($tenure == "36") {
             $rate =  7.7;
        }else{
            $rate = "unknown";
        }
        return $rate;
}

public function history($tran, $type, $change, $token){

    if ($token != env('TOKEN')) {
        
        return "Invalid token";
    }

    $history = History::where('tran_id',$tran)->first();
    
    if (!$history) {
      return "invalid history";  
    }
    
    $history->update([$type => $change]);

    return $type." of ".$history->id." changed to ".$change." successfully";

}

}
