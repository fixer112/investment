<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;
use App\User;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sms($to, $message){
    	$username = env('SMS_USERNAME');
    	$password = env('SMS_PASSWORD');
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
    return "N". number_format($number, 2);

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
public function custom_sms($token){
    //return env('TOKEN');
    if ($token != env('TOKEN')) {
        
        return "Invalid token";
    }
        $to = $_GET['number'];

        $message = $_GET['msg'];

       return $this->sms($to, urlencode($message));
}

}
