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
 * @copyright  Winans Creative 2009, Intelligent Spark 2010, iserv.ch GmbH 2010
 * @author     Fred Bliss <fred.bliss@intelligentspark.com>
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
 
 
class bulk_email extends \Backend
{
   

   public function export()
   {
   
         if ($this->Input->get('key') != 'mail')  {   return '';  }
         
          $thisID = $this->Input->get('id');
          

          $event = $this->Database->prepare("SELECT * FROM tl_evtreg_export WHERE id=?")->limit(1)->execute($thisID);

								if (!$event->numRows) {
									$this->log('Event not found', 'bulk_email()', TL_ERROR);
									return;
								}
								
	
			if ($attended == 1)	{
				 $attendees = $this->Database->prepare("SELECT * FROM tl_event_registration WHERE eventID=? AND attended=? ORDER BY id")->execute($event->pid,1);
			} else {
			 	 $attendees = $this->Database->prepare("SELECT * FROM tl_event_registration WHERE eventID=? ORDER BY id")->execute($event->pid);
			}	
			
				if (!$attendees->numRows) { 		
					
				$output .= '<div id="tl_buttons">
								<a href="contao/main.php?do=booking_export" class="header_back" title="Go back" accesskey="b" onclick="Backend.getScrollOffset();">Go back</a>
								</div>';
						$output .= '<div style="display:inline-block; padding:30px 20px; width:686px;">';
						$output .= '<div><h2 class="sub_headline">Bulk email all event attendees</h2></div>';
						$output .= '<fieldset id="pal_name_legend" class="tl_tbox block">';
						$output .= '<legend>'.$event->eventTitle.'</legend>';
						$output.=  '<p></p>';
						$output.=  '<div id="elementsToOperateOn" style="margin-left: 10px;">';
						$output.=  '<h3>';
						$output.=  'No Attendees found.';
						$output.=  '</h3></div>';
						$output .= '</fieldset>';
						return $output;

				//	header('Location: ?do=booking_export');	 
				//	return;
			}

			$results = array();
			$i = 0;
			while($attendees->next())
			{
				$results[$i][firstname] = $attendees->firstname;
				$results[$i][lastname] = $attendees->lastname;
				$results[$i][email] = $attendees->email;
				$results[$i][eventName] = $attendees->eventName;
				$i++;
			}
			
			
			

				if ($this->Input->post('FORM_SUBMIT') == 'submitted'){
					
		
					$emailTemplateID = $this->Input->post('ctrl_emailTemplate');
					$attended = $this->Input->post('ctrl_attended');
					$verification = $this->Input->post('ctrl_verification');
					$toggleEmailType = $this->Input->post('toggleElement');
					$senderName = $this->Input->post('senderName');
					$sender =  $this->Input->post('sender');
					$cc =  $this->Input->post('cc');
					$bcc  =  $this->Input->post('bcc');
					$subject = $this->Input->post('subject');
					$message = $this->Input->post('message');
					$this->ccd = 0;
					$this->bccd = 0;
					
				//	echo "senderName :".$senderName;
				//	exit;
					
						$output .= '<div id="tl_buttons">
								<a href="contao/main.php?do=booking_export" class="header_back" title="Go back" accesskey="b" onclick="Backend.getScrollOffset();">Go back</a>
								</div>';
						$output .= '<div style="display:inline-block; padding:30px 20px; width:686px;">';
						$output .= '<div><h2 class="sub_headline">Bulk email all event attendees</h2></div>';
						$output .= '<fieldset id="pal_name_legend" class="tl_tbox block">';
						$output .= '<legend>'.$event->eventTitle.'</legend>';
						$output.=  '<p></p>';
	
					
					
						$calevt = $this->Database->prepare("SELECT * FROM tl_calendar_events WHERE title=? ")->limit(1)->execute($results[0][eventName]);

							$eventDate = gmdate("d/m/Y", $calevt->startDate);
							$eventStartTime = gmdate("H:i:s", $calevt->startTime);
							$eventEndTime = gmdate("H:i:s", $calevt->endTime);	
							

				
					/*	
						echo $eventDate.'<br />';
						echo $eventStartTime.'<br />';
						echo $eventEndTime.'<br />';
						exit;
					*/
					
					$errorCheck = 0;
					
						for ($z=0; $z < count($results); $z++)
						 {
													  	
												unset($arrData);
												$arrData[eventTitle] = $event->eventTitle;
											   $arrData[firstname] = $results[$z][firstname];
						  						$arrData[lastname] = $results[$z][lastname];  
						  						$arrData[email] = $results[$z][email];  
						  						$arrData[eventName] = $results[$z][eventName];
						  						$arrData[date] = $eventDate;
						  						$arrData[eventStartTime] = $eventStartTime;
						  						$arrData[eventEndTime] = $eventEndTime;
						  							//	 print_r($arrData);

										if (($verification == 1) && ($toggleEmailType == 1)){
	 
						  					  $this->sendMail($emailTemplateID, $arrData[email], $GLOBALS['TL_LANGUAGE'], $arrData);
						  					//	echo "sent template emails";
						  					//	exit;
						  						
						  				}
						  				
						  				if (($verification == 1) && (!$toggleEmailType)){
						  					

						  					  			 $arrData[senderName] = $senderName;
													     $arrData[sender] = $sender;										     
						  								 $arrData[cc] = $cc;  
						  								 $arrData[bcc] = $bcc;  
						  								 $arrData[subject] = $subject;  
						  								 $arrData[message] = $message;  
						  								 
										 if (((($senderName == NULL) || ($sender  == NULL) || ($subject  == NULL) || ($message  == NULL)))) {
												$errorCheck = 1;    	
										  }else{	 
						  						$this->sendMailDirect($arrData[email], $GLOBALS['TL_LANGUAGE'], $arrData);
						  				//		echo "sent direct emails";
						  				//		exit;
						  					}	
						  				}
					      
					      }
					      
					      
										if (($verification == 1) &&  ($errorCheck != 1))	{
						  						$output .= '<h3>Bulk emails sent.</h3>';
						  				}
						  				
						  			 if ((!$verification) && ($errorCheck != 1))	{
						  					$output .= '<h3>Bulk emails not sent.</h3>';
											$output.=  '<p></p>';
											$output.=  'If you do wish to send the emails please tick the \'verify\' checkbox on the previous page.';
											$output.=  '</br>';
					      					}
					      					
					      				 if (($verification ==1 ) && ($errorCheck == 1))	{
						  					$output .= '<h3>Bulk emails not sent.</h3>';
											$output.=  '<p></p>';
											$output.=  'Please make sure that you have filled out the email fields correctly. <br/> If you do wish to send the emails please tick the \'verify\' checkbox on the previous page.';
											$output.=  '</br>';
					      					}	
					      					
					      					
					      					
					      					
					      					
					}else{
						
			 $emailTemplates = $this->Database->prepare("SELECT * FROM tl_evtreg_mail ORDER BY id DESC")->execute();
							
							if (!$emailTemplates->numRows) { 		  
								return;
						}
			
						$templateresults = array();
						$i = 0;
						while($emailTemplates->next())
						{
							$templateresults[$i][id] = $emailTemplates->id;
							$templateresults[$i][name] = $emailTemplates->name;
							$templateresults[$i][senderName] = $emailTemplates->senderName;
							$templateresults[$i][sender] = $emailTemplates->sender;
							$i++;
						}



			$output .= '<div id="tl_buttons">
								<a href="contao/main.php?do=booking_export" class="header_back" title="Go back" accesskey="b" onclick="Backend.getScrollOffset();">Go back</a>
								</div>';

			$output .= '<div style="display:inline-block; padding:30px 20px; width:686px;">';
			$output .= '<div><h2 class="sub_headline">Bulk email all event attendees</h2></div>';
				
				



			$output .= '<fieldset id="pal_name_legend" class="tl_tbox block">';
			$output .= '<legend>'.$event->eventTitle.'</legend>';
			$output.=  '<p></p>';
			
	//		$output .= '<fieldset id="pal_type_legend" class="tl_tbox block">';
	//		$output .= '<legend onclick="AjaxRequest.toggleFieldset(this, \'type_legend\', \'booking_export\')">Template</legend>';
			
			$output.=  '<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script> 
		<script type="text/javascript">
			function toggleStatus() {
			    if ($(\'#toggleElement\').is(\':checked\')) {
			         $(\'#elementsToOperateOn  :input\').removeAttr(\'disabled\');
			         $(\'#elementsToOperateOn\').show();
			         $(\'#ctrl_message\').hide();
			    } else {
			            $(\'#elementsToOperateOn  :input\').attr(\'disabled\', true);
			            $(\'#elementsToOperateOn\').hide();    
			            $(\'#ctrl_message\').show(); 
			    }   
			}
			</script>';

			$output.=  '<form action="contao/main.php?do=booking_export&key=mail&id='.$thisID.'&class="tl_form" method="post" enctype="application/x-www-form-urlencoded">';
			
			$output .= '<div class="w80">
								<div id="ctrl_usetemplate" class="tl_checkbox_single_container">
								<input type="checkbox" name="toggleElement" id="toggleElement" class="tl_checkbox" value="1" onchange="toggleStatus()"/>
								<label for="ctrl_usetemplate">Use Email Template</label>
								<p class="tl_help tl_tip">Use alternative email template</p></div>';
			
			$output .= '<div id="elementsToOperateOn" style="display: none">
								<h3>
								<label for="ctrl_emailTemplate">Select Email Template</label>
								</h3>
								<select name="ctrl_emailTemplate" id="ctrl_emailTemplate" class="tl_select" onfocus="Backend.getScrollOffset()" disabled="disabled"></div>';
							
							 for ($i = 0; $i < sizeof($templateresults); $i++) {
			$output.= '	
		  	
		  	<option value="'.$templateresults[$i][id].'">'.$templateresults[$i][name].'</option>'; }
		  		

			$output .= '</select>
								<p class="tl_help tl_tip">Select the email template to use</p>
								</div>';
								
								
								
						
			$output .= '<div id="ctrl_message"><p><br/><br/></p>
					
				<fieldset class="tl_box block" id="pal_address_legend">
							<legend>Address</legend>
							<div class="w50">
							  <h3><label for="ctrl_senderName">Sender Name</label></h3>
							  <input type="text" onfocus="Backend.getScrollOffset();" maxlength="255" value="'.$templateresults[0][senderName].'" class="tl_text" id="ctrl_senderName" name="senderName">
							  <p class="tl_help tl_tip">Enter the name of the sender.</p>
							</div>
							<div class="w50">
							  <h3><label for="ctrl_sender">Sender Email</label></h3>
							  <input type="text" onfocus="Backend.getScrollOffset();" maxlength="255" value="'.$templateresults[0][sender].'" class="tl_text" id="ctrl_sender" name="sender">
							  <p class="tl_help tl_tip">Enter the e-mail address of the sender. The recipient will reply to this address.</p>
							</div>
							<div class="w50">
							  <h3><label for="ctrl_cc">Send a CC to</label></h3>
							  <input type="text" onfocus="Backend.getScrollOffset();" maxlength="255" value="" class="tl_text" id="ctrl_cc" name="cc">
							  <p class="tl_help tl_tip">Recipients that should receive a carbon copy of the mail. Separate multiple addresses with a comma.</p>
							</div>
							<div class="w50">
							  <h3><label for="ctrl_bcc">Send a BCC to</label></h3>
							  <input type="text" onfocus="Backend.getScrollOffset();" maxlength="255" value="" class="tl_text" id="ctrl_bcc" name="bcc">
							  <p class="tl_help tl_tip">Recipients that should receive a blind carbon copy of the mail. Separate multiple addresses with a comma.</p>
							</div>
					</fieldset>
				
				
				
				
				
				
							<fieldset class="tl_box block" id="pal_email_legend">
							<legend>Email</legend>
				
				
				<div class="long">
	  				<h3><label for="ctrl_subject">Subject</label></h3>
	 				 <input type="text" onfocus="Backend.getScrollOffset();" maxlength="255" class="tl_text" id="ctrl_subject" name="subject">
	  				<p class="tl_help tl_tip">Enter the email subject line.</p>
				</div>
				
				<h3><label for="ctrl_html">Email Message Content</label></h3>	
									<textarea onfocus="Backend.getScrollOffset();" cols="80" rows="12" class="tl_textarea" id="ctrl_html" name="message"></textarea>							
									<p class="tl_help tl_tip">Enter the plain text of the message.</p></fieldset>';	
									
									
						
									
			$output .= '</div>';
									
								
			$output .= '<div class="w50 m12 cbx">
								<div id="ctrl_attended" class="tl_checkbox_single_container">
								<input type="checkbox" name="ctrl_attended" id="ctrl_attended" class="tl_checkbox" value="1" onfocus="Backend.getScrollOffset();"/>
								<label for="ctrl_attended">Attended Only</label>
								</div><p class="tl_help tl_tip">Only send emails to those whom attended</p>';			
								
								
			$output .= '<div class="w80 m12 cbx">
								<div id="ctrl_verification" class="tl_checkbox_single_container">
								<input type="checkbox" name="ctrl_verification" id="ctrl_verification" class="tl_checkbox" value="1" onfocus="Backend.getScrollOffset();"/>
								<label for="ctrl_verification">Verify</label>
								</div><p class="tl_help tl_tip">Select to verify that you really want to send these emails</p>';
								
					

			
								
								
								
			$output .= '</fieldset>';
			$output .= '</div><input type="hidden" name="FORM_SUBMIT" value="submitted"/>';
			$output .= '<div class="tl_formbody_submit">
							<div class="tl_submit_container">
							<input type="submit" name="send" id="send" class="tl_submit" accesskey="s" value="Send bulk email"/>
							</div>
							</div>
							</form>
  						<script>$(window).load(function() {	$(\'#easy_themes\').hide(0); }); </script>';
			
							
	}
	
	


			return $output;


   }
   
   			private function sendMailDirect($strRecipient, $strLanguage, $arrData, $strReplyTo='')
   			{
   				
   				$arrPlainData = array_map('strip_tags', $arrData);
   				
   				
   			try
		{
			$objEmail = new Email();
			$objEmail->from = $arrData[sender];
			$objEmail->fromName = $arrData[senderName];
			$objEmail->subject = $this->parseSimpleTokens($this->replaceInsertTags($arrData[subject]), $arrPlainData);
			$objEmail->text = $this->parseSimpleTokens($this->replaceInsertTags($arrData[message]), $arrPlainData);
			
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
			
			/*
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
			*/
			
			if ((strlen($arrData[cc])) && ($this->ccd == 0))
			{
				$arrRecipients = trimsplit(',', $arrData[cc]);
				foreach( $arrRecipients as $recipient )
				{
					$objEmail->sendCc($recipient);
				}
					$this->ccd = 1;
			}
			
			if ((strlen($arrData[bcc])) && ($this->bccd == 0))
			{
				$arrRecipients = trimsplit(',', $arrData[bcc]);
				foreach( $arrRecipients as $recipient )
				{
					$objEmail->sendBcc($recipient);
				}
					$this->bccd = 1;
			}
			
			
			/*
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
			*/
		
			$objEmail->sendTo($strRecipient);
		}
		catch( Exception $e )
		{
			$this->log('bulk_email email error: ' . $e->getMessage(), __METHOD__, TL_ERROR);
		}
	}
   
   

   			private function sendMail($intId, $strRecipient, $strLanguage, $arrData, $strReplyTo='')
	{
		$objMail = $this->Database->prepare("SELECT * FROM tl_evtreg_mail m LEFT OUTER JOIN tl_evtreg_mail_content c ON m.id=c.pid WHERE m.id=$intId AND (c.language='$strLanguage' OR fallback='1') ORDER BY language='$strLanguage' DESC")->limit(1)->execute();
		
		if (!$objMail->numRows)
		{
			$this->log(sprintf('E-mail template ID %s for language %s not found', $intId, strtoupper($strLanguage)), 'sendMail()', TL_ERROR);
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

			
			if ((strlen($objMail->cc)) && ($this->ccd == 0))
			{
				$arrRecipients = trimsplit(',', $objMail->cc);
				foreach( $arrRecipients as $recipient )
				{
					$objEmail->sendCc($recipient);
				}
				$this->ccd = 1;
			}
			
			if ((strlen($objMail->bcc)) && ($this->bccd == 0))
			{
				$arrRecipients = trimsplit(',', $objMail->bcc);
				foreach( $arrRecipients as $recipient )
				{
					$objEmail->sendBcc($recipient);
				}
					$this->bccd = 1;
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
			$this->log('bulk_email email error: ' . $e->getMessage(), __METHOD__, TL_ERROR);
		}
	}

}
