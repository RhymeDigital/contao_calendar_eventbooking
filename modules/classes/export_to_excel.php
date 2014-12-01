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
 
class export_to_excel extends \Backend
{

   public function export()
   {
   	
   	
    if (($this->Input->get('key') != 'xls') && ($this->Input->get('key') != 'export_all_events'))  {   return '';  }
		 // Fetch the current time
		$datestamp = time();
		// Convert the time
		$datestamp = "_".strftime("%d-%m-%y@%I-%M-%p");
		require_once('../system/modules/calendar_eventbooking/libs/Worksheet.php');
		require_once('../system/modules/calendar_eventbooking/libs/Workbook.php');
		$semi_rand = md5(time()); 
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
		$fileatt_type = "application/octet-stream";
		$xls_workbook_file = $datestamp.".xls";
		// Creating a workbook
		$workbook = new \Workbook($xls_workbook_file);
		// Creating the first worksheet
		$worksheet1=&$workbook->add_worksheet('Event-booking');
	  	// Format for the headings
	  	$formatot=&$workbook->add_format();
	  	$formatot->set_size(10);
	  	$formatot->set_align('center');
	  	$formatot->set_color('white');
	  	$formatot->set_pattern();
	  	$formatot->set_fg_color('purple');
	  	
	  	
	  	if ($this->Input->get('key') == 'xls'){
		  	$thisID = $this->Input->get('id');
		  	$event =  $this->Database->prepare("SELECT pid FROM tl_evtreg_export WHERE id = ?")
		  	 				  ->limit(1)
							  ->execute($thisID);   
		  	$result = $this->Database->prepare("SELECT * FROM tl_event_registration WHERE eventID = ?")->execute($event->pid);
		 	 	// get the event start and end dates
		 		$eventdates = $this->Database->prepare("SELECT startDate,endDate FROM tl_calendar_events WHERE id = ?")->execute($event->pid);
			}
		
		if ($this->Input->get('key') == 'export_all_events'){
			
			$result = $this->Database->prepare("SELECT * FROM tl_event_registration ")->execute();
		}
		

		  	if (!$result->numRows) {
		  		
		  		
					$output .= '<div id="tl_buttons">
								<a href="contao/main.php?do=booking_export" class="header_back" title="Go back" accesskey="b" onclick="Backend.getScrollOffset();">Go back</a>
								</div>';
						$output .= '<div style="display:inline-block; padding:30px 20px; width:686px;">';
						$output .= '<div><h2 class="sub_headline">Export attendees for this event to excel</h2></div>';
						$output .= '<fieldset id="pal_name_legend" class="tl_tbox block">';
						$output .= '<legend>'.$event->eventTitle.'</legend>';
						$output.=  '<p></p>';
						$output.=  '<div id="elementsToOperateOn" style="margin-left: 10px;">';
						$output.=  '<h3>';
						$output.=  'No Attendees found.';
						$output.=  '</h3></div>';
						$output .= '</fieldset>';
						return $output;		
		  		
		  		
	    //   header('Location: ?do=booking_export');	
			//	 return;		
				}
	
		$BSTOffset = +1.00;
		
	  	$messagetxt = array();
  		array_push($messagetxt, 'Event name');
  		$worksheet1->write_string(0,0,'Event name',$formatot);
	  	array_push($messagetxt, "\t");
	  	
	  	array_push($messagetxt, 'Event Startdate');
  		$worksheet1->write_string(0,1,'Event Startdate',$formatot);
  	  	array_push($messagetxt, "\t");
  	  	
			array_push($messagetxt, 'Event Enddate');
  		$worksheet1->write_string(0,2,'Event Enddate',$formatot);
  	  array_push($messagetxt, "\t");
	  	
  		array_push($messagetxt, 'Entry Date');
  		$worksheet1->write_string(0,3,'Entry Date',$formatot);
  		array_push($messagetxt, "\t");

  		array_push($messagetxt, 'Title');
  		$worksheet1->write_string(0,4,'Title',$formatot);
  		array_push($messagetxt, "\t");
  		
  		array_push($messagetxt, 'Job Title');
  		$worksheet1->write_string(0,5,'Job Title',$formatot);
  		array_push($messagetxt, "\t");
  		
  		array_push($messagetxt,'First name');
  		$worksheet1->write_string(0,6,'First name',$formatot);
  		array_push($messagetxt, "\t");
  		
  		 array_push($messagetxt, 'Last name');
  		$worksheet1->write_string(0,7,'Last name',$formatot);
  		array_push($messagetxt, "\t");
  		
  		array_push($messagetxt, 'GMC Number');
  		$worksheet1->write_string(0,8,'GMC Number',$formatot);
  		array_push($messagetxt, "\t");
  	
  		array_push($messagetxt, 'Email');
  		$worksheet1->write_string(0,9,'Email',$formatot);
  		array_push($messagetxt, "\t");
  		
  		array_push($messagetxt, 'Address One');
  		$worksheet1->write_string(0,10,'Address One',$formatot);
  		array_push($messagetxt, "\t");
  		
  		array_push($messagetxt, 'Address Two');
  		$worksheet1->write_string(0,11,'Address Two',$formatot);
  		array_push($messagetxt, "\t");
  		
  		array_push($messagetxt, 'City');
  		$worksheet1->write_string(0,12,'City',$formatot);
  		array_push($messagetxt, "\t");
  		
  		array_push($messagetxt, 'Postcode');
  		$worksheet1->write_string(0,13,'Postcode',$formatot);
  		array_push($messagetxt, "\t");
  		
  		array_push($messagetxt, 'County');
  		$worksheet1->write_string(0,14,'County',$formatot);
  		array_push($messagetxt, "\t");
  		
  		array_push($messagetxt, 'Country');
  		$worksheet1->write_string(0,15,'Country',$formatot);
  		array_push($messagetxt, "\t");
  		
  		array_push($messagetxt, 'Telephone number');
  		$worksheet1->write_string(0,16,'Telephone number',$formatot);
  		array_push($messagetxt, "\t");
  		
  		array_push($messagetxt, 'Mobile');
  		$worksheet1->write_string(0,17,'Mobile',$formatot);
  		array_push($messagetxt, "\t");
  		
  		array_push($messagetxt, 'Fax');
  		$worksheet1->write_string(0,18,'Fax',$formatot);
  		array_push($messagetxt, "\t");
  		
  		array_push($messagetxt, 'Company');
  		$worksheet1->write_string(0,19,'Company',$formatot);
  		array_push($messagetxt, "\t");
  		
  		array_push($messagetxt, 'Website');
  		$worksheet1->write_string(0,20,'Website',$formatot);
  		array_push($messagetxt, "\t");
  		
  		array_push($messagetxt, 'Date of birth');
  		$worksheet1->write_string(0,21,'Date of birth',$formatot);
  		array_push($messagetxt, "\t");
  		
  		array_push($messagetxt, 'Gender');
  		$worksheet1->write_string(0,22,'Gender',$formatot);
  		array_push($messagetxt, "\t");
  		
  		array_push($messagetxt, 'User Notes');
  		$worksheet1->write_string(0,23,'User Notes',$formatot);
  		array_push($messagetxt, "\t");
  		
  		array_push($messagetxt, 'Dietary Requirements');
  		$worksheet1->write_string(0,24,'Dietary Requirements',$formatot);
  		array_push($messagetxt, "\t");
  		
  		 array_push($messagetxt, 'Disabilities');
  		$worksheet1->write_string(0,25,'Dietary Disabilities',$formatot);
  		array_push($messagetxt, "\t");
  		
  		array_push($messagetxt, 'Dinner');
  		$worksheet1->write_string(0,26,'Dinner',$formatot);
  	  	array_push($messagetxt, "\t");
  	  	
  		array_push($messagetxt, 'Transport');
  		$worksheet1->write_string(0,27,'Transport',$formatot);
  	  	array_push($messagetxt, "\n");


  		 	$line = 1;
			while( $result->next())	{ 
				

				if ($line == 1) { $eventName = $result->eventName;}

				       $worksheet1->write($line,0,$result->eventName);
				       array_push($messagetxt, $result->eventName. "\t");
       
				          // H:i:s
					    if ($eventdates->startDate){
						    $worksheet1->write($line,1, gmdate("d-m-Y", $eventdates->startDate + $BSTOffset * 3600));
						    array_push($messagetxt, gmdate("d-m-Y", $eventdates->startDate + $BSTOffset * 3600). "\t");	 
						}
						
					    if ($eventdates->endDate){
						    $worksheet1->write($line,2,gmdate("d-m-Y", $eventdates->endDate + $BSTOffset * 3600));
						    array_push($messagetxt, gmdate("d-m-Y", $eventdates->endDate + $BSTOffset * 3600). "\t");	 
					 	 }
				       	
				       	
					    $worksheet1->write($line,3, $result->date);
					    array_push($messagetxt, $result->date. "\t");	
					    
					   $worksheet1->write($line,4,$result->title);
				       array_push($messagetxt, $result->title. "\t");    
					    
					    $worksheet1->write($line,5,$result->jobTitle);
				       array_push($messagetxt, $result->jobTitle. "\t");    
					    
					    $worksheet1->write($line,6,$result->firstname);
				       array_push($messagetxt, $result->firstname. "\t");    	
				       
					    $worksheet1->write($line,7,$result->lastname);
				       array_push($messagetxt, $result->lastname. "\t");    
				       
				       $worksheet1->write($line,8,$result->gmcNumber);
				       array_push($messagetxt, $result->gmcNumber. "\t");    	
				       
					    $worksheet1->write($line,9,$result->email);
				       array_push($messagetxt, $result->email. "\t");   
				       
				       $worksheet1->write($line,10,$result->addressOne);
					    array_push($messagetxt, $result->addressOne. "\t");	 
					    
					    $worksheet1->write($line,11,$result->addressTwo);
					    array_push($messagetxt, $result->addressTwo. "\t");	 
				       
				       $worksheet1->write($line,12,$result->city);
					    array_push($messagetxt, $result->city. "\t");	 
				       
				       $worksheet1->write($line,13,$result->postcode);
					    array_push($messagetxt, $result->postcode. "\t");	 
				       
				        $worksheet1->write($line,14,$result->county);
					    array_push($messagetxt, $result->county. "\t");	 
					    
					    $worksheet1->write($line,15,$result->country);
					    array_push($messagetxt, $result->country. "\t");	 
				       
				       $worksheet1->write($line,16,$result->phone);
					    array_push($messagetxt, $result->phone. "\t");	 
					    
					    $worksheet1->write($line,17,$result->mobile);
					    array_push($messagetxt, $result->mobile. "\t");	 
				       
				       $worksheet1->write($line,18,$result->fax);
					    array_push($messagetxt, $result->fax. "\t");	 
				       
					    $worksheet1->write($line,19,$result->company);
				       array_push($messagetxt, $result->company. "\t"); 
				       
				        $worksheet1->write($line,20,$result->website);
				       array_push($messagetxt, $result->website. "\t");    	
				       
				        $worksheet1->write($line,21,$result->dateOfBirth);
				       array_push($messagetxt, $result->dateOfBirth. "\t");    	
				        	
					    $worksheet1->write($line,22,$result->gender);
					    array_push($messagetxt, $result->gender. "\t");	 

					    $worksheet1->write($line,23,$result->notes);
					    array_push($messagetxt, $result->notes. "\t");	 
					    
					    $worksheet1->write($line,24,$result->dietaryRequirements);
					    array_push($messagetxt, $result->dietaryRequirements. "\t");	 
					    
					    $worksheet1->write($line,25,$result->disabilities);
					    array_push($messagetxt, $result->disabilities. "\t");	 
					    
					    $worksheet1->write($line,26,$result->dinner);
					    array_push($messagetxt, $result->dinner. "\t");	 
					    
					    $worksheet1->write($line,27,$result->transport);
					    array_push($messagetxt, $result->transport. "\n");	 
					    
				    $line++;        	
			}
			$_eventName = str_replace('(','',$eventName);
			$_eventName = str_replace(')','',$_eventName);
			$_eventName = str_replace(',','',$_eventName);
			$_eventName = str_replace(' ','_',$_eventName);
			$_eventName = str_replace('-','_',$_eventName);
			$_eventName = str_replace('?','',$_eventName);
			$_eventName = str_replace('&#40;','',$_eventName);
			$_eventName = str_replace('&#41;','',$_eventName);
			
	  	$workbook->close();
			$newreport = fopen($xls_workbook_file,"r") or die ("Couldn't open requested file");
		  $filecontents = fread($newreport,filesize($xls_workbook_file));
		    
		  header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=Event-booking_$_eventName$datestamp.xls");
			header('Cache-Control: private, max-age=0, must-revalidate');
			header('Pragma: public');
			
			

			echo $filecontents;
			fclose($newreport);
			unlink($xls_workbook_file);
			exit;
	  	return;
   }
}