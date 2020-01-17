<?php

/**
 * Taken from hammonhim SMS provider
 *
 * @author @lkulbii (Telegram)
 */

namespace App;

class SMSProvider
{

  private $client;

  private $username = 'efi.academia@gmail.com';
  private $password = 'ahbuhcguko';
  private $accID = 3881;
  private $systemPassword = 'itnewslettrSMS';

  public function __construct()
  {
    $opts = array(
      'http' => array(
        'user_agent' => 'PHPSoapClient'
      ),
      'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
      )
    );
    $context = stream_context_create($opts);
    $wsdlUrl = 'http://api.itnewsletter.co.il/webServices/WebServiceSMS.asmx?wsdl';
    $soapClientOptions = array(
      'stream_context' => $context,
      'cache_wsdl' => 0,
      'trace' => 1,
    );

    $this->client = new \SoapClient($wsdlUrl, $soapClientOptions);

    // $this->client = new \SoapClient("http://api.itnewsletter.co.il/webServices/WebServiceSMS.asmx?wsdl");

    // var_dump($client);
  }

  public function getSmsCount()
  {
    $params = [];

    $params['un'] = $this->username;
    $params['pw'] = $this->password;
    $params['accid'] = $this->accID;
    $params['sysPW'] = $this->systemPassword;
    $params['t'] = date("Y-m-d H:i:s");

    $result = $this->client->getSmsCount($params)->getSmsCountResult;

    var_dump($result);
  }

  public function sendSms($message, $number)
  {
    $params['un'] = $this->username;
    $params['pw'] = $this->password;
    $params['accid'] = $this->accID;
    $params['sysPW'] = $this->systemPassword;
    $params['t'] = date("Y-m-d H:i:s");

    $params["txtUserCellular"] = "0522798004";
    $params["destination"] = $number;
    $params["txtSMSmessage"] = $message;
    $params["dteToDeliver"] = "";

    $result = $this->client->sendSMSrecipients($params)->sendSMSrecipientsResult;
  }
}
