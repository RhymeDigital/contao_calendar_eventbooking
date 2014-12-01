<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

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
 * Table tl_event_registration
 */
$GLOBALS['TL_DCA']['tl_event_registration'] = array
(
	
	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'onload_callback' 			  => array
		(
			array('tl_event_registration', 'checkPermission'),
		),
		'ondelete_callback' => array
		(
			array('tl_event_registration', 'updateSpaces')
		),
		'onsubmit_callback' => array
		(
			array('tl_event_registration', 'decrementSpaces')
		),
			'onload_callback' => array
		(
			array('tl_event_registration', 'filterEventID')
		),

	),
	
	
	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                  => 2,
			'fields'                  => array('lastname ASC'),
			'flag'                    => 1,
			'panelLayout'             => 'filter;sort,search,limit',
		),
		'label' => array
		(
			'fields'                  => array('id'),
			'label'                   => '%s',
			'label_callback'          => array('tl_event_registration', 'getEventLabel')
		),
		
		'global_operations' => array
		(
		
			'back' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['backBT'],
				'href'                => 'do=booking_export',
				'class'               => 'header_back',
				'attributes'          => 'onclick="Backend.getScrollOffset();"',
			),
),





		'operations' => array
		(
		


			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_event_registration']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_event_registration']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_event_registration']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			),
			'attended' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_event_registration']['attended'],
				'icon'                => 'member.gif',
				'button_callback'     => array('tl_event_registration', 'toggleAttended')		
			),
			
	
			
			
			'tools' => array
			(
				'label'				  => &$GLOBALS['TL_LANG']['tl_event_registration']['tools'],
				'icon'				  => 'system/modules/calendar_eventbooking/assets/tools.png',
				'attributes'          => 'class="invisible calendar_eventbooking-contextmenu"',
			),
		)

	),
	
	// Palettes
	'palettes' => array
	(
		'default'                     => '{event_legend},eventID;{form_legend},title,jobTitle,firstname,lastname,addressOne,addressTwo,city,postcode,county,phone,email,county,mobile,fax,gender,company,website,gmcNumber,dietaryRequirements,disabilities,dinner,transport,user_photo;{details_legend},notes',
	),
	
	// Fields
	'fields' => array
	(
	
		'id' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_event_registration']['id'],
			'search'				=> false,
			'sorting'				=> true,
		),
		
		'eventID' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_event_registration']['eventID'],
			'search'				=> true,
			'sorting'				=> true,
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_event_registration', 'getEvents'),
			'eval'                    => array()
		),
		
		'eventName' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_event_registration']['eventName'],
			'search'				=> true,
			'sorting'				=> true,
		),
		
		
		'title' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_event_registration']['title'],
			'search'				=> true,
			'sorting'				=> false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255)
		),
		
		
		'jobTitle' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_event_registration']['jobTitle'],
			'search'				=> true,
			'sorting'				=> false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255)
		),
		
		

		'firstname' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_event_registration']['firstname'],
			'search'				=> true,
			'sorting'				=> true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255)
		),
		
		'lastname' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_event_registration']['lastname'],
			'search'				=> true,
			'sorting'				=> true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255)
		),
		
		
		'addressOne' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_event_registration']['addressOne'],
			'search'				=> true,
			'sorting'				=> false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255)	
		),
		
		'addressTwo' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_event_registration']['addressTwo'],
			'search'				=> true,
			'sorting'				=> false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255)
		),
		
		
		'city' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_event_registration']['city'],
			'search'				=> true,
			'sorting'				=> false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255)
		),
		
	   'postcode' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_event_registration']['postcode'],
			'search'				=> true,
			'sorting'				=> false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255)
		),
		
	   'county' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_event_registration']['county'],
			'search'				=> true,
			'sorting'				=> false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255)
		),
		
	 'phone' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_event_registration']['phone'],
			'search'				=> true,
			'sorting'				=> false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255)
		),
		
	 'email' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_event_registration']['email'],
			'search'				=> true,
			'sorting'				=> false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255)
		),
	 'county' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_event_registration']['county'],
			'search'				=> true,
			'sorting'				=> false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255)
		),
		 'mobile' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_event_registration']['mobile'],
			'search'				=> true,
			'sorting'				=> false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255)
		),
		 'fax' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_event_registration']['fax'],
			'search'				=> true,
			'sorting'				=> false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255)
		),
		 'gender' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_event_registration']['gender'],
			'search'				=> true,
			'sorting'				=> false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255)
		),
	   'company' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_event_registration']['company'],
			'search'				=> true,
			'sorting'				=> true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255)
		),		
	   'website' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_event_registration']['website'],
			'search'				=> true,
			'sorting'				=> false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255)
		),
		
		
	    'gmcNumber' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_event_registration']['gmcNumber'],
			'search'				=> true,
			'sorting'				=> false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255)
		),		
		
	    'dietaryRequirements' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_event_registration']['dietaryRequirements'],
			'search'				=> true,
			'sorting'				=> false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255)
		),		
		
		
		 'disabilities' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_event_registration']['disabilities'],
			'search'				=> true,
			'sorting'				=> false,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255)
		),		
		
	    'dinner' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_event_registration']['dinner'],
			'exclude'       => true,
			'inputType'     => 'select',
			'search'				=> true,
			'sorting'				=> false,
			'options'       => array('No', 'Yes'),
			'reference'     => &$GLOBALS['TL_LANG']['tl_evtreg_mods']
		),		
		
	    'transport' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_event_registration']['transport'],
			'exclude'       => true,
			'inputType'     => 'select',
			'search'				=> true,
			'sorting'				=> false,
			'options'       => array('No', 'Yes'),
			'reference'     => &$GLOBALS['TL_LANG']['tl_evtreg_mods']
		),	
		
	    'user_photo' => array
		(
			'label'		=> &$GLOBALS['TL_LANG']['tl_event_registration']['user_photo'],
			'exclude'  => true,
			'inputType' => 'file',
			'search'				=> true,
			'sorting'				=> false,
 			'eval'      => array('extensions'=>$GLOBALS['TL_CONFIG']['uploadTypes'],'mandatory'=>false,'thisID'=>$this->Input->get('id')),
 	//		 'eval'      => array('feEditable'=>true, 'feViewable' => true, 'extensions'=>'png,jpg,jpeg,gif', 'storeFile'=>true, 'uploadFolder'=>'tl_files/files/images/photos')
 	//		'save_callback' => array( array('tl_event_registration', 'save_field'))
		),	
		
		
		'notes' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_event_registration']['notes'],
			'inputType'		   => 'textarea',
			'search'				=> true,
			'sorting'				=> false,
			'eval'					=> array('style'=>'height:80px;')
		)
	)
);


