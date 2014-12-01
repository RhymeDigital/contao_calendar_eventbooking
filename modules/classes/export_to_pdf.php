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
 
 
class export_to_pdf extends \Backend
{
   

   public function export()
   {
   

     require_once('../system/modules/calendar_eventbooking/libs/nusoap/nusoap.php');
   
         if ($this->Input->get('key') != 'pdf')  {   return '';  }
         
         $thisID = $this->Input->get('id');

         
 
         
// Turn off WSDL caching
ini_set ('soap.wsdl_cache_enabled', 0);
    
 // SOAP WSDL endpoint
define ('ENDPOINT', 'https://api.livedocx.com/1.2/mailmerge.asmx?wsdl');

// Define timezone
date_default_timezone_set('Europe/Berlin');

		// Instantiate nuSOAP Client
		 
		$soap  = new \SoapClient(ENDPOINT);
		 
		// Set charset encoding for outgoing messages
		 
	//	$soap ->soap_defencoding = 'UTF-8';
   					  
   		$soap ->LogIn(
		    array(
		        'username' => '360fusion',
		        'password' => 'bookshop'
		    )
		);
		
		// Upload template

		$data = file_get_contents('../system/modules/calendar_eventbooking/assets/templates/attendance_list-template.docx', FILE_USE_INCLUDE_PATH);



		// Assign field values data to template
		 
		$soap->SetLocalTemplate(
		    array(
		        'template' => base64_encode($data),
		        'format'   => 'docx'
		    )
		);
		
/*		
			$fieldNames = $soap->getFieldNames();
			print_r($fieldNames);
			exit;
			
			
*/		




		$event = $this->Database->prepare("SELECT * FROM tl_evtreg_export WHERE id=?")->limit(1)->execute($thisID);
		
										if (!$event->numRows) {
											$this->log('Event not found', 'export_to_pdf()', TL_ERROR);
											header('Location: ?do=booking_export');	  
											return;
										}
										
			
				$eventName = $event->eventTitle;
				
				$_eventName = str_replace('(','',$eventName);
				$_eventName = str_replace(')','',$_eventName);
				$_eventName = str_replace(',','',$_eventName);
				$_eventName = str_replace(' ','_',$_eventName);
				$_eventName = str_replace('-','_',$_eventName);
				$_eventName = str_replace('?','',$_eventName);
				$_eventName = str_replace('&#40;','',$_eventName);
				$_eventName = str_replace('&#41;','',$_eventName);
				$this->eventname = $_eventName;
				
				
				
			$fieldValues = array (
			//	     'attendance_listÿSheet1ÿevent_name' => $event->eventTitle,
				     'event_name' => $event->eventTitle,
				);
 
			$result = $soap->SetFieldValues(
			    array(
			     'fieldValues' => $this->nuSoap_assocArrayToArrayOfArrayOfString($fieldValues)
			    )
			);
			
			
									
					 $attendees = $this->Database->prepare("SELECT * FROM tl_event_registration WHERE eventID=? ORDER BY lastname")->execute($event->pid);
						
						if (!$attendees->numRows) { 		
							
					$output .= '<div id="tl_buttons">
								<a href="contao/main.php?do=booking_export" class="header_back" title="Go back" accesskey="b" onclick="Backend.getScrollOffset();">Go back</a>
								</div>';
						$output .= '<div style="display:inline-block; padding:30px 20px; width:686px;">';
						$output .= '<div><h2 class="sub_headline">Export attendees for this event to attendance pdf</h2></div>';
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
		
		

		
		
					$blockFieldValues = array();
					$i = 0;
					while($attendees->next())
					{
					//	$blockFieldValues[$i]['attendance_listÿSheet1ÿattendee_name'] = $attendees->firstname.' '.$attendees->lastname;
						$blockFieldValues[$i]['attendee_name'] = $attendees->firstname.' '.$attendees->lastname;
						$i++;
					}
			
					$this->count = count($blockFieldValues);
					

		
	/*
		$blockFieldNames = $soap->getBlockFieldNames();

		print_r($blockFieldNames);
		exit;
		
			
		$blockFieldValues = array (
		    array ('attendance_listÿSheet1ÿattendee_name' => 'Darrell Martin'),
		    array ('attendance_listÿSheet1ÿattendee_name' => 'Ste Roberts'),
		    array ('attendance_listÿSheet1ÿattendee_name' => 'Ben Kenyon'),
		    array ('attendance_listÿSheet1ÿattendee_name' => 'Emma Stainthorpe')
		);
*/

		$soap->SetBlockFieldValues(
		    array (
		       'blockName'        => 'attendees',
		       'blockFieldValues' => $this->nuSoap_multiAssocArrayToArrayOfArrayOfString($blockFieldValues)
		    )
		);
		

			// Build the document
			 $soap->CreateDocument();
			

			// Get document as PDF
			$result = $soap->RetrieveDocument(
			    array(
			        'format' => 'pdf'
			    )
			);
			
			$data = $result->RetrieveDocumentResult;
			
			$soap->LogOut();
			unset($soap);
			
			header('Content-Type: application/x-download');
			header('Content-Disposition: attachment; filename="'.$this->eventname.'.pdf"');
			header('Cache-Control: private, max-age=0, must-revalidate');
			header('Pragma: public');
			echo base64_decode($data);
	//		header('Content-Type: application/pdf');
	//		print_r(base64_decode($data));
			return;


	
		}
		
		
			 
			/**
			 * Convert a PHP assoc array to a SOAP array of array of string, suitable for NuSOAP
			 *
			 * @param array $assoc
			 * @return array
			 */
			function nuSoap_assocArrayToArrayOfArrayOfString ($assoc)
			{
			    $arrayKeys   = array_keys($assoc);
			    $arrayValues = array_values($assoc);
			 
			    $result = array('ArrayOfString' => array(
			                  array('string' => $arrayKeys) ,
			                  array('string' => $arrayValues)) );
			 
			    return $result;
			}
			 
			/**
			 * Convert a PHP multi-depth assoc array to a SOAP array of array of array of string, suitable for NuSOAP
			 *
			 * @param array $multi
			 * @return array
			 */
			function nuSoap_multiAssocArrayToArrayOfArrayOfString ($multi)
			{
			    $arrayKeys = array_keys($multi[0]);
			 
			    $arrayValues = array();
			    foreach ($multi as $v) {
			        $arrayValues[] = array_values($v);
			    }
			 
			    $_arrayKeys = array();
			    $_arrayKeys[0] = $arrayKeys;
			 
			    $result = array('ArrayOfString' => array(array('string' => $arrayKeys)));
			 
			    foreach ($arrayValues as $a) {
			        $result['ArrayOfString'][] = array('string' => $a);
			    }
			 
			    return $result;
			}
		
	

}
