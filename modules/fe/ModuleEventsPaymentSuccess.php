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
 * @copyright  360fusion  2012
 * @author     Darrell Martin <darrell@360fusion.co.uk>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


class ModuleEventsPaymentSuccess extends \Module
{
	

	protected $strTemplate = 'mod_eventpaymentsuccess';
	protected $paypal;


	public function generate() {
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### Event Payment Success &#169; 360Fusion 2012 ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'typolight/main.php?do=modules&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}
	
	
	protected function compile() {
		global $objPage;
		
		$GLOBALS['TL_LANGUAGE'] = $objPage-> language; 
		

		 $configArray = $this->getConfig(1);
		 
		 
		 
		 $this->strTemplate = $this->evtregsuccess_template;
		 $this->Template = new \FrontendTemplate($this->strTemplate);
		 
		 
	     $emailAdmin = $configArray->adminEmail;
	     $emailRegVerification = $configArray->emailRegVerification;
	     $emailAdminVerification = $configArray->emailAdminVerification;
	     $emailRegTemplate = $configArray->emailRegTemplate;
	     $emailAdminTemplate = $configArray->emailAdminTemplate;
	    
	    
	    
	    		/* ========== Get Data from Database ========== */
		
		$arrPaymentTypes = array();
		$objPaymentTypes = $this->Database->execute("SELECT * FROM tl_evtreg_mods WHERE enabled = 1");
		$paymenttypes = array();

/*		
	 print_r($objPaymentTypes);
	 echo "<br />";
*/		
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
				$paymenttypes[$i][identity_token] = $objPaymentTypes->identity_token;
				
//			 echo "<br />";
//		 print_r($paymenttypes[$i]);
				 
				$i++;
			}


/*
 echo "<br />";
 echo "_SESSION['payselectedCount'] SUCCESS: ". $_SESSION['payselectedCount'];
*/
 	$v =  $_SESSION['payselectedCount'];
 	
 	
// $v = 1;
 
 /*
 echo "v: ". $v;
 echo "<br />";
   echo  'paymenttypes[$v][name]: '.$paymenttypes[$v][name].'<br/>';
   echo  'paymenttypes[$v][environment]: '.$paymenttypes[$v][environment].'<br/>';
   echo  'paymenttypes[$v][payment_type]: '.$paymenttypes[$v][payment_type].'<br/>';
   echo  'paymenttypes[$v][currency_code]: '.$paymenttypes[$v][currency_code].'<br/>';
  echo  'paymenttypes[$v][api_username]: '.$paymenttypes[$v][api_username].'<br/>';
  echo  'paymenttypes[$v][api_password]: '.$paymenttypes[$v][api_password].'<br/>';
  echo  'paymenttypes[$v][api_signature]: '.$paymenttypes[$v][api_signature].'<br/>';
  echo  'paymenttypes[$v][identity_token]: '.$paymenttypes[$v][identity_token].'<br/>';
  */

	       $api_username = $paymenttypes[$v][api_username];
		   $api_password = $paymenttypes[$v][api_password];
		   $api_signature = $paymenttypes[$v][api_signature];
		   $identity_token = $paymenttypes[$v][identity_token];
		   	$environment = $paymenttypes[$v][environment];
		   	$payment_type = $paymenttypes[$v][payment_type];
			$currency_code =	$paymenttypes[$v][currency_code];

	    
	    $token = $_GET['token'];
	    $PayerID = $_GET['PayerID'];
	    
/*	    
	    echo "<br />api_username: ".$api_username;
    exit;
	*/    



		$this->paypal = new \PayPal_standard($api_username, $api_password, $api_signature,$return_url,$cancel_url,$payment_type,$currency_code,$environment,$product_desc,$recurring,$billing_period,$billing_frequency,$subscriptionID,$custom);
		$this->Template->paypal = $this->paypal;
		$paypal = $this->paypal;
		$transactionDetails = array();
		

	//	echo $token;  
//		print_r ($_GET);
		
		switch ($_GET['action']) {
		
	    case 'success':      // Order was successful...
		$thisStatus = "standard";
         break;
	  
		case 'ipn':
		$this->paypal = new \PayPal_ipn();
		$this->Template->paypal = $this->paypal;
		$paypal = $this->paypal;
		
		  if ($paypal->validate_ipn()) {
		  // Called but not used other than storing the logfile since this is called by paypal and there is no session data to update the validation DB.
    		$transactionDetails['ACK'] = "Success";
			$transactionDetails['FIRSTNAME'] = $paypal->ipn_data['first_name'];
			$transactionDetails['LASTNAME'] = $paypal->ipn_data['last_name'];
			$transactionDetails['RECEIVEREMAIL'] = $paypal->ipn_data['receiver_email'];
			$transactionDetails['TRANSACTIONTYPE'] = $paypal->ipn_data['payment_type'];
			$transactionDetails['TRANSACTIONID'] = $paypal->ipn_data['txn_id'];
			$transactionDetails['AMT'] = $paypal->ipn_data['mc_gross'];
			$transactionDetails['CURRENCYCODE'] = $paypal->ipn_data['mc_currency'];
			$transactionDetails['PAYMENTSTATUS'] = $paypal->ipn_data['payment_status'];
			$transactionDetails['PAYMENTTYPE'] = $paypal->ipn_data['payment_type'];
			$transactionDetails['SUBSCRIPTIONID'] = $paypal->ipn_data['item_number'];
		  } 
			header('HTTP/1.1 200 OK');
			exit;
	     break;
		}
		
	
	if (!$token) 
	{
		if ($_GET['action'] == "success") {
			$transactionDetails['FIRSTNAME'] = $_POST['first_name'];
			$transactionDetails['LASTNAME'] = $_POST['last_name'];
			$transactionDetails['RECEIVEREMAIL'] = $_POST['receiver_email'];
			$transactionDetails['TRANSACTIONTYPE'] = $_POST['payment_type'];
			$transactionDetails['TRANSACTIONID'] = $_POST['txn_id'];
			$transactionDetails['AMT'] = $_POST['mc_gross'];
			$transactionDetails['CURRENCYCODE'] = $_POST['mc_currency'];
			$transactionDetails['PAYMENTSTATUS'] = $_POST['payment_status'];
			$transactionDetails['PAYMENTTYPE'] = $_POST['payment_type'];
			$transactionDetails['SUBSCRIPTIONID'] = $_POST['item_number'];
			$transactionDetails['CUSTOM'] = $_POST['custom'];
			$transactionDetails['PAYERID'] = $_POST['payer_id'];
			
					 // IPN TX
		 if ($_GET['tx']) {
		    $tx = $_GET['tx']; // 64490455BS507251M
		    $st= $_GET['st'];  // Completed
	 		$amt= $_GET['amt'];  // 10.00
	 		$cc= $_GET['cc'];  // GBP
	 		$item_number= $_GET['item_number'];  // 3
	 	/*	
	 		echo 'tx: ', $tx.'<br/>';
	 		echo 'st: ', $st.'<br/>';
	 		echo 'amt: ', $amt.'<br/>';
	 		echo 'cc: ', $cc.'<br/>';
	 		echo 'item_number: ', $item_number.'<br/>';
	 	*/
	 	
//	 	echo '<br/>identity_token: '.$identity_token.'<br/>';
	 	
	 		$transactionDetails = $paypal->getPDTCheckoutDetails($tx,$identity_token);  
/*	 		
	 		echo '<br/>transactionDetails: ';
	 		print_r($transactionDetails);
*/	 		
 		}
 		
		}
		
		else if ($_GET['action'] == "ipn") {
			$thisStatus = "standard_ipn";
		}
		
		else { 
			$tid = $_GET['tid'];
		//	$transactionDetails = $paypal->getTransactionDetails($_SESSION['nvpReqArray']['TRANSACTIONID']);  
		   $transactionDetails = $paypal->getTransactionDetails($tid);  
				$thisStatus = "websitePaymentsPro";
			}
	}	
		
		
	if ($token) 
	{
		
		$checkoutDetails = $paypal->getCheckoutDetails($token);
		
	/*
		example $checkoutDetails return vars:
		
			[TOKEN] => EC-0V899760LW573413E 
			[TIMESTAMP] => 2010-03-10T15:22:42Z 
			[CORRELATIONID] => 5767d5ac74245 
			[ACK] => Success 
			[VERSION] => 52.0 
			[BUILD] => 1212010 
			[EMAIL] => test_1267540489_per@360fusion.co.uk 
			[PAYERID] => KGPXNEGANPTUQ 
			[PAYERSTATUS] => verified 
			[FIRSTNAME] => Test 
			[LASTNAME] => User 
			[COUNTRYCODE] => GB 
			[CUSTOM] => 5|GBP| 
		*/
		
		/*
					echo '<br/>checkoutDetails: '.'<br/>';
					print_r($checkoutDetails);
		*/			
		
			 if ($checkoutDetails['BILLINGAGREEMENTACCEPTEDSTATUS'] == 1) {
			 	// this is a paypal express recurring payment
			 	// Calls CreateRecurringPaymentsProfile one time for each recurring payment item included in the order.
			 	
						$_SESSION['PPTOKEN'] = $token;
						$_SESSION['PPPAYERID'] = $PayerID;
						$return = ($paypal->callCreateRecurringPaymentsProfile($token, $subscriptions[$p][amount],$paymenttypes[$v][currency_code], $subscriptions[$p][billing_period], $subscriptions[$p][billing_frequency], $subscriptions[$p][product_desc]));
				//		print_r($return);		
				//		echo '<br/>';
							if (($return['ACK'] == 'Success') || ($return['ACK'] == 'SuccessWithWarning'))   {		
								$paymentDetails = $paypal->doExpPayment($subscriptions[$p][amount],$paymenttypes[$v][currency_code],$_SESSION['PPTOKEN'],$_SESSION['PPPAYERID']);
						//		print_r($paymentDetails);		
						//		echo '<br/>';
								$transactionDetails = $paypal->getTransactionDetails($paymentDetails['TRANSACTIONID']);
							   $thisStatus = "express";
						//		print_r($transactionDetails);		
						   } 
				} 
				
			 else {
			 	
			 	
					$paymentDetails = $paypal->doPayment($_SESSION['payamount'],$_SESSION['currency_code']);

/*
					echo '<br/>paymentDetails: '.'<br/>';
					print_r($paymentDetails);
					
*/					
					
					
					/*
					example $paymentDetails return vars:
					
						[TOKEN] => EC-0V899760LW573413E 
						[TIMESTAMP] => 2010-03-10T15:20:28Z 
						[CORRELATIONID] => 95e5397394529 
						[ACK] => Success 
						[VERSION] => 52.0 
						[BUILD] => 1212010 
						[TRANSACTIONID] => 65B43104VJ335994F 
						[TRANSACTIONTYPE] => expresscheckout 
						[PAYMENTTYPE] => instant 				
						[ORDERTIME] => 2010-03-10T15:20:27Z 
						[AMT] => 5.00 
						[FEEAMT] => 0.37 
						[TAXAMT] => 0.00 
						[CURRENCYCODE] => GBP 
						[PAYMENTSTATUS] => Completed 
						[PENDINGREASON] => None 
						[REASONCODE] => None 
					*/
			
					$transactionDetails = $paypal->getTransactionDetails($paymentDetails['TRANSACTIONID']);
					$thisStatus = "express";
				}
		
	}
	
		/*	
		
		example $transactionDetails return vars:
		
		?[RECEIVERBUSINESS] => test_1267533784_biz@360fusion.co.uk 
		 [RECEIVEREMAIL] => test_1267533784_biz@360fusion.co.uk 
		 [RECEIVERID] => RP9VBHP4R6JRE 
		 [PAYERID] => NNHSGCY9DPALJ 
		 [PAYERSTATUS] => verified 
		 [COUNTRYCODE] => GB 
		 [ADDRESSOWNER] => PayPal 
		 [ADDRESSSTATUS] => None 
		 [SALESTAX] => 0.00 
		 [TIMESTAMP] => 2010 - 03 - 11T13:17:00Z 
		 [CORRELATIONID] => a0284daf8658f 
		 [ACK] => Success 
		 [VERSION] => 52.0 
		 [BUILD] => 1214948 
		 [FIRSTNAME] => dfdfdfdf 
		 [LASTNAME] => erere 
		 [TRANSACTIONID] => 29P284612D050842S 
		 [RECEIPTID] => 0263 - 7875 - 9661 - 5520 
		 [TRANSACTIONTYPE] => webaccept 
		 [PAYMENTTYPE] => instant 
		 [ORDERTIME] => 2010 - 03 - 11T13:16:59Z 
		 [AMT] => 5.00 
		 [FEEAMT] => 0.37 
		 [TAXAMT] => 0.00 
		 [CURRENCYCODE] => GBP 
		 [PAYMENTSTATUS] => Completed 
		 [PENDINGREASON] => None 
		 [REASONCODE] => None 
		 [L_QTY0] => 1 
		 [L_TAXAMT0] => 0.00 
		 [L_CURRENCYCODE0] => GBP ) 
		
	*/
	

	 	 $this->Template->transactionDetails = $transactionDetails;

		 $this->Template->status = $thisStatus;
		 $this->Template->userFirstName = $transactionDetails['FIRSTNAME']; 
		 $this->Template->userLastName = $transactionDetails['LASTNAME']; 
		 $this->Template->userEmail = $transactionDetails['RECEIVEREMAIL']; 
	
		 $this->Template->transactionType = "PayPal ".$transactionDetails['TRANSACTIONTYPE']; 
		 $this->Template->paymentType = $transactionDetails['PAYMENTTYPE'];
		 $this->Template->orderTime =  gmdate("l dS \of F Y\, h:i:s A T", time());

		 $this->Template->amount = $transactionDetails['AMT'];
		 $this->Template->currencyCode = $transactionDetails['CURRENCYCODE'];
		 $this->Template->paymentStatus = $transactionDetails['PAYMENTSTATUS'];
		 $this->Template->pendingReason = $transactionDetails['PENDINGREASON'];
		 
		 $this->Template->orderNum = $transactionDetails['TRANSACTIONID'];
		 $this->Template->ItemLabel = "";
		 $this->Template->ItemDescription = "";
		 
		
		 
		if ($transactionDetails['PAYMENTSTATUS'] == "Completed" || $thisStatus == "standard") {
			//  create the event booking
			 $postbackfields = $_SESSION['postbackfields'];
			 
			 $this->Template-> userBooking = $postbackfields['eventName'];
			 $this->Template-> userBookedDate = $postbackfields['date'];
			 $postbackfields['TRANSACTIONID'] = $transactionDetails['TRANSACTIONID'];
			
			
          $check = $this->Database->prepare("SELECT id FROM tl_event_registration WHERE transactionid=?")->limit(1)->execute($transactionDetails['TRANSACTIONID']);
          
          	if (!$check->numRows) {

			 		$newBooking = $this->Database->prepare("INSERT INTO tl_event_registration %s")->set($postbackfields)->execute();

					$this->log('New event booking payment submitted ('.$thisStatus.', firstname: '.$transactionDetails['FIRSTNAME'].', lastname: '.$transactionDetails['LASTNAME'].', transaction id:  '.$transactionDetails['TRANSACTIONID'].', payment status: '.$transactionDetails['PAYMENTSTATUS'].'): event id:  '.$_SESSION['postbackfields']['eventID'].'', 'ModuleEventsPaymentSuccess()', TL_GENERAL);
						//	$redirect_to_completed_page = $this->Environment->base.$this->getPageFromID($_SESSION['evtreg_success_jumpTo']);
						
						
		 			 $event = $this->Database->prepare("SELECT * FROM tl_calendar_events WHERE id=?")->limit(1)->execute($_SESSION['postbackfields']['eventID']);
					 // count the total spaces taken in this event in tl_event_registration so we can update the evtreg_availableSpacesRemaining (evtreg_availableSpaces - count in tl_event_registration)
					 $thisRegSpaces = $this->Database->prepare("SELECT id FROM tl_event_registration WHERE eventID=?")->execute($_SESSION['postbackfields']['eventID']);
					 $event->evtreg_availableSpacesRemaining = ($event->evtreg_availableSpaces - $thisRegSpaces->numRows);
					 $updateEvent = $this->Database->prepare("UPDATE tl_calendar_events SET evtreg_availableSpacesRemaining=?  WHERE id=?")->execute($event->evtreg_availableSpacesRemaining, $_SESSION['postbackfields']['eventID']);
					 $this->log('Updated number of spaces remaining on event id eventID: '.$_SESSION['postbackfields']['eventID'].' , spaces remaining: '.$event->evtreg_availableSpacesRemaining.'', 'ModuleEventsPaymentSuccess() - paid event', TL_GENERAL);
							 
							
							/* 
							 // now decrement the spaces remaining by 1
							 $newSpacesRemaining = $event->evtreg_availableSpacesRemaining -1;

							 	$this->Database->prepare("UPDATE tl_calendar_events SET evtreg_availableSpacesRemaining=? WHERE id=?")
								->execute($newSpacesRemaining,  $_SESSION['postbackfields']['eventID']);
								$this->log('Number of spaces remaining on event id '.$_SESSION['postbackfields']['eventID'].' : '.$newSpacesRemaining.'', 'ModuleEventsPaymentSuccess()', TL_GENERAL);
							*/	
								
								
									$arrData = $postbackfields;


									if ($emailRegVerification == 1){
										// email to the user	
										$this->sendMail($emailRegTemplate, $arrData['email'], $GLOBALS['TL_LANGUAGE'], $arrData);
							       	$this->log('Email sent to "' . $arrData['email'] . '"', 'ModuleEventsPaymentSuccess()', TL_GENERAL);
									}		
									
									if ($emailAdminVerification == 1){									
										// email the admin	
										$this->sendMail($emailAdminTemplate, $emailAdmin, $GLOBALS['TL_LANGUAGE'], $arrData);
							       	$this->log('Email sent to "' . $emailAdmin . '"', 'ModuleEventsPaymentSuccess()', TL_GENERAL);
									}
								
						
					  //    	header('Location: '.$redirect_to_completed_page.'');	
					  
			  
			}		
			
			
			
		} else {
				$this->log('Event booking payment issue ('.$thisStatus.', firstname: '.$transactionDetails['FIRSTNAME'].', lastname: '.$transactionDetails['LASTNAME'].', pending reason: '.$transactionDetails['PENDINGREASON'].', transaction id:  '.$transactionDetails['TRANSACTIONID'].', payment status: '.$transactionDetails['PAYMENTSTATUS'].'): event id:  '.$_SESSION['postbackfields']['eventID'].'', 'ModuleEventsPaymentSuccess()', TL_ERROR);
		}
		return;

	}
	
	

	

	
	
