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

class PayPal_pro extends \ModuleEventsPaymentCheckout
{

	function __construct($api_username = null, $api_password = null, $api_signature = null, $proxy_host = null, $proxy_port = null, $environment = null, $use_proxy = false, $version = '98.0')
	{

		$this->api_username = $api_username;
	    $this->api_password = $api_password;
	    $this->api_signature = $api_signature;
		$this->environment = $environment;
		$this->use_proxy = $use_proxy;
		$this->version = $version;
		
		$this->endpoint = '/nvp';
		if ($this->environment == 'live') {
		$this->host = "https://api-3t.paypal.com";
		} else {
			//sandbox
			$this->host = "https://api-3t.sandbox.paypal.com";

	}
	
		if($this->use_proxy == true)
		{
			$this->proxy_host = $proxy_host;
			$this->proxy_port = $proxy_port;
		}
		else
		{
			$this->proxy_host = '127.0.0.1';
			$this->proxy_port = '808';
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
		
		
		
	function hash_call($methodName,$nvpStr) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->host.$this->endpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
		if($this->USE_PROXY)
		{
			curl_setopt ($ch, CURLOPT_PROXY, $this->proxy_host.":".$this->proxy_port); 
		}
		$nvpreq="METHOD=".urlencode($methodName)."&version=".urlencode($this->version)."&PWD=".urlencode($this->api_password)."&USER=".urlencode($this->api_username)."&SIGNATURE=".urlencode($this->api_signature).$nvpStr;
		curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpreq);
		$response = curl_exec($ch);
		$nvpResArray=$this->deformatNVP($response);
		$nvpReqArray=$this->deformatNVP($nvpreq);
		$_SESSION['nvpReqArray']=$nvpReqArray;
		if (curl_errno($ch))
		{
			die("CURL send a error during perform operation: ".curl_errno($ch));
		} 
		else 
		{
			curl_close($ch);
		}

	return $nvpResArray;
	}

	function deformatNVP($nvpstr) {

		$intial=0;
		$nvpArray = array();
		while(strlen($nvpstr))
		{
			$keypos= strpos($nvpstr,'='); 
			$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr); 
			$keyval=substr($nvpstr,$intial,$keypos);
			$valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
			$nvpArray[urldecode($keyval)] =urldecode( $valval);
			$nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
		 }
		return $nvpArray;
	}

	function __destruct() {}
}