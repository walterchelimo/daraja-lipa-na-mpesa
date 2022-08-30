<?php
require 'vendor/autoload.php';

use Carbon\Carbon;
    $phone=$_POST['pnumber'];
    $amt=$_POST['amt'];


    stkPush($amt,$phone);

function lipaNaMpesaPassword()
{
    //timestamp
    $timestamp = Carbon::rawParse('now')->format('YmdHms');
    //passkey
    $passKey ="bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";
    $businessShortCOde =174379;
    //generate password
    $mpesaPassword = base64_encode($businessShortCOde.$passKey.$timestamp);

    return $mpesaPassword;
}
    

   function newAccessToken()
   {
       $consumer_key="FSCwxOF1aMA8mkIFfNAjAaPnzJySN4pf";
       $consumer_secret="45zwc7Ls2m1E7Aud";
       $credentials = base64_encode($consumer_key.":".$consumer_secret);
       $url = "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials";


       $curl = curl_init();
       curl_setopt($curl, CURLOPT_URL, $url);
       curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Basic ".$credentials,"Content-Type:application/json"));
       curl_setopt($curl, CURLOPT_HEADER, false);
       curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
       $curl_response = curl_exec($curl);
       $access_token=json_decode($curl_response);
       curl_close($curl);
       
       return $access_token->access_token;
   }



   function stkPush($amount,$phone)
   {
       //    $user = $request->user;
       //    $amount = $request->amount;
         //$phone =  $request->phone;
       //    $formatedPhone = substr($phone, 1);//726582228
       //    $code = "254";
       //$phoneNumber = $code.$formatedPhone;//254726582228
      //$phone = $_GET['phone'];
      
       


       $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
       $curl_post_data = [
            'BusinessShortCode' =>174379,
            'Password' => lipaNaMpesaPassword(),
            'Timestamp' => Carbon::rawParse('now')->format('YmdHms'),
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'PartyA' => $phone,
            'PartyB' => 174379,
            'PhoneNumber' => $phone,
            'CallBackURL' => 'https://daraja.test.theairliftsacco.co.ke/complet.php',
            'AccountReference' => "The Coddiners",
            'TransactionDesc' => "lipa Na M-PESA"
        ];


       $data_string = json_encode($curl_post_data);


       $curl = curl_init();
       curl_setopt($curl, CURLOPT_URL, $url);
       curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.newAccessToken()));
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($curl, CURLOPT_POST, true);
       curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
       $curl_response = curl_exec($curl);
      // print_r($curl_response);
   }