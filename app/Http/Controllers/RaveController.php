<?php
namespace App\Http\Controllers;

use App\History;

//use the Rave Facade
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Rave;

class RaveController extends Controller
{

    /**
     * Initialize Rave payment process
     * @return void
     */
    public function initialize()
    {
        //This initializes payment and redirects to the payment gateway
        //The initialize method takes the parameter of the redirect URL
        Rave::initialize(route('callback'));
    }

    /**
     * Obtain Rave callback information
     * @return void
     */
    public function callback()
    {

        $data = Rave::verifyTransaction(request()->txref);

        $chargeResponsecode = $data->data->chargecode;
        $chargeAmount = $data->data->amount;
        $chargeCurrency = $data->data->currency;
        $reference = request()->txref;

        if ($chargeResponsecode == "00" || $chargeResponsecode == "0") {
            // transaction was successful...
            // please check other things like whether you already gave value for this ref
            // if the email matches the customer who owns the product etc
            //Give Value and return to Success page
            $now = Carbon::now();
            $tenure = $data->data->meta[0]->metavalue;
            $MyDateCarbon = $this->return_date($now, $tenure);
            $amount = $chargeAmount;
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

        } else {
            //Dont Give Value and return to Failure page

            request()->session()->flash('failed', 'Investment of ' . $this->naira($amount) . ' failed, pls try again');
            return redirect('/cus');

        }

        //dd($data);
        // Get the transaction from your DB using the transaction reference (txref)
        // Check if you have previously given value for the transaction. If you have, redirect to your successpage else, continue
        // Comfirm that the transaction is successful
        // Confirm that the chargecode is 00 or 0
        // Confirm that the currency on your db transaction is equal to the returned currency
        // Confirm that the db transaction amount is equal to the returned amount
        // Update the db transaction record (includeing parameters that didn't exist before the transaction is completed. for audit purpose)
        // Give value for the transaction
        // Update the transaction to note that you have given value for the transaction
        // You can also redirect to your success page from here

    }
}
