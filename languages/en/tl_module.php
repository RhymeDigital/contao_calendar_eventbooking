<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
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
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Calendar
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
 
// events reader
$GLOBALS['TL_LANG']['tl_module']['cal_eventregistrationpage']     = array('Event booking registration page', 'Please select the page that contains to the event registration module.');
// events registration
$GLOBALS['TL_LANG']['tl_module']['evtreg_success_jumpTo'] = array('Event booking registration successful', 'Event booking registration successful. Point this to the success page.');
$GLOBALS['TL_LANG']['tl_module']['evtreg_error_jumpTo'] = array('Event booking registration error', 'Event booking registration error. Point this to the error page.');
$GLOBALS['TL_LANG']['tl_module']['evtreg_template'] = array('Event booking registration template', 'Template');
$GLOBALS['TL_LANG']['tl_module']['evtregsuccess_template'] = array('Event booking registration payment success template', 'Select a template');
$GLOBALS['TL_LANG']['tl_module']['evtregerror_template'] = array('Event booking registration payment error template', 'Select a template');
$GLOBALS['TL_LANG']['tl_module']['evtregcheckout_template'] = array('Event booking registration payment checkout template', 'Select a template');





// events survey login 
$GLOBALS['TL_LANG']['tl_module']['evtsurveys_jumpTo'] = array('Event booking surveys lister page', 'Please select the page that contains to the event surveys lister module.');

// events survey lister 
$GLOBALS['TL_LANG']['tl_module']['evtsurveysreader_jumpTo'] = array('Event booking surveys reader page', 'Please select the page that contains to the event surveys reader module.');




/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_module']['registration_legend']     = 'Event Booking Registration';
$GLOBALS['TL_LANG']['tl_module']['survey_legend']     = 'Event Surveys';

?>