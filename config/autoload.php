<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Calendar_eventbooking
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Hooks
	'calendar_eventbooking_hooks'        => 'system/modules/calendar_eventbooking/hooks/calendar_eventbooking_hooks.php',

	// Libs
	'Format'                             => 'system/modules/calendar_eventbooking/libs/Format.php',
	'nusoap'                             => 'system/modules/calendar_eventbooking/libs/nusoap/nusoap.php',
	'OLEwriter'                          => 'system/modules/calendar_eventbooking/libs/OLEwriter.php',
	'Parser'                             => 'system/modules/calendar_eventbooking/libs/Parser.php',
	'PDF_Label'                          => 'system/modules/calendar_eventbooking/libs/PDF_Label.php',
	'Workbook'                           => 'system/modules/calendar_eventbooking/libs/Workbook.php',
	'Worksheet'                          => 'system/modules/calendar_eventbooking/libs/Worksheet.php',
	
	
	// Modules
	'Contao\ModuleEventsBookingSetup'    => 'system/modules/calendar_eventbooking/modules/be/ModuleEventsBookingSetup.php',
	'Contao\bulk_email'                  => 'system/modules/calendar_eventbooking/modules/classes/bulk_email.php',
	'Contao\edit_event'                  => 'system/modules/calendar_eventbooking/modules/classes/edit_event.php',
	'Contao\exportLabels_to_pdf'         => 'system/modules/calendar_eventbooking/modules/classes/exportLabels_to_pdf.php',
	'Contao\export_to_excel'             => 'system/modules/calendar_eventbooking/modules/classes/export_to_excel.php',
	'Contao\export_to_pdf'               => 'system/modules/calendar_eventbooking/modules/classes/export_to_pdf.php',
	'Contao\HTTPRequest'                 => 'system/modules/calendar_eventbooking/modules/classes/HTTPRequest.php',
	'Contao\list_attendees'              => 'system/modules/calendar_eventbooking/modules/classes/list_attendees.php',
	'Contao\PayPal_ipn'                  => 'system/modules/calendar_eventbooking/modules/classes/PayPal_ipn.php',
	'Contao\PayPal_pro'                  => 'system/modules/calendar_eventbooking/modules/classes/PayPal_pro.php',
	'Contao\PayPal_standard'             => 'system/modules/calendar_eventbooking/modules/classes/PayPal_standard.php',
	'Contao\PDTRequest'                  => 'system/modules/calendar_eventbooking/modules/classes/PDTRequest.php',
	'Contao\ModuleEventsPaymentCheckout' => 'system/modules/calendar_eventbooking/modules/fe/ModuleEventsPaymentCheckout.php',
	'Contao\ModuleEventsPaymentError'    => 'system/modules/calendar_eventbooking/modules/fe/ModuleEventsPaymentError.php',
	'Contao\ModuleEventsPaymentSuccess'  => 'system/modules/calendar_eventbooking/modules/fe/ModuleEventsPaymentSuccess.php',
	'Contao\ModuleEventsReader'          => 'system/modules/calendar_eventbooking/modules/fe/ModuleEventsReader.php',
	'Contao\ModuleEventsRegistration'    => 'system/modules/calendar_eventbooking/modules/fe/ModuleEventsRegistration.php',
	'Contao\ModuleEventsSurveyLister'    => 'system/modules/calendar_eventbooking/modules/fe/ModuleEventsSurveyLister.php',
	'Contao\ModuleEventsSurveyLogin'     => 'system/modules/calendar_eventbooking/modules/fe/ModuleEventsSurveyLogin.php',
	'Contao\ModuleEventsSurveyReader'    => 'system/modules/calendar_eventbooking/modules/fe/ModuleEventsSurveyReader.php',
  'Contao\ModuleEventFilterlist'  		 => 'system/modules/calendar_eventbooking/modules/be/ModuleEventFilterlist.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'be_evtreg_setup'                      => 'system/modules/calendar_eventbooking/templates',
	'event_registration'                   => 'system/modules/calendar_eventbooking/templates',
	'mod_eventpaymentcheckout'             => 'system/modules/calendar_eventbooking/templates',
	'mod_eventpaymenterror'                => 'system/modules/calendar_eventbooking/templates',
	'mod_eventpaymentreturn'               => 'system/modules/calendar_eventbooking/templates',
	'mod_eventpaymentsuccess'              => 'system/modules/calendar_eventbooking/templates',
	'mod_eventpaymentsuccessNEW'           => 'system/modules/calendar_eventbooking/templates',
	'mod_eventsregistrationform'           => 'system/modules/calendar_eventbooking/templates',
	'mod_eventsregistrationform_tableless' => 'system/modules/calendar_eventbooking/templates',
	'mod_eventssurveylister'               => 'system/modules/calendar_eventbooking/templates',
	'mod_eventssurveylogin'                => 'system/modules/calendar_eventbooking/templates',
	'mod_eventfilterlist'  								 => 'system/modules/calendar_eventbooking/templates',
	'event_upcoming_filter'  							 => 'system/modules/calendar_eventbooking/templates',
	'event_upcoming_filter_side'  				 => 'system/modules/calendar_eventbooking/templates',
	'mod_eventfilterlist'  								 => 'system/modules/calendar_eventbooking/templates',
	
));
