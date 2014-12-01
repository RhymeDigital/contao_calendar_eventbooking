<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 *
 * The TYPOlight webCMS is an accessible web content management system that 
 * specializes in accessibility and generates W3C-compliant HTML code. It 
 * provides a wide rec_interval of functionality to develop professional websites 
 * including a built-in search engine, form generator, file and user manager, 
 * CSS engine, multi-language support and many more. For more information and 
 * additional TYPOlight applications like the TYPOlight MVC Framework please 
 * visit the project website http://www.typolight.org.
 *
 * This is the data container array that extends the table tl_calendar_events.
 *
 * PHP version 5
 * @copyright  360fusion  2011
 * @author     Darrell Martin <darrell@360fusion.co.uk>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


/**
 * Table tl_calendar_events (extended)
 */
 
 

 
 array_insert($GLOBALS['TL_DCA']['tl_calendar_events']['config']['onsubmit_callback'], 1,array(array('evtreg_eventfunctions', 'saveFields')));
 


 array_insert($GLOBALS['TL_DCA']['tl_calendar_events']['fields'], 0, array
		(
	
				'eventselectpageid' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['eventselectpageid'],
	     	'inputType'    					=> 'pageTree',
        'eval'                  => array('multiple'=>true, 'fieldType'=>'checkbox', 'tl_class'=>'clr hide_sort_hint'),
			  'sql'                   => "blob NULL",
			),
	
		)
	);



array_insert($GLOBALS['TL_DCA']['tl_calendar_events']['list']['operations'], 3, array
	(
			'evtreg_group' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_group'],
				// send to tl_formstorage table editing
				'href'                => 'do=form&table=tl_form_auto',
				'icon'                => 'system/modules/calendar_eventsregistration/assets/eventsattend.gif',
			)
	)
);



 
 
 
// Selector type
$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['__selector__'][] = 'evtreg_certificate';
$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default'] = '{evtreg_legend_certificate},evtreg_certificate;'. $GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default'];
array_insert($GLOBALS['TL_DCA']['tl_calendar_events']['subpalettes'], 2, array
	(
	'evtreg_certificate'	=> 'evtreg_certificate_select,evtreg_certificate_coursename,evtreg_certificate_location,evtreg_certificate_coursedates,evtreg_certificate_preview',
	)
);


// Selector type
$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['__selector__'][] = 'evtreg_survey';
$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default'] = '{evtreg_legend_survey},evtreg_survey;'. $GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default'];
array_insert($GLOBALS['TL_DCA']['tl_calendar_events']['subpalettes'], 2, array
	(
	'evtreg_survey'	=> 'evtreg_survey_select',
	)
);


// Selector type
$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['__selector__'][] = 'evtreg_register';
$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default'] = '{evtreg_legend_register},evtreg_register;'. $GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default'];
// Insert new subpalettes after position 2
array_insert($GLOBALS['TL_DCA']['tl_calendar_events']['subpalettes'], 2, array
	(
	'evtreg_register'	=> 'evtreg_lastDate,evtreg_availableSpaces,evtreg_cost,evtreg_registrationFields,evtreg_registrationMand,evtreg_paymentTypes',
	)
);



 $GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default'] = '{eventselectentry_legend},eventselectpageid;'. $GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default'];




