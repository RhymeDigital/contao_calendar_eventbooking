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



class ModuleEventsRegistration extends \Module
{

	/**
	 * Template
	 * @var string
	 */


	protected $strTemplate = 'mod_eventsregistrationform';
		

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### EVENT REGISTRATION &#169; 360Fusion 2012 ###';
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
	
	

	
		  $configArray = $this->getConfig(1);
/*		  
echo 'configArray: ';
print_r($configArray);
echo '<br>configArray->adminEmail: '.$configArray->adminEmail;
exit;
*/		  


		 $this->strTemplate = $this->evtreg_template;
		 $this->Template = new \FrontendTemplate($this->strTemplate);
	             
	                     
	    $emailAdmin = $configArray->adminEmail;
	    $emailRegVerification = $configArray->emailRegVerification;
	    $emailAdminVerification = $configArray->emailAdminVerification;
	    $emailRegTemplate = $configArray->emailRegTemplate;
	    $emailAdminTemplate = $configArray->emailAdminTemplate;
	    
	    
		$this->Template->action = $this->Environment->base.$this->Environment->request;
		$this->Template->subdir = "system/modules/calendar_eventbooking/";

		$thisID =  $this->Input->get('id');
/*		
echo 'thisID: '.$thisID;
exit;
*/
		// Get current event
		$time = time();
		
		 $event = $this->Database->prepare("SELECT * FROM tl_calendar_events WHERE id=?")
		 	->limit(1)
          ->execute($thisID);

			
			$this->Template->headline = $event->evtreg_formHeadline;
			$this->Template->id = $event->id;
			$this->Template->title = $event->title;
			$this->Template->bookable_spaces = $event->evtreg_availableSpaces;
			$this->Template->bookable_spaces_remaining = $event->evtreg_availableSpacesRemaining;
			$this->Template->registrationFields =  deserialize($event->evtreg_registrationFields);
			$this->Template->evtreg_cost = $event->evtreg_cost;
			$this->Template->evtreg_register = $event->evtreg_register;
			$this->Template->evtreg_mandatory = deserialize($event->evtreg_registrationMand);
			
			
   			$completed_pageID = $this->evtreg_success_jumpTo;		
			$fieldsArray =  deserialize($event->evtreg_registrationFields);
			$this->Template->submitError = NULL;
			$BSTOffset = +1.00;
			$GMTMySqlString = gmdate("Y-m-d H:i:s", time() + $BSTOffset * 3600);
			
			if ($event->evtreg_availableSpacesRemaining <= 0) {
				$this->Template->submitError =   '<span style="color:red;">This event is fully booked.</span>'; 
					return;
			}
			
			// Paid event
			if (($event->evtreg_cost > 0) &&  ($event->evtreg_availableSpacesRemaining > 0)){
				$checkoutJumpTo = $configArray->checkout_jumpTo;
		//		$_SESSION['evtreg_success_jumpTo']  =  $this->evtreg_success_jumpTo;		
		
/*
	  if (FE_USER_LOGGED_IN) {
				$redirect_to_checkout_page = $this->Environment->base.$this->getPageFromID($checkoutJumpTo);
				header('Location: '.$redirect_to_checkout_page.'?id='.$thisID.'');
			} else { 
				$redirect_to_checkout_page = $this->Environment->base.$this->getPageFromID(141);
				header('Location: '.$redirect_to_checkout_page.'?id='.$thisID.'');
			}	
*/	

/*
echo '<strong>Environment->base: </strong>'.$this->Environment->base.'<br>';
echo '<strong>checkoutJumpTo: </strong>'.$checkoutJumpTo.'<br>';
echo '<strongthis->getPageFromID($checkoutJumpTo): </strong>'.$this->getPageFromID($checkoutJumpTo).'<br>';
exit;
*/
				$redirect_to_checkout_page = $this->Environment->base.$this->getPageFromID($checkoutJumpTo);
/*			
echo 'redirect_to_checkout_page: '.$redirect_to_checkout_page.'?id='.$thisID;
exit;
	*/	
				header('Location: '.$redirect_to_checkout_page.'?id='.$thisID.'');
			
			// Free event 	
			} else {

/*			
	  if (!FE_USER_LOGGED_IN) {
				$redirect_to_checkout_page = $this->Environment->base.$this->getPageFromID(141);
				header('Location: '.$redirect_to_checkout_page.'?id='.$thisID.'');
			}	
*/
		     if (($this->Input->post('FORM_SUBMIT') == 'submitted') && ($event->evtreg_availableSpacesRemaining > 0)) {	

								for ($i=0; $i<count($fieldsArray); $i=($i+1))
										{
							 		      $postbackfields[''.$fieldsArray[$i].'']  = $this->Input->post($fieldsArray[$i]);
							 		      if ($postbackfields[''.$fieldsArray[$i].''] == NULL && $fieldsArray[$i] != 'user_photo'){  $this->Template->submitError =   '<span style="color:red;">Please complete all fields</span>'; }
							 		      
										}
										

							if ($this->Template->submitError != NULL){
								$this->Template->postbackfields = $postbackfields;
								return;
							} else {
								
								// hidden fields
								$postbackfields['eventID'] = $event->id;
								$postbackfields['date'] = $GMTMySqlString;
								$postbackfields['eventName'] = stripslashes($event->title);
								$postbackfields['tstamp'] =  time();
								
							// save the photo
							if ($_FILES['user_photo']['error'] == 0) { $postbackfields['user_photo'] = $this->savePhoto($event->id,stripslashes($event->title)); }	
								
							$newBooking = $this->Database->prepare("INSERT INTO tl_event_registration %s")->set($postbackfields)->execute();
							
							$this->log('New event booking submitted: event id:  '.$event->id.'', 'ModuleEventsRegistration()', TL_GENERAL);
							$redirect_to_completed_page = $this->Environment->base.$this->getPageFromID($completed_pageID);
						
							 
						  
				  // count the total spaces taken in this event in tl_event_registration so we can update the evtreg_availableSpacesRemaining (evtreg_availableSpaces - count in tl_event_registration)
					 $thisRegSpaces = $this->Database->prepare("SELECT id FROM tl_event_registration WHERE eventID=?")->execute($event->id);
					 $event->evtreg_availableSpacesRemaining = ($event->evtreg_availableSpaces - $thisRegSpaces->numRows);
					 $updateEvent = $this->Database->prepare("UPDATE tl_calendar_events SET evtreg_availableSpacesRemaining=?  WHERE id=?")->execute($event->evtreg_availableSpacesRemaining, $event->id);
					 $this->log('Updated number of spaces remaining on event id eventID: '.$event->id.' , spaces remaining: '.$event->evtreg_availableSpacesRemaining.'', 'ModuleEventsRegistration() - free event', TL_GENERAL);
								
								
									$postbackfields['user_photo'] = str_replace(" ","%20", $postbackfields['user_photo']);
									$arrData = $postbackfields;


									if ($emailRegVerification == 1){
										// email to the user	
										$this->sendMail($emailRegTemplate, $arrData['email'], $GLOBALS['TL_LANGUAGE'], $arrData);
							       	$this->log('Email sent to "' . $arrData['email'] . '"', 'ModuleEventsRegistration()', TL_GENERAL);
									}		
									
									if ($emailAdminVerification == 1){									
										// email the admin	
										$this->sendMail($emailAdminTemplate, $emailAdmin, $GLOBALS['TL_LANGUAGE'], $arrData);
							       	$this->log('Email sent to "' . $emailAdmin . '"', 'ModuleEventsRegistration()', TL_GENERAL);
									}
								
								
					      	header('Location: '.$redirect_to_completed_page.'');	
 	 							}		
			  }
					 
					 
			}
		return;
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
			
			
			
