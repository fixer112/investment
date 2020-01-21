<?php
namespace App\Traits;

Trait Sms{
public function send($to, $message)
    {
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
}