// Insert new Fields after position 12
array_insert($GLOBALS['TL_DCA']['tl_calendar_events']['fields'], 12, array
	(

		'evtreg_register' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_register'],
			'exclude'                 => true,
			'default'                 => '',
			'inputType'               => 'checkbox',
			'filter'                  => true,
			'eval'                    => array('submitOnChange'=>true)
		),
		
			'evtreg_formHeadline' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_formHeadline'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'w10'),
		),
		
		'evtreg_availableSpaces' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_availableSpaces'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true,'rgxp'=>'digit', 'tl_class'=>'w10'),
		),

		'evtreg_lastDate' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_lastDate'],
			'exclude'                 => true,
			'default'                 => time(),
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true,'maxlength'=>10, 'rgxp'=>'date', 'datepicker'=>$this->getDatePickerString(16))
		),
		
		'evtreg_cost' => array
		(
			'label'         => &$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_cost'],
			'exclude'       => true,
			'inputType'     => 'text',
			'eval'          => array('mandatory'=>false, 'tl_class'=>'w10')
		),

		  'evtreg_registrationFields' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_registrationFields'],
			'exclude'                 => true,
			'inputType'               => 'checkboxWizard',
			'options_callback'        => array('evtreg_eventfunctions', 'getFormFields'),
			'eval'                    => array('multiple'=> true, 'mandatory'=> false),
			'save_callback' => array(array('evtreg_eventfunctions', 'saveRegistrationFields'),),
		),
		
		  'evtreg_registrationMand' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_registrationMand'],
			'exclude'                 => true,
			'inputType'               => 'checkboxWizard',
			'options_callback'        => array('evtreg_eventfunctions', 'getFormFieldMand'),
			'eval'                    => array('multiple'=> true, 'mandatory'=> false),
			'sql' 										=> "blob NULL",
			'save_callback' => array(array('evtreg_eventfunctions', 'saveRegistrationMand'),),
		),
		
		
		  'evtreg_paymentTypes' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_paymentTypes'],
			'exclude'                 => true,
			'inputType'               => 'checkboxWizard',
			'options_callback'        => array('evtreg_eventfunctions', 'getpaymentTypes'),
			'eval'                    => array('multiple'=> true, 'mandatory'=> false),
			'sql' 										=> "blob NULL",
			'save_callback' => array(array('evtreg_eventfunctions', 'savepaymentTypes'),),
		),
		
		
		


			'evtreg_survey' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_survey'],
			'exclude'                 => true,
			'default'                 => '',
			'inputType'               => 'checkbox',
			'filter'                  => false,
			'eval'                    => array('submitOnChange'=>true)
		),
		
		
			'evtreg_survey_select' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_survey_select'],
			'search'				=> true,
			'sorting'				=> true,
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('evtreg_eventfunctions', 'getSurveys'),
			'eval'                    => array()
		),
		
		
		
		'evtreg_certificate' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_certificate'],
			'exclude'                 => true,
			'default'                 => '',
			'inputType'               => 'checkbox',
			'filter'                  => false,
			'eval'                    => array('submitOnChange'=>true)
		),
		
		
			'evtreg_certificate_select' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_certificate_select'],
			'search'				=> true,
			'sorting'				=> true,
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('evtreg_eventfunctions', 'getCertificates'),
			'eval'                    => array()
		),
		
		
			'evtreg_certificate_coursename' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_certificate_coursename'],
			'exclude'                 => true,
			'inputType'			=> 'textarea',
			'eval'			=> array('tl_class'=>'w80','style'=>'height:15px')
		),
		
		
			'evtreg_certificate_location' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_certificate_location'],
			'exclude'                 => true,
			'inputType'			=> 'textarea',
			'eval'			=> array('tl_class'=>'w80','style'=>'height:15px')
		),
		
			'evtreg_certificate_coursedates' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_certificate_coursedates'],
			'exclude'                 => true,
			'inputType'			=> 'textarea',
			'eval'			=> array('tl_class'=>'w80','style'=>'height:15px')
		),
		
			'evtreg_certificate_preview' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_certificate_preview'],
			'exclude'                 => true,
			'inputType'			=> 'text',
			'eval'			=> array('doNotShow'=>true,'disabled'=>'disabled'),
		),


		
	)
);



class evtreg_eventfunctions extends Backend
{
	

