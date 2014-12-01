<?php 
namespace Contao;
if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  360fusion  2011
 * @author     Darrell Martin <darrell@360fusion.co.uk>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


/**
 * Handle Paypal payments
 * 
 * @extends ModulePayPalPurchaseCheckout
 */

class PayPal_standard extends \ModuleEventsPaymentCheckout
{

	private $endpoint;
	private $host;
	private $gate;
	
	function __construct($api_username = null, $api_password = null, $api_signature = null, $return_url = null, $cancel_url = null, $payment_type = null, $currency_code = null, $environment = null,  $recurring = null, $billing_period = null, $billing_frequency = null, $subscriptionID = null, $custom = null) {
	
	$this->username = $api_username;
	$this->password = $api_password;
	$this->signature = $api_signature;
	$this->return_url = $return_url;
	$this->cancel_url = $cancel_url;
	$this->payment_type = $payment_type;
	$this->currency_code = $currency_code;
	$this->environment = $environment;
	$this->product_desc = $product_desc;
	$this->recurring = $recurring;
	$this->billing_period = $billing_period;
	$this->billing_frequency = $billing_frequency;
	$this->subscriptionID = $subscriptionID;
	$this->custom = $custom;
	
	
	
		$this->endpoint = '/nvp';
		if ($this->environment == 'live') {
		$this->host = "api-3t.paypal.com";
		$this->gate = 'https://www.paypal.com/cgi-bin/webscr?';
		$this->pdt = 'www.paypal.com';
		} else {
			//sandbox
			$this->host = "api-3t.sandbox.paypal.com";
			$this->gate = 'https://www.sandbox.paypal.com/cgi-bin/webscr?';
		   $this->pdt = 'www.sandbox.paypal.com';
	}
	
	}
	
	
		/**
	 * Process payment on confirmation page.
	 * 
	 * @access public
	 * @return void
	 */
	public function processPayment()
	{
		return true;
	}
		

	/**
	 * @return string URL of the "success" page
	 */
	private function getReturnTo() {
	    return sprintf($this->return_url,
		$this->getScheme(), $_SERVER['SERVER_NAME']);
	}

	/**
	 * @return string URL of the "cancel" page
	 */
	private function getReturnToCancel() {
		return sprintf($this->cancel_url,
		$this->getScheme(), $_SERVER['SERVER_NAME']);
	}

	/**
	 * @return HTTPRequest
	 */
	private function response($data) {

		
		$r = new \HTTPRequest($this->host, $this->endpoint, 'POST', true);
		$result = $r->connect($data);
		if ($result<400) return $r;
		return false;
   
	}
	


