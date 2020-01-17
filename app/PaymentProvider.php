<?php

namespace App;

class PaymentProvider
{


  public function __construct()
  {

  }


  public function getUrl($price, $prod_name, $callback, $customer_name, $email)
  {

    // sandbox не работает
    define('SANDBOX_ON', false);

    # Global Definetions :
    if(SANDBOX_ON) {
      $TerminalNumber = 1000; # TEST terminal
    } else {
      $TerminalNumber = 83459; # Company terminal
    }
    $UserName = 'mcA1GeIGQijZ9peqjSqt';   # API User
    $CreateInvoice = true;  # to Create Invoice (Need permissions to create invoice )
    $Operation = 1;  # = 1 - Bill Only , 2- Bill And Create Token , 3 - Token Only , 4 - Suspended Deal (Order).

    #Create Post Information
    // Account vars
    $vars = array();
    $vars['TerminalNumber'] = $TerminalNumber;
    $vars['UserName'] = $UserName;
    $vars["APILevel"] = "10"; // req
    $vars['codepage'] = '65001'; // unicode
    $vars["Operation"] = $Operation;

    if(SANDBOX_ON) {
      $vars["Language"] =  'en';
    } else {
      $vars["Language"] =  'he'; // page languge he- hebrew , en - english , ru , ar
    }
    $vars["CoinID"] = '1'; // billing coin , 1- NIS , 2- USD other , article :  http://kb.cardcom.co.il/article/AA-00247/0
    $vars["SumToBill"] = $price;// Sum To Bill
    $vars['ProductName'] = $prod_name; // Product Name , will how if no invoice will be created.

    $vars['SuccessRedirectUrl'] = "https://secure.cardcom.solutions/DealWasSuccessful.aspx"; // Success Page
    $vars['ErrorRedirectUrl'] = "https://secure.cardcom.solutions/DealWasUnSuccessful.aspx?customVar=1234"; // Error Page

    // Other Optional vars :

    // $vars["CancelType"] = "2"; # show Cancel button on start ,
    // $vars["CancelUrl"] = "http://www.yoursite.com/OrderCanceld";
    $vars['IndicatorUrl']  = env('APP_URL') . $callback; // Indicator Url \ Notify URL . after use -  http://kb.cardcom.co.il/article/AA-00240/0

    $vars["ReturnValue"] = "1234"; // Optional , ,recommended , value that will be return and save in CardCom system
    $vars["MaxNumOfPayments"] = "1"; // max num of payments to show  to the user

    $vars["ShowInvoiceHead"] = "true"; //  if show & edit Invoice Details on the page.
    $vars["InvoiceHeadOperation"] = "1"; //  0 = no create & show Invoice.  1 =(default)create Invoice.  2 = show Details Invoice but not create Invoice !

    if ($CreateInvoice)
    {
        // article for invoice vars:  http://kb.cardcom.co.il/article/AA-00244/0
        $vars['IsCreateInvoice'] = "true";
        // customer info :
        $vars["InvoiceHead.CustName"] = $customer_name; // customer name
        $vars["InvoiceHead.SendByEmail"] = "true"; // will the invoice be send by email to the customer
        $vars["InvoiceHead.Language"] = "he"; // he or en only
        $vars["InvoiceHead.Email"] = $email;

        // products info

        // Line 1
        $vars["InvoiceLines1.Description"] = $prod_name;
        $vars["InvoiceLines1.Price"] = $price;
        $vars["InvoiceLines1.Quantity"] = '1';

        // // line 2
        // $vars["InvoiceLines2.Description"] = "itme 2";
        // $vars["InvoiceLines2.Price"] = "10";
        // $vars["InvoiceLines2.Quantity"] = "1";

        // ********   Sum of all Lines Price*Quantity  must be equals to SumToBill ***** //
    }

    // Send Data To Bill Gold Server
    $r = $this->send($vars, 'https://secure.cardcom.solutions/Interface/LowProfile.aspx');
    parse_str($r,$responseArray);


    # Is Deal OK
    if ($responseArray['ResponseCode'] == "0") {
      return $responseArray;
    }
    # Show Error to developer only
    else {
      throw new \RuntimeException(urldecode($r), 500);
    }
  }


  private function send($vars,$PostVarsURL)
  {
    $urlencoded = http_build_query($vars);

    #init curl connection
    if( function_exists( "curl_init" )) {
      $CR = curl_init();
      curl_setopt($CR, CURLOPT_URL, $PostVarsURL);
      curl_setopt($CR, CURLOPT_POST, 1);
      curl_setopt($CR, CURLOPT_FAILONERROR, true);
      curl_setopt($CR, CURLOPT_POSTFIELDS, $urlencoded );
      curl_setopt($CR, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($CR, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($CR, CURLOPT_FAILONERROR,true);

      #actual curl execution perfom
      $r = curl_exec( $CR );
      $error = curl_error ( $CR );

      # some error , send email to developer
      if( !empty( $error )) {
        throw new \RuntimeException($error);
      }

      curl_close( $CR );
      return $r;
    } else {
      throw new \RuntimeException('No curl_init');
    }
  }


  public function onCallback($request)
  {
    file_put_contents('payment.log', 'API CALLBACK WORKS' . "\n", FILE_APPEND);
    file_put_contents('payment.log', json_encode($request->all(), JSON_UNESCAPED_UNICODE) . "\n ------- \n", FILE_APPEND);

    if($request->input('OperationResponse') != 0)
      throw new \RuntimeException('Response code = 0');

    $trans = app()->mdb->get('transaction', '*', [
      'code' => $request->input('lowprofilecode'),
    ]);

    if($trans['completed'])
      throw new \RuntimeException('Transaction already completed');

    app()->mdb->update('transaction', [
      'completed' => true
    ], [
      'code' => $request->input('lowprofilecode'),
    ]);

    $trans['data'] = json_decode($trans['data'], true);

    return $trans;
  }
}