/**
 * tl_event_registration class.
 * 
 * @extends Backend
 */
class tl_event_registration extends Backend
{



		

	public function toggleAttended($row, $href, $label, $title, $icon, $attributes) {	
		
			$href .= '&amp;tid='.$row['id'].'&amp;state='.($row[attended] ? '' : '1');
			if (!$row[attended])	{ $icon = 'member_.gif'; }	
			return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	
		}
		
		
/*
  public function save_field($varValue, $dc)
    {
    	
    
  //  echo 'user_photo: ' . $_POST['user_photo']. '<br/>';
  // 	print_r($_FILES);
   print_r($_SESSION['FILES']);
    	exit;
    }
*/

public function listBookings($arrRow)
	{
	return '<div><strong>' . $arrRow['firstname'] . '</strong></div></div>' . "\n";
	}


public function getEvents (DataContainer $dc)
{
	
		$eventID = $this->Input->get('eventID');

		if ($eventID){
			// print_r( $GLOBALS['TL_DCA']['tl_event_registration']['list']['sorting']);
			$forms = $this->Database->prepare("SELECT * FROM tl_calendar_events WHERE id=?")->limit(1)->execute($eventID);
		     $registrationForms[$forms->id] = stripslashes($forms->title);
		}  else {
		
		
	 $forms = $this->Database->prepare("SELECT * FROM tl_calendar_events")
                ->execute(1);
          
          $registrationForms = array();
          
		while ($forms->next())
		        {
		        $registrationForms[$forms->id] = stripslashes($forms->title);
		        }
			
            }
            
          return $registrationForms;       
	
}
		

	public function getEventLabel($row, $label) {
	return '
<div style="float:left; width:50px">' . $row['id'] . '</div>
<div style="float:left; width:130px;">' . $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], strtotime($row['date'])) . '</div>
<div style="float:left; width:200px">' . $row['firstname'] .'&nbsp;'. $row['lastname']. '</div>';

	}
	

		
	/**
	* 
	* 
	* @access public
	* @param object $dc
	* @return void
	*/
	public function checkPermission()
	{
		
	//echo 'id: '.$this->Input->get('id');
	//	print_r($this->Input);
//	exit;

	
				
		$details = $this->Database->prepare("SELECT * FROM tl_event_registration WHERE id = ?")->limit(1)->execute($this->Input->get('id'));
	//		if ($details->numRows) {
	//				$_SESSION['user_photo'] = $details->user_photo;
	//		}

				
				if (!file_exists(TL_ROOT . '/' . $details->user_photo)) {
					//	echo "NO PHOTO: ". $details->user_photo;
					$this->Database->prepare("UPDATE tl_event_registration SET user_photo=?  WHERE id=?")->execute('', $this->Input->get('id'));
				}

		if (strlen($this->Input->get('act')))
		{
			$GLOBALS['TL_DCA']['tl_event_registration']['config']['closed'] = false;
		}
		
	}
	