	private function buildQuery($data = array()) {
	
	    $data['USER'] = $this->username;
		$data['PWD'] = $this->password;
		$data['SIGNATURE'] = $this->signature;
		$data['VERSION'] = '52.0';
		$query = http_build_query($data);
		return $query;
	}

	
	/**
	 * Main payment function
	 * 
	 * If OK, the customer is redirected to PayPal gateway
	 * If error, the error info is returned
	 * 
	 * @param float $amount Amount (2 numbers after decimal point)
	 * @param string $desc Item description
	 * @param string $invoice Invoice number (can be omitted)
	 * @param string $currency 3-letter currency code (USD, GBP, CZK etc.)
	 * 
	 * @return array error info
	 */
	public function doExpressCheckout($amount, $desc, $currency, $invoice = '') {
		
		$commit = '&useraction=commit';
	     $currency = $this->currency_code;
		 $data = array(
		'PAYMENTACTION' =>$this->payment_type,
		'AMT' =>$amount,
		'RETURNURL' =>$this->getReturnTo(),
		'CANCELURL'  =>$this->getReturnToCancel(),
		'DESC'=>$desc,
		'NOSHIPPING'=>"1",
		'ALLOWNOTE'=>"1",
		'CURRENCYCODE'=>$currency,
		'METHOD' =>'SetExpressCheckout');
		
		$data['CUSTOM'] = $this->custom;
		
		if ($this->recurring) {
			$data['L_BILLINGTYPE0'] = 'RecurringPayments';
			$data['L_BILLINGAGREEMENTDESCRIPTION0'] = $desc;
			$commit = '';
		}
		

		if ($invoice) $data['INVNUM'] = $invoice;
/*		
		echo 'data:<br/> ';
		print_r($data);
		echo '<br/> <br/>';
*/
		
		$query = $this->buildQuery($data);
/*		
		echo 'query:<br/> ';
		print_r($query);
		echo '<br/> <br/>';
*/		
		
		$result = $this->response($query);
/*		
		echo 'result:<br/> ';
		print_r($result);
		echo '<br/> <br/>';
*/		

		if (!$result) return false;
		$response = $result->getContent();
/*		
		echo 'response:<br/> ';
		print_r($response);
		echo '<br/> <br/>';
*/
		$return = $this->responseParse($response);
/*		
		echo 'responseParse:<br/> ';
		print_r($return);
		echo '<br/> <br/>';
*/
		if (($return['ACK'] == 'Success') || ($return['ACK'] == 'SuccessWithWarning'))  {
			
//			echo 'Location: '.$this->gate.'cmd=_express-checkout'.$commit.'&token='.$return['TOKEN'].'';
//			exit;
			
			header('Location: '.$this->gate.'cmd=_express-checkout'.$commit.'&token='.$return['TOKEN'].'');
			die();
		}
		return($return);
	}

	
	
	public function getTransactionDetails($trans_id){
		$data = array(
		'TRANSACTIONID' => $trans_id,
		'METHOD' =>'GetTransactionDetails');
		$query = $this->buildQuery($data);

		$result = $this->response($query);

		if (!$result) return false;
		$response = $result->getContent();
		$return = $this->responseParse($response);
		return($return);
	}
	
	
		
	
		public function getPDTCheckoutDetails($tx_token,$auth_token){

				// read the post from PayPal system and add 'cmd'
				$req = 'cmd=_notify-synch';
				$req .= "&tx=$tx_token&at=$auth_token";
				// post back to PayPal system to validate
				$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
				$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
				$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
				$fp = fsockopen ('ssl://'.$this->pdt, 443, $errno, $errstr, 30);
			
			
				
	$return['ENVIRONMENT'] = $this->environment;
	$return['PDT'] = $this->pdt;
	
	
			
				if (!$fp) {
				// HTTP ERROR
					$return['HTTPERROR'] = 'ERROR';
				} else {
				fputs ($fp, $header . $req);
				// read the body data 
				$res = '';
				$headerdone = false;
				while (!feof($fp)) {
				$line = fgets ($fp, 1024);
				if (strcmp($line, "\r\n") == 0) {
				// read the header
				$headerdone = true;
				}
				else if ($headerdone)
				{
				// header has been read. now read the contents
				$res .= $line;
				}
				}
				
				// parse the data
				$lines = explode("\n", $res);
				
				$return['RES'] = $res;
					
				$keyarray = array();
				if (strcmp ($lines[0], "SUCCESS") == 0) {
						for ($i=1; $i<count($lines);$i++){
							list($key,$val) = explode("=", $lines[$i]);
							$keyarray[urldecode($key)] = urldecode($val);
						}
					//print_r($keyarray);
					$return = array();
					$return['FIRSTNAME'] = $keyarray['first_name'];
					$return['LASTNAME'] = $keyarray['last_name'];
					$return['RECEIVEREMAIL'] = $keyarray['payer_email'];
					$return['TRANSACTIONTYPE'] = $keyarray['txn_type'];
					$return['PAYMENTTYPE'] = $keyarray['payment_type'];
					$return['AMT'] = $keyarray['mc_gross'];
					$return['CURRENCYCODE'] = $keyarray['mc_currency'];
					$return['PAYMENTSTATUS'] = $keyarray['payment_status'];
					$return['TRANSACTIONID'] = $keyarray['txn_id'];
					$return['SUBSCRIPTIONID'] = $keyarray['custom'];
					$return['PAYERID'] = $keyarray['payer_id'];
				//	$return['ITEMDESCRIPTION'] = $keyarray['item_name'];
				}
				else if (strcmp ($lines[0], "FAIL") == 0) {
				// log for manual investigation
			//	$return['ERROR'] = 'Error';
				}
				}
				
				fclose ($fp);
		return($return);
	}
	