		public function getSurveys (DataContainer $dc) {
			
				$survey = array();
				$surveys = $this->Database->prepare("SELECT * FROM tl_survey")->execute();

				while ($surveys->next())
				        {
				        $survey[$surveys->id] = stripslashes($surveys->title);
				        }
		          return $survey;       

		}
		
		
		public function getCertificates (DataContainer $dc) {
			
			$directory = "../system/modules/calendar_eventbooking/assets/certificates/";
		    $results = array();
		    $handler = opendir($directory);
			   // open directory and walk through the filenames
			   while ($file = readdir($handler)) {
					      // if file isn't this directory or its parent, add it to the results
					      if ($file != "." && $file != "..") {
					        $results[] = $file;
					      }
			    }
    closedir($handler);


			   return $results;

	   }
	
	
	
	/**
	 * Get all getFormFields fields and return them as array
	 * @return array
	 */
	public function getFormFields(DataContainer $dc) {
		
		$fields = array();
		$objFields = $this->Database->prepare("SHOW COLUMNS FROM tl_event_registration")
		->execute();
		while ($objFields->next())
		{
			if (((((($objFields->Field != 'id') && ($objFields->Field != 'eventID') && ($objFields->Field != 'tstamp') && ($objFields->Field != 'eventName') && ($objFields->Field != 'date')  && ($objFields->Field != 'notes')))))){
			 array_push($fields, $objFields->Field);
			}
		}
		return $fields;
	}
	
	
		
	public function getFormFieldMand(DataContainer $dc) {
		$thisDataArray = array();
		$thisData = $this->Database->prepare("SELECT evtreg_registrationFields FROM tl_calendar_events WHERE id=?")->execute($dc->id);
	  $thisDataArray = deserialize($thisData->evtreg_registrationFields);
		return $thisDataArray;       
	}
			
			
			
	public function getpaymentTypes(DataContainer $dc) {
		$thisDataArray = array();
		$thisData = $this->Database->prepare("SELECT id,name FROM tl_evtreg_mods WHERE enabled = 1")->execute();
		if ($thisData->numRows > 0){
	  			while ($thisData->next()) 	
				        {
				        $thisDataArray[$thisData->id] = stripslashes($thisData->name); 
				        } 
		}

		return $thisDataArray;       
	}	
	

	
	


	 public function saveFields()  {
	 	
	 	
	 		 $spaces = $this->Database->prepare("SELECT evtreg_availableSpacesRemaining, evtreg_availableSpaces FROM tl_calendar_events WHERE id=?")->limit(1)->execute($this->Input->get('id'));
         
        if (($spaces->evtreg_availableSpacesRemaining == '') && ($_POST[evtreg_register] == 1)){	 
        	$this->Database->prepare("UPDATE tl_calendar_events SET evtreg_availableSpacesRemaining=? WHERE id=?")->execute($_POST[evtreg_availableSpaces], $this->Input->get('id'));
       }

			// if remaining spaces is greater than the total spaces (varValue) then set remaining spaces to total spaces
		   if ($spaces->evtreg_availableSpacesRemaining > $_POST[evtreg_availableSpaces]) {	
		   		$this->Database->prepare("UPDATE tl_calendar_events SET evtreg_availableSpacesRemaining=? WHERE id=?")->execute($_POST[evtreg_availableSpaces], $this->Input->get('id'));
		  }
		  
		  	// count the total spaces taken in this event in tl_event_registration so we can update the evtreg_availableSpacesRemaining (evtreg_availableSpaces - count in tl_event_registration)
		     $thisRegSpaces = $this->Database->prepare("SELECT id FROM tl_event_registration WHERE eventID=?")->execute($this->Input->get('id'));
		     $spaces->evtreg_availableSpacesRemaining = ($spaces->evtreg_availableSpaces - $thisRegSpaces->numRows);
		     $updateEvent = $this->Database->prepare("UPDATE tl_calendar_events SET evtreg_availableSpacesRemaining=?, title=? WHERE id=?")->execute($spaces->evtreg_availableSpacesRemaining, stripslashes($_POST[title]), $this->Input->get('id'));
		    
		     		


		// since all registration events have this field we can add this event into the tl_evtreg_export table...
		  $event = $this->Database->prepare("SELECT id, title FROM tl_calendar_events WHERE id=?")->limit(1)->execute($this->Input->get('id'));
         $eventExists = $this->Database->prepare("SELECT id FROM tl_evtreg_export WHERE pid=?")->limit(1)->execute($this->Input->get('id'));
       	if ($eventExists->numRows < 1)
				{
					// insert the event into the database

								$eventfields['pid'] = $event->id;
								$eventfields['eventTitle'] = stripslashes($_POST['title']);
								$newEvents = $this->Database->prepare("INSERT INTO tl_evtreg_export %s")->set($eventfields)->execute();
				}
				

					// update in case the eventTitle is missing
								$eventfields['pid'] = $event->id;
								$eventfields['eventTitle'] = stripslashes($_POST['title']);
								$updateEvent = $this->Database->prepare("UPDATE tl_evtreg_export SET eventTitle=?  WHERE pid=?")->execute(stripslashes($eventfields['eventTitle']),$eventfields['pid']);
								
								// rename eventName's in tl_event_registration if the event title has changed
								

								
								
					$thisNamed = $this->Database->prepare("SELECT eventID, eventName FROM tl_event_registration WHERE eventID=?")->limit(1)->execute($this->Input->get('id'));
					if ($thisNamed->numRows > 0)	{
					//	echo stripslashes($event->title).'<br/>';
					//	echo $thisNamed->eventName.'<br/>';
								if (stripslashes($event->title) != $thisNamed->eventName) {
									 $thisEventTitles = $this->Database->prepare("SELECT id FROM tl_event_registration WHERE eventID=?")->execute($event->id);
										 if ($thisEventTitles->numRows >0){
										 		while ($thisEventTitles->next())
										 		{
										 			$updateEventTitle = $this->Database->prepare("UPDATE tl_event_registration SET eventName=?  WHERE id=?")->execute(stripslashes($_POST['title']),$thisEventTitles->id);
										 		}
										}
									
								}
					}		
	 	
			return;
	 }


