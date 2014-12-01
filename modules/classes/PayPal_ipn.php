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
 * Handle Paypal ipn (Standard) payments
 * 
 * @extends ModulePayPalPurchaseCheckout
 */

class PayPal_ipn extends \ModuleEventsPaymentCheckout
{



	private $endpoint;
	private $host;
	private $gate;
	private $ipn_log;                    // bool: log IPN results to text file?
    private $last_error; 
	private $ipn_log_file;               // filename of the IPN log
	private $ipn_response;               // holds the IPN response from paypal   
	private $ipn_data = array();         // array contains the POST values for IPN
   
	function __construct($businessAccount = null, $return_url = null, $cancel_url = null, $ipn = null, $product_desc = null, $currency_code = null, $amount = null, $environment = null, $recurring = null, $billing_period = null, $billing_frequency = null, $subscriptionID = null, $custom = null, $subscriptionsInitialPayment = null) {
	
	// make vars public for this class
	$this->businessAccount = $businessAccount;
	$this->return_url = $return_url;
	$this->cancel_url = $cancel_url;
	$this->ipn = $ipn;
	$this->product_desc = $product_desc;
	$this->currency_code = $currency_code;
	$this->amount = $amount;
	$this->environment = $environment;
	$this->recurring = $recurring;
	$this->billing_period = $billing_period;
	$this->billing_frequency = $billing_frequency;
	$this->subscriptionID = $subscriptionID;
	$this->custom = $custom;
	$this->initial_payment = $subscriptionsInitialPayment;
		
	$this->last_error = '';
    $this->ipn_log_file = '.ipn_results.log';
    $this->ipn_log = true; 
    $this->ipn_response = '';
	  
		$this->endpoint = '/nvp';
		if ($this->environment == 'live') {
		$this->host = "api-3t.paypal.com";
		$this->gate = 'https://www.paypal.com/cgi-bin/webscr?';
		$this->ipngate = 'https://www.paypal.com/cgi-bin/webscr?';
		} else {
			//sandbox
			$this->host = "api-3t.sandbox.paypal.com";
			$this->gate = 'https://www.sandbox.paypal.com/cgi-bin/webscr?';
			$this->ipngate = 'https://www.sandbox.paypal.com/cgi-bin/webscr?';
	}
	
	
		if (($this->environment != "live") || ($this->environment != "sandbox")) {   // Set IPN Values
			
			// Disable referer check in Contao settings MUST be ticked for IPN to work
			
			include('system/config/localconfig.php');
			
	

		
			$this->dbHost = $GLOBALS['TL_CONFIG']['dbHost'];
			$this->database = $GLOBALS['TL_CONFIG']['dbDatabase'];
			$this->dbUser = $GLOBALS['TL_CONFIG']['dbUser'];
		    $this->dbPassword = $GLOBALS['TL_CONFIG']['dbPass'];
		    
		    
		mysql_connect($this->dbHost, $this->dbUser, $this->dbPassword);
		@mysql_select_db($this->database) or die( "Unable to select database");
		  
			$query  = mysql_query("SELECT environment FROM tl_evtreg_mods WHERE type='paypal'");
		    $query_values = mysql_fetch_assoc($query);
			$thisEnvironment = $query_values['environment'];
			

			 if ($thisEnvironment == "live") { 
				$this->host = "api-3t.paypal.com";
				$this->gate = 'https://www.paypal.com/cgi-bin/webscr?';
				$this->ipngate = 'https://www.paypal.com/cgi-bin/webscr?';
		    } else {
		        $this->host = "api-3t.sandbox.paypal.com";
			    $this->gate = 'https://www.sandbox.paypal.com/cgi-bin/webscr?';
			    $this->ipngate = 'https://www.sandbox.paypal.com/cgi-bin/webscr?';	
		    }
		}
		
	//echo 'this->custom = '.	$this->custom = $custom;
//	exit;
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


	private function buildQuery($data = array()) {
	    $data['rm'] = '1';

		 if ($this->recurring  == 1)  { // For Recurring  	
		   	$data['cmd'] = '_xclick-subscriptions';
			$data['a3'] = $this->amount;
			


		switch ($this->billing_period) {
			case "Day":
			$duration = 'D';
			break;
			case "Week":
			$duration = 'W';
			break;
			case "Month":
			$duration = 'M';
			break;
			case "Year":
			$duration = 'Y';
			break;
			default:
			$duration = 'Y';
		}
			
			
			/* Subscription duration. Specify an integer value in the allowable range for the units of duration that you specify with t3 */
			$data['p3'] = $this->billing_frequency;
			
			/*
			Regular subscription units of duration.  Allowable values are: 
				D – for days; allowable range for p3 is 1 to 90
				W – for weeks; allowable range for p3 is 1 to 52
				M – for months; allowable range for p3 is 1 to 24
				Y – for years; allowable range for p3 is 1 to 5
			*/
			$data['t3'] = $duration;
			
			/*
				Recurring payments. Subscription payments recur unless subscribers cancel their subscriptions before the end of the current billing cycle or you limit the number of times that payments recur with the value that you specify for srt. 
				Allowable values are: 
					0 – subscription payments do not recur 
				    1 – subscription payments recur
			*/
			$data['src'] = '1';
			
			/*
				Reattempt on failure. If a recurring payment fails, PayPal attempts to collect the payment two more times before canceling the subscription. 
				Allowable values are: 
				 0 – do not reattempt failed recurring payments
				 1 – reattempt failed recurring payments before canceling
			*/
			$data['sra '] = '1';
			
			
						
			if ($this->initial_payment >0){ 
				$data['a1'] = $this->initial_payment+$this->amount;
				$data['p1'] = $this->billing_frequency;
				$data['t1'] = $duration;
				 }

			
			
			}else{
				$data['cmd'] = '_xclick';
				$data['amount'] = $this->amount;
		 }
		 
		 // parse through the subscription id from our DB 
		 /*
		Pass-through variable for you to track product or service 
		purchased or the contribution made. The value you specify is 
		passed back to you upon payment completion
		 */
	    $data['item_number'] = $this->subscriptionID;
		$data['custom'] = $this->custom;
		$data['upload'] = '1';
		$data['business'] = $this->businessAccount;
		$data['lc'] = strtoupper($GLOBALS['TL_LANGUAGE']);
		$data['charset'] = 'UTF-8';
		$data['no_note'] = '1';
		$data['return'] = $this->return_url.'?action=success';
		$data['cancel_return'] = $this->cancel_url;
		$data['notify_url'] = $this->ipn.'?action=ipn';
		$data['item_name'] = $this->product_desc;
		$data['currency_code'] = $this->currency_code;
		$data['tax'] ="0.00";

		$query = http_build_query($data);
		return $query;
	}

	
	/**
	 * Main payment function
	 * 
	 * If OK, the customer is redirected to PayPal gateway
	 * 
	 * @return array error info
	 */
	public function doPaymentStandard() {
	     $currency = $this->currency_code;
		 $data = array();
		 $query = $this->buildQuery($data);
		 header('Location: '.$this->gate.'&'.$query.'');
		 die();
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
	
	
	
	public function validate_ipn() {

      // parse the paypal URL
      $url_parsed=parse_url($this->ipngate);        

      // generate the post string from the _POST vars aswell as load the
      // _POST vars into an arry so we can play with them from the calling
      // script.
      $post_string = '';    
      foreach ($_POST as $field=>$value) { 
         $this->ipn_data["$field"] = $value;
         $post_string .= $field.'='.urlencode(stripslashes($value)).'&'; 
      }
	  

      $post_string.="cmd=_notify-validate"; // append ipn command

      // open the connection to paypal
      $fp = fsockopen($url_parsed[host],"80",$err_num,$err_str,30); 
      if(!$fp) {
          
         // could not open the connection.  If loggin is on, the error message
         // will be in the log.
         $this->last_error = "fsockopen error no. $errnum: $errstr";
         $this->log_ipn_results(false);       
         return false;
         
      } else { 
 

 
       // Post the data back to paypal
         fputs($fp, "POST $url_parsed[path] HTTP/1.1\r\n"); 
         fputs($fp, "Host: $url_parsed[host]\r\n"); 
         fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n"); 
         fputs($fp, "Content-length: ".strlen($post_string)."\r\n"); 
         fputs($fp, "Connection: close\r\n\r\n"); 
         fputs($fp, $post_string . "\r\n\r\n"); 


			$this->debug .=  "POST $url_parsed[path] HTTP/1.1\r\n";
			$this->debug .=  "Host: $url_parsed[host]\r\n";
			$this->debug .=  "Content-length: ".strlen($post_string)."\r\n";
			$this->debug .=  'post_string: '.$post_string."\r\n";
			

         // loop through the response from the server and append to variable
         while(!feof($fp)) { 
            $this->ipn_response .= fgets($fp, 1024); 
         } 

         fclose($fp); // close connection
         
         	$this->debug .= 'ipn_response: '.$this->ipn_response."\r\n";

      }
      
      if (@eregi("HTTP/1.0 302 Found",$this->ipn_response)) {
  

         // Valid IPN transaction.
         $this->log_ipn_results(true);
         return true;       
         
      } else {
  
         // Invalid IPN transaction.  Check the log for details.
         $this->last_error = 'IPN Validation Failed.';
         $this->log_ipn_results(false);   
         return false;
         
      }
      
   }
   
   private function log_ipn_results($success) {
       
      if (!$this->ipn_log) return;  // is logging turned off?
      
        $text .= $this->debug;
            
      // Timestamp
      $text = '['.date('m/d/Y g:i A').'] - '; 
      
      // Success or failure being logged?
      if ($success) $text .= "SUCCESS!\n";
      else $text .= 'FAIL: '.$this->last_error."\n";
      
      // Log the POST variables
      $text .= "IPN POST Vars from Paypal:\n";
      foreach ($this->ipn_data as $key=>$value) {
         $text .= "$key=$value, ";
      }
 
      // Log the response from the paypal server
      $text .= "\nIPN Response from Paypal Server:\n ".$this->ipn_response;
      


		  
		 $subID = $this->ipn_data['item_number'];
     	 $thisPayerID = $this->ipn_data ['payer_id'].'-'.$subID;
     	
     	 
     	 	$text .= "\nPAYPAL PARSED:\n ";
  			$text .= "\nthisPayerID: ".$this->ipn_data ['payer_id'];
  			$text .= "\nsubID: ".$this->ipn_data ['item_number'];	
  			
  			


      	switch ($this->ipn_data ['txn_type']) {
      		
      		 // SUBSCRIPTION SIGN UP
		   case 'subscr_signup':     
		   
		   
 			mysql_connect($this->dbHost, $this->dbUser, $this->dbPassword);
		  @mysql_select_db($this->database) or die( "Unable to select database");
		  
				// first check to see if the user exists in the db
				$query  = "SELECT * FROM tl_member WHERE paypal_payer_id='$thisPayerID'";
				$thisrow = mysql_query($query);
				$num_rows = mysql_num_rows($thisrow);
				
				 $text .= "\nDB rows: ".$num_rows;	
				 
				//  record found
				if ($num_rows > 0) {
					
					
						$thisObjPaymentSub = "SELECT * FROM tl_paymentreg_subs WHERE id='$subID'";
						$result = mysql_query($thisObjPaymentSub);
						$row = mysql_fetch_array($result);
						$thisGroup = $row['subcriptionreg_groups'];
						
						$paymentreg_subcriptions = array();
						$paymentreg_subcriptions['id'] = $subID;
						$paymentreg_subcriptions['time'] = time();
						$paymentreg_subcriptions = serialize($paymentreg_subcriptions);


  					$text .= "\nCONTAO DB UPDATE:\n ";
  					$text .= "\npaypal_payer_id: ".$thisPayerID;
  					$text .= "\npaymentreg_subcriptions: ".$paymentreg_subcriptions;
  					$text .= "\ngroups: ".$thisGroup;
  
					 // If the user signs up set their subscription
					$queryuser  = "UPDATE tl_member SET subscription_signup=1, subscription_eot=0, subscription_cancelled=0, subscription_failed=0, paymentreg_subcriptions='$paymentreg_subcriptions', groups= '$thisGroup' WHERE paypal_payer_id='$thisPayerID'";
					mysql_query($queryuser);
					

				} else {
					// no record found
					  $text .= "\nNo user found in tl_member records\n ";
				}
      		break;
      		
      		 // SUBSCRIPTION CANCELLATION
      		 case 'subscr_cancel':     
      		 
 			mysql_connect($this->dbHost, $this->dbUser, $this->dbPassword);
		  @mysql_select_db($this->database) or die( "Unable to select database");
		 	 
      		 // If the user cancels the subscription set their subscription_cancelled to 1
				$query  = "UPDATE tl_member SET subscription_cancelled=1 WHERE paypal_payer_id='$thisPayerID'";
				mysql_query($query);
      		break;


      		// SUBSCRIPTION END OF TERM
      		case 'subscr_eot':
      		
 			mysql_connect($this->dbHost, $this->dbUser, $this->dbPassword);
		  @mysql_select_db($this->database) or die( "Unable to select database");
		  
	 		// If the users subscription ends term set their subscription_eot to 1
				$query  = "UPDATE tl_member SET subscription_eot=1 WHERE paypal_payer_id='$thisPayerID'";
				mysql_query($query);
      		break;
      		
      		// SUBSCRIPTION FAILED
      		case 'subscr_failed':     
      		
 			mysql_connect($this->dbHost, $this->dbUser, $this->dbPassword);
		  @mysql_select_db($this->database) or die( "Unable to select database");
		  
	 		// If the users subscription payment fails set their subscription_failed to 1
				$query  = "UPDATE tl_member SET subscription_failed=1 WHERE paypal_payer_id='$thisPayerID'";
				mysql_query($query);
      		break;
      		


      	}
      	
      	
      		

      // Write to log
      $fp=fopen($this->ipn_log_file,'a');
      fwrite($fp, $text . "\n\n"); 

      fclose($fp);  // close file
      
      
      		
   }
	
}

?>