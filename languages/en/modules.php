<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

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
 * Language file for modules (en).
 *
 * PHP version 5
 * @copyright  360fusion  2011
 * @author     Darrell Martin <darrell@360fusion.co.uk>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


/**
 * Back end modules
 */
 $GLOBALS['TL_LANG']['MOD']['calendar_eventbooking']	= 'Event Booking';
$GLOBALS['TL_LANG']['MOD']['booking_report'] = array('All Attendees', 'Displays booking information');
$GLOBALS['TL_LANG']['MOD']['booking_setup'] = array('Booking Configuration', 'Displays booking configuration');
$GLOBALS['TL_LANG']['MOD']['booking_export'] = array('Booking Management', 'Export booked events');
/**
 * Front end modules
 */
$GLOBALS['TL_LANG']['FMD']['eventsreader']	= array('Event Booking Reader', 'Adds the booking item reader to the page.');  
$GLOBALS['TL_LANG']['FMD']['eventsregistration']	= array('Event Booking Registration', 'Adds the booking registration to the page.'); 
$GLOBALS['TL_LANG']['FMD']['eventpaymentcheckout']	= array('Event Booking Payment Checkout', 'Adds the booking payment to the page.'); 
$GLOBALS['TL_LANG']['FMD']['eventpaymentsuccess']	= array('Event Booking Payment Success', 'Adds the booking payment success to the page.'); 
$GLOBALS['TL_LANG']['FMD']['eventpaymenterror']	= array('Event Booking Payment Error', 'Adds the booking payment error to the page.'); 
$GLOBALS['TL_LANG']['FMD']['eventsurveylogin']	= array('Event Booking Surveys Login', 'Adds the booking surveys login to the page.'); 
$GLOBALS['TL_LANG']['FMD']['eventsurveylister']	= array('Event Booking Surveys Lister', 'Adds the booking surveys lister to the page.'); 
$GLOBALS['TL_LANG']['FMD']['eventsurveyreader']	= array('Event Booking Surveys Reader', 'Adds the booking surveys reader to the page.'); 
$GLOBALS['TL_LANG']['FMD']['eventfilterlist']   = array('Event Filter List', 'Adds a list of filtered events to the page.');


/**
 * Isotope Modules
 */
$GLOBALS['TL_LANG']['EVENTREG']['config_module']		= 'Event Booking Configuration';
$GLOBALS['TL_LANG']['EVTREG']['config']					= 'General settings';
$GLOBALS['TL_LANG']['EVTREG']['mods']				= array('Payment manager', 'Configure payment types.');
$GLOBALS['TL_LANG']['EVTREG']['event_mail']		= array('Email template manager','Customise notification emails.');
$GLOBALS['TL_LANG']['EVTREG']['configs']				= array('Configuration manager', 'Configure general settings.');
// $GLOBALS['TL_LANG']['EVTREG']['export']				= array('Export manager', 'Export event bookings to excel.');


?>
