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

$GLOBALS['TL_LANG']['MSC']['adminSub']     = 'New registration on %s';
$GLOBALS['TL_LANG']['MSC']['emailSub']     = 'Your registration on %s';
$GLOBALS['TL_LANG']['MSC']['emailTxt']    = 'Thank you for your registration on ##domain##.##\n####\n##Please click ##link## to login to your account. ##\n##If you did not request an account, please ignore this e-mail.##\n####\n##This is an automated email please do not reply.##\n##';
$GLOBALS['TL_LANG']['MSC']['adminTxt'] = 'A new member (ID %s) has registered at your website.%sThis is an automated email please do not reply.';


/**
 * Payment modules
 */
$GLOBALS['TL_LANG']['PAYE']['paypal']			= array('PayPal Standard Checkout', 'PayPal standard payments).');
$GLOBALS['TL_LANG']['PAYE']['paypalexpress']	= array('PayPal Express', 'PayPal Express payments');
$GLOBALS['TL_LANG']['PAYE']['paypalpro']	= array('PayPal Payments Pro', 'PayPal Payments Pro payments');


/**
 * Credit Cards for PayPal Pro
 */
$GLOBALS['TL_LANG']['calevntbooking']['MasterCard']					= 'MasterCard';
$GLOBALS['TL_LANG']['calevntbooking']['Visa']								= 'Visa';
$GLOBALS['TL_LANG']['calevntbooking']['American Express']		= 'American Express';
$GLOBALS['TL_LANG']['calevntbooking']['Discover']						= 'Discover';
$GLOBALS['TL_LANG']['calevntbooking']['Maestro']						= 'Maestro';



// Event Survey

$GLOBALS['TL_LANG']['calevntbooking']['event_date'] = 'Date';
$GLOBALS['TL_LANG']['calevntbooking']['event_title'] = 'Title';
$GLOBALS['TL_LANG']['calevntbooking']['event_survey'] = 'Survey';
$GLOBALS['TL_LANG']['calevntbooking']['event_cert'] = 'Certificate';

?>