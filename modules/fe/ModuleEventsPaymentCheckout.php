<?php 

namespace Contao;

if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 *
 * The TYPOlight webCMS is an accessible web content management system that 
 * specializes in accessibility and generates W3C-compliant HTML code. It 
 * provides a wide range of functionality to develop professional websites 
 * including a built-in search engine, form generator, file and user manager, 
 * CSS engine, multi-language support and many more. For more information and 
 * additional TYPOlight applications like the TYPOlight MVC Framework please 
 * visit the project website http://www.typolight.org.
 *
 * PHP version 5
 * @copyright  360fusion  2011
 * @author     Darrell Martin <darrell@360fusion.co.uk>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */



class ModuleEventsPaymentCheckout extends \Module
{

	/**
	 * Template
	 * @var string
	 */


	protected $strTemplate = 'mod_eventpaymentcheckout';
	protected $paypal;
		

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### EVENT PAYMENT CHECKOUT &#169; 360Fusion 2012 ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'typolight/main.php?do=modules&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}
	
		/**
	 * Generate module
	 */
	protected function compile()
	{
		
		global $objPage;
		$GLOBALS['TL_LANGUAGE'] = $objPage->language;
		
		
		 $this->strTemplate = $this->evtregcheckout_template;
		 $this->Template = new \FrontendTemplate($this->strTemplate);
		 
		 
		 
		$thisID =  $this->Input->get('id');
		$event = $this->Database->prepare("SELECT * FROM tl_calendar_events WHERE id=?")->limit(1)->execute($thisID);
		

		// First get the selected payement types:
		$arrPaymentTypes = array();
	//	$objPaymentTypes = $this->Database->execute("SELECT * FROM tl_evtreg_mods WHERE enabled = 1");

		$objPaymentTypes = $this->Database->execute("SELECT * FROM tl_evtreg_mods WHERE id IN (".implode(",",deserialize($event->evtreg_paymentTypes)).")");

		$paymenttypes = array();
			$i = 0;
			while($objPaymentTypes->next())
			{
				$paymenttypes[$i][id] = $objPaymentTypes->id;
				$paymenttypes[$i][name] = $objPaymentTypes->name;
				$paymenttypes[$i][type] = $objPaymentTypes->type;	
				$paymenttypes[$i][label] = $objPaymentTypes->label;
				$paymenttypes[$i][paypal_standard] = $objPaymentTypes->paypal_standard;
				$paymenttypes[$i][payment_type] = $objPaymentTypes->payment_type;
				$paymenttypes[$i][currency_code] = $objPaymentTypes->currency_code;
				$paymenttypes[$i][environment] = $objPaymentTypes->environment;
				$paymenttypes[$i][api_username] = $objPaymentTypes->api_username;
				$paymenttypes[$i][api_password] = $objPaymentTypes->api_password;
				$paymenttypes[$i][api_signature] = $objPaymentTypes->api_signature;
				$paymenttypes[$i][allowedcc_types] = $objPaymentTypes->allowedcc_types;
				if ($paymenttypes[$i][allowedcc_types]) { $num = $i;}
				$i++;
			}
		

		 $configArray = $this->getConfig(1);
		 $success_jumpTo = $configArray->success_jumpTo;
		 $error_jumpTo = $configArray->error_jumpTo;


		
		// Get current event
		$time = time();
		

		
			$this->Template->headline = $event->evtreg_formHeadline;
			$this->Template->id = $event->id;
			$this->Template->title = $event->title;
			$this->Template->bookable_spaces = $event->evtreg_availableSpaces;
			$this->Template->bookable_spaces_remaining = $event->evtreg_availableSpacesRemaining;
			$this->Template->registrationFields = deserialize($event->evtreg_registrationFields);
			
			$formattedAmount =  $event->evtreg_cost;
			$formattedAmount = number_format($formattedAmount, 2);
			$this->Template->amount = $formattedAmount;
			
	    	$this->Template->subdir = "system/modules/calendar_eventbooking/";
		    $this->Template->submit = "Submit Payment";
		//    $this->Template->currency_code = $paymenttypes[$payselectedCount][currency_code];
		    $this->Template->product_desc =  $event->title. ' Event  Attendance';
		    
		    	//  now parse the payment types and subcription intervals to the template...
		$this->Template->paymenttypes = $paymenttypes;
		

			
			$this->Template->eventID = $event->id;
			
		   
		  $amount = $formattedAmount;
		  $product_desc = $event->title.' Event Attendance';
			$recurring = $event->evtreg_cost_recurring;
			$billing_period = $event->evtreg_billing_period;
			$billing_frequency = $event->evtreg_billing_frequency;
	    $success = $this->Environment->base.$this->getPageFromID($success_jumpTo);
		  $error = $this->Environment->base.$this->getPageFromID($error_jumpTo);
		 	
		   
			$cctypes = array();
			$cctypes[0] = deserialize($paymenttypes[$num][allowedcc_types]);


		   for ( $i = 0; $i <= count($cctypes[0]); $i ++) {
		 		$cctypes[1][$i] = $GLOBALS['TL_LANG']['calevntbooking'][''.$cctypes[0][$i].''];
			}


			$this->Template->ccTypes = $cctypes[0];
			$this->Template->evtreg_mandatory = deserialize($event->evtreg_registrationMand);
			
			
			$fieldsArray =  deserialize($event->evtreg_registrationFields);
			$this->Template->submitError = NULL;
			$BSTOffset = +1.00;
			$GMTMySqlString = gmdate("Y-m-d H:i:s", time() + $BSTOffset * 3600);
			
		
  			 if ($this->Input->post('FORM_SUBMIT') == 'checkout') {	
  			 	
  			 	
/*
  			 	
								for ($i=0; $i<count($fieldsArray); $i++)
										{
							// 	echo "f: ".$fieldsArray[$i];
							 	 if ($fieldsArray[$i] == NULL){  $this->Template->submitError =   '<span style="color:red;">Please complete all fields marked (*)</span>'; }
								return;
										}
*/
  			 	
  			// find the array counter id for the posted payment_method
			for ( $z = 0; $z <= count($paymenttypes); $z ++) { 

				if ($paymenttypes[$z][type] == $this->Input->post('payment_method')){
				//	echo "payment type: ".$paymenttypes[$z][type].'<br />';
//					$paymentTypeName = $paymenttypes[$z][type];
					$payselectedCount = $z;
				}
			}
	
/*		
		  $paymodule = $this->Database->prepare("SELECT id FROM tl_evtreg_mods WHERE type=?")
		 	->limit(1)
          ->execute($paymentTypeName);

      	 $payselectedCountSession= $paymodule->id;
*/
		
	//      echo "payselectedCount: ".$payselectedCount;

			 $_SESSION['payselectedCount'] = $payselectedCount;
 			 $_SESSION['payamount'] = $amount;
  			 $_SESSION['payproduct_desc'] =	$product_desc;
  			 
 		
/*	 
	 echo "<br />";
    echo "_SESSION['payselectedCount'] CHECKOUT: ". $_SESSION['payselectedCount'];
	 echo "<br />";

*/
	 



						if ($this->Input->post('CreditCardNumber') != NULL) { $this->Template->CreditCardNumber = ($this->Input->post('CreditCardNumber')); }
						if ($this->Input->post('CVV2') != NULL) { $this->Template-> CVV2 = ($this->Input->post('CVV2')); }
						if ($this->Input->post('issueNumber') != NULL) { $this->Template->issueNumber = ($this->Input->post('issueNumber')); }
					//	if ($this->Input->post('BillingFirstName') != NULL) { $this->Template->BillingFirstName = ($this->Input->post('BillingFirstName')); }
					//	if ($this->Input->post('BillingLastName') != NULL) { $this->Template->BillingLastName = ($this->Input->post('BillingLastName')); }
						if ($this->Input->post('BillingStreet1') != NULL) { $this->Template->BillingStreet1 = ($this->Input->post('BillingStreet1')); }
						if ($this->Input->post('BillingStreet2') != NULL) { $this->Template->BillingStreet2 = ($this->Input->post('BillingStreet2')); }		
						if ($this->Input->post('BillingCityName') != NULL) { $this->Template->BillingCityName = ($this->Input->post('BillingCityName')); }
						if ($this->Input->post('BillingPostalCode') != NULL) { $this->Template->BillingPostalCode = ($this->Input->post('BillingPostalCode')); }	
						if ($this->Input->post('BillingPhone') != NULL) { $this->Template->BillingPhone = ($this->Input->post('BillingPhone')); }
  			 	
  			 	
  			 				for ($i=0; $i<count($fieldsArray); $i=($i+1))
										{
							 		      $postbackfields[''.$fieldsArray[$i].'']  = $this->Input->post($fieldsArray[$i]);
							 		      if ($postbackfields[''.$fieldsArray[$i].''] == NULL && $fieldsArray[$i] != 'user_photo'){  $this->Template->submitError =   '<span style="color:red;">Please complete all fields marked (*)</span>'; }
										}
										
										
										
		
							// hidden fields
							$postbackfields['eventID'] = $event->id;
							$postbackfields['date'] = $GMTMySqlString;
							$postbackfields['eventName'] = stripslashes($event->title);
							$postbackfields['tstamp'] =  time();
							
							// save the photo
							if ($_FILES['user_photo']['error'] == 0) { $postbackfields['user_photo'] = $this->savePhoto($event->id,stripslashes($event->title)); }	
								




							$_SESSION['postbackfields'] = $postbackfields;
						//	print_r(	$postbackfields);
						
								if ($this->Template->submitError != NULL){
									$this->Template->postbackfields = $postbackfields;
									return;
								}
		
		
					switch ($this->Input->post('payment_method')) {
			

			case "paypal":
			  // Paypal Website Payment Standard		
			  
				$businessAccount = $paymenttypes[$payselectedCount][paypal_standard];
		    	$currency_code = 	$paymenttypes[$payselectedCount][currency_code];
		    	$environment = $paymenttypes[$payselectedCount][environment];


	   	$this->log('User selected Paypal Standard button, event id:  '.$_SESSION['postbackfields']['eventID'].', Event Name: '.$_SESSION['postbackfields']['eventName'].', Firstname: '.$_SESSION['postbackfields']['firstname'].', Lastname: '.$_SESSION['postbackfields']['lastname'].', Email: '.$_SESSION['postbackfields']['email'].', ', 'ModuleEventsPaymentCheckout()', TL_GENERAL);



				$this->paypal = new PayPal_ipn($businessAccount, $success, $error, $success, $product_desc, $currency_code, $amount, $environment,$recurring,$billing_period,$billing_frequency,$subscriptionID,$custom,$subscriptionsInitialPayment);
				$this->Template->paypal = $this->paypal;
			    $paypal = $this->paypal;
				$return = ($paypal->doPaymentStandard());
			 break;
		

			case "paypalexpress":
			  // Paypal Express 
			  
			   	$this->log('User selected Paypal Express button, event id:  '.$_SESSION['postbackfields']['eventID'].', Event Name: '.$_SESSION['postbackfields']['eventName'].', Firstname: '.$_SESSION['postbackfields']['firstname'].', Lastname: '.$_SESSION['postbackfields']['lastname'].', Email: '.$_SESSION['postbackfields']['email'].'', 'ModuleEventsPaymentCheckout()', TL_GENERAL);

			   	
			  	$api_username = $paymenttypes[$payselectedCount][api_username];
			  	$api_password = $paymenttypes[$payselectedCount][api_password];
			  	$api_signature = $paymenttypes[$payselectedCount][api_signature];
		    	$payment_type = 	$paymenttypes[$payselectedCount][payment_type];
		    	$currency_code = 	$paymenttypes[$payselectedCount][currency_code];
		    	$environment = $paymenttypes[$payselectedCount][environment];
		    	


				 $_SESSION['currency_code'] =	$currency_code;

			   $this->paypal = new \PayPal_standard($api_username, $api_password, $api_signature, $success, $error, $payment_type, $currency_code, $environment,$recurring,$billing_period,$billing_frequency,$subscriptionID,$custom,$initialpayment);
			   $this->Template->paypal = $this->paypal;
			   $paypal = $this->paypal;
			   $return = ($paypal->doExpressCheckout($amount, $product_desc, $currency_code));
			 break;
			 
			case "paypalpro":
			  // Paypal Payments Pro

			  	$this->log('User selected Paypal Pro button, Event ID:  '.$_SESSION['postbackfields']['eventID'].', Event Name: '.$_SESSION['postbackfields']['eventName'].', Firstname: '.$_SESSION['postbackfields']['firstname'].', Lastname: '.$_SESSION['postbackfields']['lastname'].', Email: '.$_SESSION['postbackfields']['email'].'', 'ModuleEventsPaymentCheckout()', TL_GENERAL);
			   
			  	 
			  $api_username = $paymenttypes[$payselectedCount][api_username];
			  $api_password = $paymenttypes[$payselectedCount][api_password];
			  $api_signature = $paymenttypes[$payselectedCount][api_signature];
			  $environment = $paymenttypes[$payselectedCount][environment];
			  $currencyCode = 	$paymenttypes[$payselectedCount][currency_code];
			  $_SESSION['CUSTOM_SUB'] = $subscriptions[$subselectedCount][id];
			  
				$firstName = $this->Input->post('BillingFirstName');
				$creditCardType = $this->Input->post('CreditCardType');
				$creditCardNumber = $this->Input->post('CreditCardNumber');
				$expDateMonth  = $this->Input->post('ExpMonth');
				$issueNumber  = $this->Input->post('issueNumber');
				$expDateYear = $this->Input->post('ExpYear');
				$cvv2Number = $this->Input->post('CVV2');
				$address1 = $this->Input->post('BillingStreet1');
				$address2 = $this->Input->post('BillingStreet2');
				$city = $this->Input->post('BillingCityName');
				$state = $this->Input->post('BillingStateOrProvince');
				$zip = $this->Input->post('BillingPostalCode');
				$amount = $amount;
				$countryCode = $this->Input->post('BillingCountry');
				$paymentAction = $this->Input->post('PaymentAction');
				
				// quick check of form
				if ($this->Input->post('payment_method') == NULL ||
				$firstName == NULL || 
				$creditCardNumber == NULL ||
				$cvv2Number == NULL ||
				$address1 == NULL ||
		//		$address2 == NULL ||
				$city == NULL ||
				$state == NULL ||
				$zip == NULL) {
				$this->Template->submitError = "Please fill in all credit card and billing fields marked *";
				$this->Template->postbackfields = $postbackfields;
					return;
				}
				

					if ($creditCardType == "Maestro" && $issueNumber == NULL) { 
							$this->Template->submitError = "Please enter an issue number";
							$this->Template->postbackfields = $postbackfields;
							return;
					}
					
	/*				
				 if($recurring == 1) // For Recurring
				{
					$profileStartDate = urlencode(date('Y-m-d h:i:s'));
					$billingPeriod = urlencode($billing_period);// or "Day", "Week", "SemiMonth", "Year"
					$billingFreq = urlencode($billing_frequency);// combination of this and billingPeriod must be at most a year
					if ($subscriptionsInitialPayment >0){ $initAmt = $subscriptionsInitialPayment+$amount; }
					$failedInitAmtAction = urlencode("ContinueOnFailure");
					$desc = urlencode("Recurring $".$amount);
					$autoBillAmt = urlencode("AddToNextBilling");
					$profileReference = urlencode("Anonymous");
					$methodToCall = 'CreateRecurringPaymentsProfile';
					$nvpRecurring ='&BILLINGPERIOD='.$billingPeriod.'&BILLINGFREQUENCY='.$billingFreq.'&PROFILESTARTDATE='.$profileStartDate.'&INITAMT='.$initAmt.'&FAILEDINITAMTACTION='.$failedInitAmtAction.'&DESC='.$desc.'&AUTOBILLAMT='.$autoBillAmt.'&PROFILEREFERENCE='.$profileReference;
				}
				else
				{
*/		
			//		$nvpRecurring = '';
					$methodToCall = 'doDirectPayment';
//				}


			
				
				
			// PayPal records this IP addresses as a means to detect possible fraud. (Required) 
				if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
				    $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
				} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				    $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
				} else {
				    $ipAddress = $_SERVER['REMOTE_ADDR'];
				}
						

			 $nvpstr=
			  '&PAYMENTACTION='.$paymentAction
			 .'&IPADDRESS='.$ipAddress
			 .'&AMT='.number_format((float)$amount , 2, '.', '')
		 	 .'&ITEMAMT='.number_format((float)$amount, 2, '.', '')
			 .'&CREDITCARDTYPE='.$creditCardType
			 .'&ACCT='.$creditCardNumber
			 .'&EXPDATE='.$expDateMonth.$expDateYear
			 .'&CVV2='.$cvv2Number
			 .'&FIRSTNAME='. urlencode($firstName)
			 .'&LASTNAME=&STREET='. urlencode($address1.$address2)
			 .'&CITY='.urlencode($city)
			 .'&STATE='.urlencode($state)
			 .'&ZIP='.urlencode($zip)
			 .'&COUNTRYCODE='.$countryCode
			 .'&CURRENCYCODE='.$currencyCode;
			 
				$nvpstr .= '&RETURNFMFDETAILS=1';
				
				$nvpstr .= '&L_NAME0='. urlencode($product_desc);
				$nvpstr .= '&L_NUMBER0='.$this->Template->id;
			  $nvpstr .= '&L_AMT0='.$amount;  
				$nvpstr .= '&L_QTY0=1';  
				
			  if ($creditCardType == "Maestro") { $nvpstr .= '&ISSUENUMBER='.$issueNumber;}
			  
				 
			    $this->paypal = new PayPal_pro($api_username, $api_password, $api_signature, '', '', $environment, false);
				 $this->Template->paypal = $this->paypal;
				 $paypal = $this->paypal;
				 
				 $resArray = ($paypal->hash_call($methodToCall, $nvpstr));
				
				
			 $this->log('Paypal Pro response ACK: '.$resArray['ACK'].', transactionid: '.$resArray['TRANSACTIONID'].', Event ID:  '.$_SESSION['postbackfields']['eventID'].', Event Name: '.$_SESSION['postbackfields']['eventName'].', transaction id: '.$resArray['TRANSACTIONID'].', Firstname: '.$_SESSION['postbackfields']['firstname'].', Lastname: '.$_SESSION['postbackfields']['lastname'].', Email: '.$_SESSION['postbackfields']['email'].', Phone: '.$_SESSION['postbackfields']['phone'].'', 'ModuleEventsPaymentCheckout()', TL_GENERAL);
			 	
//			 if (($resArray['ACK'] == 'Success') || ($resArray['ACK'] == 'SuccessWithWarning'))   {
			 if ($resArray['ACK'] == 'Success')   {
							 /*
							 example return:
									[TIMESTAMP] => 2010 - 03 - 11T11:17:08Z 
									[CORRELATIONID] => 3342a4209d7d3 
									[ACK] => Success 
									[VERSION] => 51.0 
									[BUILD] => 1224319 
									[AMT] => 5.00 
									[CURRENCYCODE] => GBP 
									[AVSCODE] => X 
									[CVV2MATCH] => M 
									[TRANSACTIONID] => 1W617382VT289451A 
							 */
			//	 $_SESSION['nvpReqArray']['TRANSACTIONID'] = $resArray['TRANSACTIONID'];
			//	 $this->jumpToOrReload($success_jumpTo.'?tid='.$resArray['TRANSACTIONID']);
				 
				$completed_pageID = $success_jumpTo;
	   		    $redirect_to_completed_page = $this->Environment->base.$this->getPageFromID($completed_pageID).'?tid='.$resArray['TRANSACTIONID'];
				 header('Location: '.$redirect_to_completed_page);	
				 
			} else  {
				 $_SESSION['nvpReqArray']['L_LONGMESSAGE0'] = $resArray['L_LONGMESSAGE0'];
				 $_SESSION['nvpReqArray']['SHORTMESSAGE0'] = $resArray['SHORTMESSAGE0'];
				 $this->log('Paypal Pro response ACK Failure: '.$resArray['L_LONGMESSAGE0'].', '.$resArray['SHORTMESSAGE0'].', event id:  '.$_SESSION['postbackfields']['eventID'].', Firstname: '.$_SESSION['postbackfields']['firstname'].', Lastname: '.$_SESSION['postbackfields']['lastname'].', Email: '.$_SESSION['postbackfields']['email'].', Phone: '.$_SESSION['postbackfields']['phone'].'', 'ModuleEventsPaymentCheckout()', TL_GENERAL);
		     $completed_pageID = $error_jumpTo;
		     $_SESSION['nvpReqArray']['BACK'] = \Environment::get('base') . \Environment::get('request');
	   		 $redirect_to_completed_page = $this->Environment->base.$this->getPageFromID($completed_pageID);
				 header('Location: '.$redirect_to_completed_page);	
				 
			}

			 break;
			 
			default:
			   // null
			} 
		
		
				if ($this->Input->post('payment_method') == NULL) {
					 $this->Template->submitError =   '<span style="color:red;">Please select a payment method</span>';
				 }
	
		
							if ($this->Template->submitError != NULL){
								$this->Template->postbackfields = $postbackfields;
								return;
							}
							
							
  			 	return;
  			}
  			
  			
  			

		return;
	}
	
	
	
		
		private function getConfig($configID){
			
				$configArray = $this->Database->prepare("SELECT * FROM tl_evtreg_config WHERE id=?")->limit(1)->execute($configID);

								if (!$configArray->numRows) {
									$this->log('Config ID '.$configID.' not found', 'ModuleEventsCheckout()', TL_ERROR);
									return;
								}

						return $configArray;
		}
		
		
	
		private function getPageFromID($intId){
				global $objPage;
		
				if (strlen($intId) && $intId != $objPage->id)
				{
					$objNextPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
												  ->limit(1)
												  ->execute($intId);
		
					if ($objNextPage->numRows)
					{
						return ($this->getUrl($objNextPage->fetchAssoc()));
					}
				}
		
				$this->reload();
				//return;
		}
	
		private function getUrl($arrRow, $strParams=''){
			$strUrl = ($GLOBALS['TL_CONFIG']['rewriteURL'] ? '' : 'index.php/') . (strlen($arrRow['alias']) ? $arrRow['alias'] : $arrRow['id']) . $strParams . $GLOBALS['TL_CONFIG']['urlSuffix'];
	
			if ($GLOBALS['TL_CONFIG']['disableAlias'])
			{
				$strRequest = '';
	
				if ($strParams)
				{
					$arrChunks = explode('/', preg_replace('@^/@', '', $strParams));
	
					for ($i=0; $i<count($arrChunks); $i=($i+2))
					{
						$strRequest .= sprintf('&%s=%s', $arrChunks[$i], $arrChunks[($i+1)]);
					}
				}
	
				$strUrl = 'index.php?id=' . $arrRow['id'] . $strRequest;
			}
	
			// HOOK: add custom logic
			if (isset($GLOBALS['TL_HOOKS']['generateFrontendUrl']) && is_array($GLOBALS['TL_HOOKS']['generateFrontendUrl']))
			{
				foreach ($GLOBALS['TL_HOOKS']['generateFrontendUrl'] as $callback)
				{
					$this->import($callback[0]);
					$strUrl = $this->$callback[0]->$callback[1]($arrRow, $strParams, $strUrl);
				}
			}
	
			return $strUrl;
		}
		
		

	
	


	private function savePhoto($thisID, $eventTitle){
	
		$this->extensions = "jpg,jpeg,gif,png";
		
		$file = $_FILES['user_photo'];
		$maxlength_kb = $this->getReadableSize($this->maxlength);

		// Add the parsed id to the filename
		$file['name'] = $thisID.'_'.$file['name'];

		// Romanize the filename
		$file['name'] = utf8_romanize($file['name']);
		

		// File was not uploaded
		if (!is_uploaded_file($file['tmp_name']))
		{
			if (in_array($file['error'], array(1, 2)))
			{
				$this->log('File "'.$file['name'].'" exceeds the maximum file size of '.$maxlength_kb, 'ModuleEventsPaymentCheckout validate()', TL_ERROR);
			}

			if ($file['error'] == 3)
			{
				$this->log('File "'.$file['name'].'" was only partially uploaded', 'ModuleEventsPaymentCheckout validate()', TL_ERROR);
			}

			unset($_FILES['user_photo']);
			return;
		}

		// File is too big
		if ($this->maxlength > 0 && $file['size'] > $this->maxlength)
		{
			$this->log('File "'.$file['name'].'" exceeds the maximum file size of '.$maxlength_kb, 'ModuleEventsPaymentCheckout validate()', TL_ERROR);

			unset($_FILES['user_photo']);
			return;
		}

		$pathinfo = pathinfo($file['name']);
		$uploadTypes = trimsplit(',', $this->extensions);

		// File type is not allowed
		if (!in_array(strtolower($pathinfo['extension']), $uploadTypes))
		{
			$this->log('File type "'.$pathinfo['extension'].'" is not allowed to be uploaded ('.$file['name'].')', 'ModuleEventsPaymentCheckout validate()', TL_ERROR);

			unset($_FILES['user_photo']);
			return;
		}

		if (($arrImageSize = @getimagesize($file['tmp_name'])) != false)
		{
			// Image exceeds maximum image width
			if ($arrImageSize[0] > $GLOBALS['TL_CONFIG']['imageWidth'])
			{
				$this->log('File "'.$file['name'].'" exceeds the maximum image width of '.$GLOBALS['TL_CONFIG']['imageWidth'].' pixels', 'ModuleEventsPaymentCheckout validate()', TL_ERROR);

				unset($_FILES['user_photo']);
				return;
			}

			// Image exceeds maximum image height
			if ($arrImageSize[1] > $GLOBALS['TL_CONFIG']['imageHeight'])
			{
				$this->log('File "'.$file['name'].'" exceeds the maximum image height of '.$GLOBALS['TL_CONFIG']['imageHeight'].' pixels', 'ModuleEventsPaymentCheckout validate()', TL_ERROR);

				unset($_FILES['user_photo']);
				return;
			}
		}

		// Store file in the session and optionally on the server
		if ($_FILES['user_photo'])
		{
			$_SESSION['FILES']['user_photo'] = $_FILES['user_photo'];
			$this->log('File "'.$file['name'].'" uploaded successfully', 'ModuleEventsPaymentCheckout validate()', TL_FILES);



			
				$strUploadFolder = 'tl_files/files/images/photos/'.$eventTitle;


			// create event dir if it does not exist
			if (!is_dir(TL_ROOT . '/' . $strUploadFolder)) {
					mkdir(TL_ROOT . '/' . $strUploadFolder, 0700);
			}
				
				// Store the file if the upload folder exists
				if (strlen($strUploadFolder) && is_dir(TL_ROOT . '/' . $strUploadFolder))
				{
					$this->import('Files');

					// Do not overwrite existing files
					if ($this->doNotOverwrite && file_exists(TL_ROOT . '/' . $strUploadFolder . '/' . $file['name']))
					{
						$offset = 1;
						$pathinfo = pathinfo($file['name']);
						$name = $pathinfo['filename'];

						$arrAll = scan(TL_ROOT . '/' . $strUploadFolder);
						$arrFiles = preg_grep('/^' . preg_quote($name, '/') . '.*\.' . preg_quote($pathinfo['extension'], '/') . '/', $arrAll);

						foreach ($arrFiles as $strFile)
						{
							if (preg_match('/__[0-9]+\.' . preg_quote($pathinfo['extension'], '/') . '$/', $strFile))
							{
								$strFile = str_replace('.' . $pathinfo['extension'], '', $strFile);
								$intValue = intval(substr($strFile, (strrpos($strFile, '_') + 1)));

								$offset = max($offset, $intValue);
							}
						}

						$file['name'] = str_replace($name, $name . '__' . ++$offset, $file['name']);
					}

					$this->Files->move_uploaded_file($file['tmp_name'], $strUploadFolder . '/' . $file['name']);
					$this->Files->chmod($strUploadFolder . '/' . $file['name'], 0644);

					$_SESSION['FILES']['user_photo'] = array
					(
						'name' => $file['name'],
						'type' => $file['type'],
						'tmp_name' => TL_ROOT . '/' . $strUploadFolder . '/' .$file['name'],
						'error' => $file['error'],
						'size' => $file['size'],
						'uploaded' => true
					);

					$this->log('File "'.$file['name'].'" has been moved to "'.$strUploadFolder.'"', 'ModuleEventsPaymentCheckout validate()', TL_FILES);

				}

		}
			unset($_FILES['user_photo']);
		  return $strUploadFolder . '/' . $file['name'];
		
	}
			
	
}