 	public function decrementSpaces(DataContainer $dc) {
 		
 		
 		// Return if there is no active record (override all)
		if (!$dc->activeRecord || $dc->activeRecord->dateAdded > 0)
		{
			return;
		}
		
		$BSTOffset = +1.00;
		$GMTMySqlString = gmdate("Y-m-d H:i:s", time() + $BSTOffset * 3600);
		
		// get the event
		$event =  $this->Database->prepare("SELECT * FROM tl_calendar_events WHERE id=?")->limit(1)->execute($dc->activeRecord->eventID);   
		$this->Database->prepare("UPDATE tl_event_registration SET date=?, eventName=?  WHERE id=?")->execute($GMTMySqlString, stripslashes($event->title), $dc->id);
		
		 $spaces = $this->Database->prepare("SELECT evtreg_availableSpacesRemaining, evtreg_availableSpaces FROM tl_calendar_events WHERE id=?")->limit(1)->execute($dc->activeRecord->eventID);
		 // count the total spaces taken in this event in tl_event_registration so we can update the evtreg_availableSpacesRemaining (evtreg_availableSpaces - count in tl_event_registration)
		 $thisRegSpaces = $this->Database->prepare("SELECT id FROM tl_event_registration WHERE eventID=?")->execute($dc->activeRecord->eventID);
		 $spaces->evtreg_availableSpacesRemaining = ($spaces->evtreg_availableSpaces - $thisRegSpaces->numRows);
		 $updateEvent = $this->Database->prepare("UPDATE tl_calendar_events SET evtreg_availableSpacesRemaining=?  WHERE id=?")->execute($spaces->evtreg_availableSpacesRemaining, $dc->activeRecord->eventID);
		 $this->log('Updated number of spaces remaining on event id (decremented) eventID: '.$dc->activeRecord->eventID.' , spaces remaining: '.$spaces->evtreg_availableSpacesRemaining.'', 'tl_event_registration()', TL_GENERAL);
	
		 return;
	}
	
	

 	public function updateSpaces(DataContainer $dc) {


		$objField = $this->Database->prepare("SELECT * FROM ".$dc->table." WHERE id=?")->limit(1)->execute($dc->id);
	    $spaces = $this->Database->prepare("SELECT evtreg_availableSpaces FROM tl_calendar_events WHERE id=?")->limit(1)->execute($objField->eventID);		
		 // count the total spaces taken in this event in tl_event_registration so we can update the evtreg_availableSpacesRemaining (evtreg_availableSpaces - count in tl_event_registration)
		 $thisRegSpaces = $this->Database->prepare("SELECT id FROM tl_event_registration WHERE eventID=?")->execute($objField->eventID);
		 $spaces->evtreg_availableSpacesRemaining = ($spaces->evtreg_availableSpaces - $thisRegSpaces->numRows) +1;
		 $updateEvent = $this->Database->prepare("UPDATE tl_calendar_events SET evtreg_availableSpacesRemaining=?  WHERE id=?")->execute($spaces->evtreg_availableSpacesRemaining, $objField->eventID);		
		 $this->log('Updated number of spaces remaining on event id (incremented), eventID: '.$dc->activeRecord->eventID.' , spaces remaining: '.$spaces->evtreg_availableSpacesRemaining.'', 'tl_event_registration()', TL_GENERAL);
		
		return;
	}
	
	
	public function filterEventID(DataContainer $dc) {

	$eventID = $this->Input->get('eventID');

		if ($eventID){
		$GLOBALS['TL_DCA']['tl_event_registration']['list']['sorting']['filter'] = array(array('eventID=?', $eventID));
	//	print_r( $GLOBALS['TL_DCA']['tl_event_registration']['list']['sorting']);
		}
		
		
		
	if($this->Input->get('tid')) {

				$this->Database->prepare('UPDATE tl_event_registration SET attended =? WHERE id=?')->execute($this->Input->get('state')=='1'?'1':'', $this->Input->get('tid'));		
				/*
				if ($this->Input->get('state')== 1) {
					echo  'To do: Prompt -> Send email to user with generic link to the page with email input -> Get events attended for user -> List -> Take survey -> Download certificate';
				}
				*/
			}

		
		
		return;
	}
	
	
	

	
}