	 public function saveRegistrationFields($varValue, DataContainer $dc)  {
	 	
		$this->Database->prepare("UPDATE tl_calendar_events SET evtreg_registrationFields=? WHERE id=?")->execute($varValue, $dc->id);
			return $varValue;
	}


	 public function saveRegistrationMand($varValue, DataContainer $dc)  {
	 	
		$this->Database->prepare("UPDATE tl_calendar_events SET evtreg_registrationMand=? WHERE id=?")->execute($varValue, $dc->id);
			return $varValue;
	}
	
	
	 public function savepaymentTypes($varValue, DataContainer $dc)  {
	 	
		$this->Database->prepare("UPDATE tl_calendar_events SET evtreg_paymentTypes=? WHERE id=?")->execute($varValue, $dc->id);
			return $varValue;
	}




	 public function loadCategories(DataContainer $dc)  {
			 
			 	// first determine the event category type:
			 	
	 	
    $thisEvent = $this->Database->prepare("SELECT pid FROM tl_calendar_events WHERE id=?")->execute($dc->id);
    $thisCal = $this->Database->prepare("SELECT title FROM tl_calendar WHERE id=?")->execute($thisEvent->pid);
		$objParents = $this->Database->prepare("SELECT id, title FROM tl_calendar") ->execute();
			
			if (!$objParents->numRows) { 		  
					return;
			}

			$parents = array();
			while($objParents->next())
			{
				$parents[$objParents->id][title] = $objParents->title;
			}

	  	$cat = $this->Database->prepare("SELECT id FROM tl_page WHERE title=?")->limit(1)->execute($thisCal->title);
														
									 $arrCat = array();
								    $objCat = $this->Database->prepare("SELECT id, title FROM tl_page WHERE pid=?")->execute($cat->id);
								    
								  	$i = 0;
									    while ($objCat->next())
									    {
									    	 $arrCat[$objCat->id] = $objCat->title;
									        $i++;
									    }
								    return $arrCat;
				 }
				 
  


}

?>