				private function getConfig($configID){
			
				$configArray = $this->Database->prepare("SELECT * FROM tl_evtreg_config WHERE id=?")->limit(1)->execute($configID);

								if (!$configArray->numRows) {
									$this->log('Config ID '.$configID.' not found', 'ModuleEventsRegistration()', TL_ERROR);
									return;
								}

						return $configArray;
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
			$objEmail = new \Email();
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
				$objTemplate = new \FrontendTemplate((strlen($objMail->template) ? $objMail->template : 'mail_default'));
	
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
	
	
	private function savePhoto($thisID,$eventTitle){
		

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
				$this->log('File "'.$file['name'].'" exceeds the maximum file size of '.$maxlength_kb, 'ModuleEventsRegistration validate()', TL_ERROR);
			}

			if ($file['error'] == 3)
			{
				$this->log('File "'.$file['name'].'" was only partially uploaded', 'ModuleEventsRegistration validate()', TL_ERROR);
			}

			unset($_FILES['user_photo']);
			return;
		}

		// File is too big
		if ($this->maxlength > 0 && $file['size'] > $this->maxlength)
		{
			$this->log('File "'.$file['name'].'" exceeds the maximum file size of '.$maxlength_kb, 'ModuleEventsRegistration validate()', TL_ERROR);

			unset($_FILES['user_photo']);
			return;
		}

		$pathinfo = pathinfo($file['name']);
		$uploadTypes = trimsplit(',', $this->extensions);

		// File type is not allowed
		if (!in_array(strtolower($pathinfo['extension']), $uploadTypes))
		{
			$this->log('File type "'.$pathinfo['extension'].'" is not allowed to be uploaded ('.$file['name'].')', 'ModuleEventsRegistration validate()', TL_ERROR);

			unset($_FILES['user_photo']);
			return;
		}

		if (($arrImageSize = @getimagesize($file['tmp_name'])) != false)
		{
			// Image exceeds maximum image width
			if ($arrImageSize[0] > $GLOBALS['TL_CONFIG']['imageWidth'])
			{
				$this->log('File "'.$file['name'].'" exceeds the maximum image width of '.$GLOBALS['TL_CONFIG']['imageWidth'].' pixels', 'ModuleEventsRegistration validate()', TL_ERROR);

				unset($_FILES['user_photo']);
				return;
			}

			// Image exceeds maximum image height
			if ($arrImageSize[1] > $GLOBALS['TL_CONFIG']['imageHeight'])
			{
				$this->log('File "'.$file['name'].'" exceeds the maximum image height of '.$GLOBALS['TL_CONFIG']['imageHeight'].' pixels', 'ModuleEventsRegistration validate()', TL_ERROR);

				unset($_FILES['user_photo']);
				return;
			}
		}

		// Store file in the session and optionally on the server
		if ($_FILES['user_photo'])
		{
			$_SESSION['FILES']['user_photo'] = $_FILES['user_photo'];
			$this->log('File "'.$file['name'].'" uploaded successfully', 'ModuleEventsRegistration validate()', TL_FILES);



			
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

					$this->log('File "'.$file['name'].'" has been moved to "'.$strUploadFolder.'"', 'ModuleEventsRegistration validate()', TL_FILES);

				}

		}
			unset($_FILES['user_photo']);
		  return $strUploadFolder . '/' . $file['name'];
		
	}
	
	
}