	public function getCheckoutDetails($token){
		$data = array(
		'TOKEN' => $token,
		'METHOD' =>'GetExpressCheckoutDetails');
		$query = $this->buildQuery($data);

		$result = $this->response($query);
		
		if (!$result) return false;
		$response = $result->getContent();

		$return = $this->responseParse($response);
	
		return($return);
	}
	
	
	public function callCreateRecurringPaymentsProfile($token, $payment_amount, $currency, $billing_period, $billing_frequency, $desc, $initialpayment){
		
		$data = array(
		'PROFILESTARTDATE' =>date('Y-m-d h:i:s'),
		'BILLINGPERIOD' => $billing_period,
		'BILLINGFREQUENCY' => $billing_frequency,
		'TOKEN' => $token,
		'DESC' => $desc,
		'AMT' => $payment_amount,
		'CURRENCYCODE'=>$currency,
		'METHOD' =>'CreateRecurringPaymentsProfile');
		
		if ($initialpayment >0){ $data['INITAMT'] = $initialpayment+$payment_amount; }
			
		$query = $this->buildQuery($data);

		$result = $this->response($query);
		
		if (!$result) return false;
		$response = $result->getContent();

		$return = $this->responseParse($response);
	
		return($return);
	}
	
	

	public function doExpPayment($amount,$currency_code,$token,$payer){

		$details = $this->getCheckoutDetails($token);
		if (!$details) return false;
	
		$data = array(
		'PAYMENTACTION' => 'Sale',
		'PAYERID' => $payer,
		'TOKEN' => $token,
		'AMT' => $amount,
		'CURRENCYCODE'=> $currency_code,
		'METHOD' =>'DoExpressCheckoutPayment');
		
//		print_r($data);
//		exit;
		
		$query = $this->buildQuery($data);

		$result = $this->response($query);

		if (!$result) return false;
		$response = $result->getContent();
		$return = $this->responseParse($response);
		return($return);
	}
	
	
	public function doPayment($amount,$currency_code){
		$token = $_GET['token'];
		$payer = $_GET['PayerID'];
		$details = $this->getCheckoutDetails($token);
		if (!$details) return false;
	
		$data = array(
		'PAYMENTACTION' => 'Sale',
		'PAYERID' => $payer,
		'TOKEN' => $token,
		'AMT' => $amount,
		'CURRENCYCODE'=> $currency_code,
		'METHOD' =>'DoExpressCheckoutPayment');
		
//		print_r($data);
//		exit;
		
		$query = $this->buildQuery($data);

		$result = $this->response($query);

		if (!$result) return false;
		$response = $result->getContent();
		$return = $this->responseParse($response);
		return($return);
	}

	private function getScheme() {
		$scheme = 'http';
		if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on') {
			$scheme .= 's';
		}
		return $scheme;
	}

	private function responseParse($resp){
		$a=explode("&", $resp);
		$out = array();
		foreach ($a as $v){
			$k = strpos($v, '=');
			if ($k) {
				$key = trim(substr($v,0,$k));
				$value = trim(substr($v,$k+1));
				if (!$key) continue;
				$out[$key] = urldecode($value);
			} else {
				$out[] = $v;
			}
		}
		return $out;
	}
}
?>