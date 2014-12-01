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



/*
 * Front end modules
 */
array_insert($GLOBALS['FE_MOD']['events'], 3, array
	(
		'eventsregistration' => 'ModuleEventsRegistration',
		'eventsreader' => 'ModuleEventsReader',
		'eventpaymentcheckout' => 'ModuleEventsPaymentCheckout',
		'eventpaymentsuccess' => 'ModuleEventsPaymentSuccess',
		'eventpaymenterror' => 'ModuleEventsPaymentError',
		'eventsurveylogin' => 'ModuleEventsSurveyLogin',
		'eventsurveylister' => 'ModuleEventsSurveyLister',
		'eventsurveyreader' => 'ModuleEventsSurveyReader',
		'eventfilterlist'   => 'ModuleEventFilterlist',
	)
);



/*
 * Back end modules
 */
 
 if (!is_array($GLOBALS['BE_MOD']['calendar_eventbooking']))
{
	array_insert($GLOBALS['BE_MOD'], 1, array('calendar_eventbooking' => array()));
}
 
 
array_insert($GLOBALS['BE_MOD']['calendar_eventbooking'], 1, array
(


	'booking_setup' => array
	(
		'callback'					=> 'ModuleEventsBookingSetup',
		'tables'					=> array('tl_evtreg_config', 'tl_evtreg_mail', 'tl_evtreg_mail_content','tl_evtreg_mods', 'tl_evtreg_types'),
		'icon'						=> 'system/modules/calendar_eventbooking/assets/icon-setup.png',
		'stylesheet'				=> 'system/modules/calendar_eventbooking/assets/backend.css',

	),
	
	
		'booking_export' => array
	(
		'tables'					=> array('tl_evtreg_export'),
		'icon'				=> 'system/modules/calendar_eventbooking/assets/export.gif',
		'stylesheet'				=> 'system/modules/calendar_eventbooking/assets/backend.css',
		'javascript'				=> 'system/modules/calendar_eventbooking/assets/backend.js',
		'export_all_events'     	=> array('export_to_excel', 'export'),
		'attend'     	        => array('list_attendees', 'lister'),
		'event'     	       	 => array('edit_event', 'lister'),
		'xls'      				 => array('export_to_excel', 'export'),
		'pdf'      				 => array('export_to_pdf', 'export'),
		'pdflabels'      		 => array('exportLabels_to_pdf', 'export'),
		'mail'      				 => array('bulk_email', 'export'),
	),
	
	
		'booking_report' => array
	(
		'tables'					=> array('tl_event_registration'),
		'icon'				=> 'system/modules/calendar_eventbooking/assets/icon-statistics.gif',
		'stylesheet'				=> 'system/modules/calendar_eventbooking/assets/backend.css',
		'javascript'				=> 'system/modules/calendar_eventbooking/assets/backend.js',
	),

	
));



// Callback is only used for overview screen
if ($_GET['do'] == 'booking_setup' && strlen($_GET['table']))
{
	unset($GLOBALS['BE_MOD']['calendar_eventbooking']['booking_setup']['callback']);
}




$GLOBALS['EVTREG_MOD'] = array
(
	'config' => array
	(
		'mods' => array
		(
	       'tables'					=> array('tl_evtreg_mods', 'tl_evtreg_types'),
			'icon'						=> 'system/modules/calendar_eventbooking/assets/icon-payment.png',
		),
		'event_mail' => array
		(
			'tables'					=> array('tl_evtreg_mail', 'tl_evtreg_mail_content'),
			'icon'						=> 'system/modules/calendar_eventbooking/assets/icon-mail.gif',
		),
	
		'configs' => array
		(
			'tables'					=> array('tl_evtreg_config'),
			'icon'						=> 'system/modules/calendar_eventbooking/assets/icon-setup.png',
		),
	
	/*	
		'export' => array
		(
			'tables'					=> array('tl_evtreg_export'),
			'icon'						=> 'system/modules/calendar_eventbooking/assets/export.png',
		),
		
*/
	)
);


/**
 * Payment modules
 */
$GLOBALS['EVTPREG_MOD']['paypal'] = 'PayPal_standard';
$GLOBALS['EVTPREG_MOD']['paypalexpress'] = 'PayPal_ipn';
$GLOBALS['EVTPREG_MOD']['paypalpro']	= 'PayPal_pro';



/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['outputBackendTemplate'][] = array('calendar_eventbooking_hooks', 'calendar_eventbooking_OutputBackendTemplate'); 

 


?>
