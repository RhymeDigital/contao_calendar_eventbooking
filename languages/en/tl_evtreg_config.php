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
 * Fields
 */
$GLOBALS['TL_LANG']['tl_evtreg_config']['name']			 = array('Configuration name', '');
$GLOBALS['TL_LANG']['tl_evtreg_config']['label']				 = array('Label','');
$GLOBALS['TL_LANG']['tl_evtreg_config']['adminEmail']	 = array('Admin email address', 'This is the email address to send all administrative emails.');
$GLOBALS['TL_LANG']['tl_evtreg_config']['emailRegVerification']	 = array('Send email to attendee? ', 'Send an email to the attendee?.');
$GLOBALS['TL_LANG']['tl_evtreg_config']['emailAdminVerification']	 = array('Send email to admin? ', 'Send an email to the admin with a verification link to the booking user\'s details.');
$GLOBALS['TL_LANG']['tl_evtreg_config']['emailRegTemplate']	 = array('User template selection', 'This is the email template to use for attendee');
$GLOBALS['TL_LANG']['tl_evtreg_config']['emailAdminTemplate']	 = array('Admin template selection',  'This is the email template to use for admins');

$GLOBALS['TL_LANG']['tl_evtreg_config']['payment'] = array('Use a payment gateway?',  'Select if you wish to use a payment method when registering');
$GLOBALS['TL_LANG']['tl_evtreg_config']['paymentType']	 = array('Select a payment type',  'Select a payment type');
$GLOBALS['TL_LANG']['tl_evtreg_config']['api_username']   = array('API username', 'Link to the paypal API username. Required for Paypal Pro.');
$GLOBALS['TL_LANG']['tl_evtreg_config']['api_password']   = array('API password', 'Link to the paypal API password. Required for Paypal Pro.');
$GLOBALS['TL_LANG']['tl_evtreg_config']['api_signature']  = array('Paypal Signature', 'Link to the paypal signature. Required for Paypal Pro.');
$GLOBALS['TL_LANG']['tl_evtreg_config']['paypal_standard']  = array('Paypal Standard', 'Link to the paypal standard account\'s email address. Required for Paypal Standard.');
$GLOBALS['TL_LANG']['tl_evtreg_config']['environment'] = array('Paypal Environment', 'Select whether this is a live or test environment.');
$GLOBALS['TL_LANG']['tl_evtreg_config']['payment_type'] = array('Paypal Payment type', 'Determine when to charge the buyer\'s PayPal or credit card account.');
$GLOBALS['TL_LANG']['tl_evtreg_config']['currency_code'] = array('Paypal Currency code', 'The 3 alpha currency code that represents the currency used for the payment.');
$GLOBALS['TL_LANG']['tl_evtreg_config']['checkout_jumpTo'] = array('Payment checkout', 'Payment checkout. Point this to the checkout module page.');
$GLOBALS['TL_LANG']['tl_evtreg_config']['success_jumpTo'] = array('Payment successful', 'Payment successful. Point this to the success module page.');
$GLOBALS['TL_LANG']['tl_evtreg_config']['error_jumpTo'] = array('Payment error', 'Payment error. Point this to the error module page.');
/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_evtreg_config']['new']    	= array('New configuration', 'Create a new configuration.');
$GLOBALS['TL_LANG']['tl_evtreg_config']['edit']   	= array('Edit configuration', 'Edit configuration ID %s.');
$GLOBALS['TL_LANG']['tl_evtreg_config']['copy']   	= array('Copy configuration', 'Copy configuration ID %s.');
$GLOBALS['TL_LANG']['tl_evtreg_config']['delete'] 	= array('Delete configuration', 'Delete configuration ID %s.');
$GLOBALS['TL_LANG']['tl_evtreg_config']['show']   	= array('Show configuration details', 'Show details for configuration ID %s.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_evtreg_config']['name_legend']		= 'Name';
$GLOBALS['TL_LANG']['tl_evtreg_config']['email_add_config_legend']		= 'Email Address Configuration';
$GLOBALS['TL_LANG']['tl_evtreg_config']['email_reg_config_legend']		= 'Booking Email Configuration';
$GLOBALS['TL_LANG']['tl_evtreg_config']['general_legend']		= 'General Configuration';
$GLOBALS['TL_LANG']['tl_evtreg_config']['payment_legend']		= 'Payment Gateway Configuration';
$GLOBALS['TL_LANG']['tl_evtreg_config']['api_legend']		= 'Paypal API credentials';



$GLOBALS['TL_LANG']['tl_evtreg_config']['Sale'] = 'Sale';
$GLOBALS['TL_LANG']['tl_evtreg_config']['Authorization'] = 'Authorization';
$GLOBALS['TL_LANG']['tl_evtreg_config']['Order'] = 'Order';

$GLOBALS['TL_LANG']['tl_evtreg_config']['AUD'] = 'Australian Dollar [AUD]';
$GLOBALS['TL_LANG']['tl_evtreg_config']['CAD'] = 'Canadian Dollar [CAD]';
$GLOBALS['TL_LANG']['tl_evtreg_config']['CHF'] = 'Swiss Franc [CHF]';
$GLOBALS['TL_LANG']['tl_evtreg_config']['CZK'] = 'Czech Koruna [CZK]';
$GLOBALS['TL_LANG']['tl_evtreg_config']['DKK'] = 'Danish Krone [DKK]';
$GLOBALS['TL_LANG']['tl_evtreg_config']['EUR'] = 'Euro [EUR]';
$GLOBALS['TL_LANG']['tl_evtreg_config']['GBP'] = 'Pound Sterling [GBP]';
$GLOBALS['TL_LANG']['tl_evtreg_config']['HKD'] = 'Hong Kong Dollar [HKD]';
$GLOBALS['TL_LANG']['tl_evtreg_config']['HUF'] = 'Hungarian Forint [HUF]';
$GLOBALS['TL_LANG']['tl_evtreg_config']['JPY'] = 'Japanese Yen [JPY]';
$GLOBALS['TL_LANG']['tl_evtreg_config']['NOK'] = 'Norwegian Krone [NOK]';
$GLOBALS['TL_LANG']['tl_evtreg_config']['NZD'] = 'New Zealand Dollar [NZD]';
$GLOBALS['TL_LANG']['tl_evtreg_config']['PLN'] = 'Polish Zloty [PLN]';
$GLOBALS['TL_LANG']['tl_evtreg_config']['SEK'] = 'Swedish Krona [SEK]';
$GLOBALS['TL_LANG']['tl_evtreg_config']['SGD'] = 'Singapore Dollar [SGD]';
$GLOBALS['TL_LANG']['tl_evtreg_config']['USD'] = 'U.S Dollar [USD]';


$GLOBALS['TL_LANG']['tl_evtreg_config']['live'] = 'Live';
$GLOBALS['TL_LANG']['tl_evtreg_config']['sandbox'] = 'Sandbox (testing)';