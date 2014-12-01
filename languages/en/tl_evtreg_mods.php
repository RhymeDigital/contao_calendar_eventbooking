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
 * @copyright  Winans Creative 2009, Intelligent Spark 2010, iserv.ch GmbH 2010
 * @author     Fred Bliss <fred.bliss@intelligentspark.com>
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

 
/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_evtreg_mods']['type']						= array('Type of Payment Gateway', 'Select a particular payment gateway');
$GLOBALS['TL_LANG']['tl_evtreg_mods']['name']						= array('Payment Type Name', 'Enter a name for this payment method. This will only be used in the backend.');
$GLOBALS['TL_LANG']['tl_evtreg_mods']['label']						= array('Payment Type Label', 'The label will be shown to customers on checkout.');
$GLOBALS['TL_LANG']['tl_evtreg_mods']['paypal_standard']	= array('PayPal account', 'Enter the Paypal account to use to accept payments.');
$GLOBALS['TL_LANG']['tl_evtreg_mods']['api_username']   = array('API username', 'Link to the paypal API username.');
$GLOBALS['TL_LANG']['tl_evtreg_mods']['api_password']   = array('API password', 'Link to the paypal API password.');
$GLOBALS['TL_LANG']['tl_evtreg_mods']['api_signature']  = array('Signature', 'Link to the paypal signature.');
$GLOBALS['TL_LANG']['tl_evtreg_mods']['identity_token']  = array('Identity Token', 'Link to the payments data transfer identity token for IPN. (optional)');
$GLOBALS['TL_LANG']['tl_evtreg_mods']['payment_type'] = array('Payment type', 'Determine when to charge the buyer\'s PayPal or credit card account.');
$GLOBALS['TL_LANG']['tl_evtreg_mods']['currency_code'] = array('Currency code', 'The 3 alpha currency code that represents the currency used for the payment.');
$GLOBALS['TL_LANG']['tl_evtreg_mods']['environment'] = array('Environment', 'Select whether this is a live or test environment.');
$GLOBALS['TL_LANG']['tl_evtreg_mods']['groups']						= array('Member Groups', 'Restrict this payment type to certain member groups.');
$GLOBALS['TL_LANG']['tl_evtreg_mods']['protected']      			= array('Protect module', 'Show the payment type to certain member groups only.');
$GLOBALS['TL_LANG']['tl_evtreg_mods']['guests']         			= array('Show to guests only', 'Hide the payment type if a member is logged in.');
$GLOBALS['TL_LANG']['tl_evtreg_mods']['debug']						= array('Debug mode', 'For testing without actually capturing for payment.');
$GLOBALS['TL_LANG']['tl_evtreg_mods']['enabled']					= array('Enabled', 'Check here if the payment type should be enabled in the store.');

$GLOBALS['TL_LANG']['tl_evtreg_mods']['allowedcc_types'] = array('Allowed credit cards', 'Select allowed credit cards. NOTE: For UK, only Maestro, MasterCard, Discover, and Visa are allowable');





/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_evtreg_mods']['type_legend']		= 'Name & Type';
$GLOBALS['TL_LANG']['tl_evtreg_mods']['gateway_legend']		= 'Payment Gateway Configuration';
$GLOBALS['TL_LANG']['tl_evtreg_mods']['expert_legend']		= 'Expert settings';
$GLOBALS['TL_LANG']['tl_evtreg_mods']['enabled_legend']		= 'Enabled settings';
$GLOBALS['TL_LANG']['tl_evtreg_mods']['api_legend'] = 'Paypal API credentials';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_evtreg_mods']['new']				= array('New payment type', 'Create a New payment type');
$GLOBALS['TL_LANG']['tl_evtreg_mods']['edit']   			= array('Edit payment type', 'Edit payment type ID %s');
$GLOBALS['TL_LANG']['tl_evtreg_mods']['copy']   			= array('Copy payment type', 'Copy payment type ID %s');
$GLOBALS['TL_LANG']['tl_evtreg_mods']['delete'] 			= array('Delete payment type', 'Delete payment type ID %s');
$GLOBALS['TL_LANG']['tl_evtreg_mods']['show']   			= array('Payment Type Details', 'Show details of payment type ID %s');

$GLOBALS['TL_LANG']['tl_evtreg_mods']['Sale'] = 'Sale';
$GLOBALS['TL_LANG']['tl_evtreg_mods']['Authorization'] = 'Authorization';
$GLOBALS['TL_LANG']['tl_evtreg_mods']['Order'] = 'Order';

$GLOBALS['TL_LANG']['tl_evtreg_mods']['Yes'] = 'Yes';
$GLOBALS['TL_LANG']['tl_evtreg_mods']['No'] = 'No';

$GLOBALS['TL_LANG']['tl_evtreg_mods']['AUD'] = 'Australian Dollar [AUD]';
$GLOBALS['TL_LANG']['tl_evtreg_mods']['CAD'] = 'Canadian Dollar [CAD]';
$GLOBALS['TL_LANG']['tl_evtreg_mods']['CHF'] = 'Swiss Franc [CHF]';
$GLOBALS['TL_LANG']['tl_evtreg_mods']['CZK'] = 'Czech Koruna [CZK]';
$GLOBALS['TL_LANG']['tl_evtreg_mods']['DKK'] = 'Danish Krone [DKK]';
$GLOBALS['TL_LANG']['tl_evtreg_mods']['EUR'] = 'Euro [EUR]';
$GLOBALS['TL_LANG']['tl_evtreg_mods']['GBP'] = 'Pound Sterling [GBP]';
$GLOBALS['TL_LANG']['tl_evtreg_mods']['HKD'] = 'Hong Kong Dollar [HKD]';
$GLOBALS['TL_LANG']['tl_evtreg_mods']['HUF'] = 'Hungarian Forint [HUF]';
$GLOBALS['TL_LANG']['tl_evtreg_mods']['JPY'] = 'Japanese Yen [JPY]';
$GLOBALS['TL_LANG']['tl_evtreg_mods']['NOK'] = 'Norwegian Krone [NOK]';
$GLOBALS['TL_LANG']['tl_evtreg_mods']['NZD'] = 'New Zealand Dollar [NZD]';
$GLOBALS['TL_LANG']['tl_evtreg_mods']['PLN'] = 'Polish Zloty [PLN]';
$GLOBALS['TL_LANG']['tl_evtreg_mods']['SEK'] = 'Swedish Krona [SEK]';
$GLOBALS['TL_LANG']['tl_evtreg_mods']['SGD'] = 'Singapore Dollar [SGD]';
$GLOBALS['TL_LANG']['tl_evtreg_mods']['USD'] = 'U.S Dollar [USD]';




$GLOBALS['TL_LANG']['tl_evtreg_mods']['live'] = 'Live';
$GLOBALS['TL_LANG']['tl_evtreg_mods']['sandbox'] = 'Sandbox (testing)';