			private function getConfig($configID){
			
				$configArray = $this->Database->prepare("SELECT * FROM tl_evtreg_config WHERE id=?")->limit(1)->execute($configID);

								if (!$configArray->numRows) {
									$this->log('Config ID '.$configID.' not found', 'ModuleEventsRegistration()', TL_ERROR);
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
		
		
		
		private function sendMail($intId, $strRecipient, $strLanguage, $arrData, $strReplyTo='')
	{
		$objMail = $this->Database->prepare("SELECT * FROM tl_evtreg_mail m LEFT OUTER JOIN tl_evtreg_mail_content c ON m.id=c.pid WHERE m.id=$intId AND (c.language='$strLanguage' OR fallback='1') ORDER BY language='$strLanguage' DESC")->limit(1)->execute();
		
		if (!$objMail->numRows)
		{
			$this->log(sprintf('E-mail template ID %s for language %s not found', $intId, strtoupper($strLanguage)), 'ModuleEventsRegistration sendMail()', TL_ERROR);
			return;
		}
		
		$arrPlainData = array_map('strip_tags', $arrData);
		
		try
		{
			$objEmail = new Email();
			$objEmail->from = $objMail->sender;
			$objEmail->fromName = $objMail->senderName;
			$objEmail->subject = $this->parseSimpleTokens($this->replaceInsertTags($objMail->subject), $arrPlainData);
			$objEmail->text = $this->parseSimpleTokens($this->replaceInsertTags($objMail->text), $arrPlainData);
			
			if ($strReplyTo != '')
			{ 
				$objEmail->replyTo($strReplyTo);
			}
	
			// Add style sheet newsletter.css
			if (!$objNewsletter->sendText && file_exists(TL_ROOT . '/newsletter.css'))
			{
				$buffer = file_get_contents(TL_ROOT . '/newsletter.css');
				$buffer = preg_replace('@/\*\*.*\*/@Us', '', $buffer);
	
				$css  = '<style type="text/css">' . "\n";
				$css .= trim($buffer) . "\n";
				$css .= '</style>' . "\n";
				$arrData['head_css'] = $css;
			}
			
			// Add HTML content
			if (!$objMail->textOnly && strlen($objMail->html))
			{
				// Get mail template
				$objTemplate = new FrontendTemplate((strlen($objMail->template) ? $objMail->template : 'mail_default'));
	
				$objTemplate->body = $objMail->html;
				$objTemplate->charset = $GLOBALS['TL_CONFIG']['characterSet'];
				$objTemplate->css = '##head_css##';
				
				// Prevent parseSimpleTokens from stripping important HTML tags
				$GLOBALS['TL_CONFIG']['allowedTags'] .= '<doctype><html><head><meta><style><body>';
				$strHtml = str_replace('<!DOCTYPE', '<DOCTYPE', $objTemplate->parse());
				$strHtml = $this->parseSimpleTokens($this->replaceInsertTags($strHtml), $arrData);
				$strHtml = str_replace('<DOCTYPE', '<!DOCTYPE', $strHtml);
	
				// Parse template
				$objEmail->html = $strHtml;
				$objEmail->imageDir = TL_ROOT . '/';
			}
			
			if (strlen($objMail->cc))
			{
				$arrRecipients = trimsplit(',', $objMail->cc);
				foreach( $arrRecipients as $recipient )
				{
					$objEmail->sendCc($recipient);
				}
			}
			
			if (strlen($objMail->bcc))
			{
				$arrRecipients = trimsplit(',', $objMail->bcc);
				foreach( $arrRecipients as $recipient )
				{
					$objEmail->sendBcc($recipient);
				}
			}
			
			$attachments = deserialize($objMail->attachments);
		   	if(is_array($attachments) && count($attachments) > 0)
			{
				foreach($attachments as $attachment)
				{
					if(file_exists(TL_ROOT . '/' . $attachment))
					{
						$objEmail->attachFile(TL_ROOT . '/' . $attachment);
					}
				}
			}
		
			$objEmail->sendTo($strRecipient);
		}
		catch( Exception $e )
		{
			$this->log('ModuleEventsRegistration email error: ' . $e->getMessage(), __METHOD__, TL_ERROR);
		}
	}
	
	
}
?>