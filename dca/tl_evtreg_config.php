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



$this->loadLanguageFile('subdivisions');


/**
 * Table tl_evtreg_config 
 */
$GLOBALS['TL_DCA']['tl_evtreg_config'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'closed'					  => true,
		'onload_callback' => array
		(
	   array('tl_evtreg_config', 'checkPermission'),
		),
		'ondelete_callback'			  => array
		(
			array('tl_evtreg_config', 'archiveRecord'),
		),
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('name'),
			'flag'					  => 1,
		),
		'label' => array
		(
			'fields'                  => array('name', 'fallback'),
			'format'                  => '%s <span style="color:#b3b3b3; padding-left:3px;">[%s]</span>',
		),
		'global_operations' => array
		(
			'back' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['backBT'],
				'href'                => 'table=',
				'class'               => 'header_back',
				'attributes'          => 'onclick="Backend.getScrollOffset();"',
			),
		
			'new' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_evtreg_config']['new'],
				'href'                => 'act=create',
				'class'               => 'header_new',
				'attributes'          => 'onclick="Backend.getScrollOffset();"',
			),
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_evtreg_config']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif',
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_evtreg_config']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif',
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'	=> array('payment'),
		'default'	=> '{name_legend},name,label,fallback;{email_add_config_legend},adminEmail;{email_reg_config_legend},emailRegVerification,emailRegTemplate,emailAdminVerification,emailAdminTemplate;{payment_legend},payment',
	),
	
		// Subpalettes
	'subpalettes' => array
	(
//		'payment'  => 'api_username,api_password,api_signature,paypal_standard,environment,payment_type,currency_code,checkout_jumpTo,success_jumpTo,error_jumpTo',
	'payment'  =>'checkout_jumpTo,success_jumpTo,error_jumpTo',
	),
	

	// Fields
	'fields' => array
	(
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_evtreg_config']['name'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'unique'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
		),
		'label' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_evtreg_config']['label'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
		),
		'adminEmail' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_evtreg_config']['adminEmail'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>64, 'rgxp'=>'email', 'tl_class'=>'w50'),
		),
		
			'emailRegVerification' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_evtreg_config']['emailRegVerification'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 m12'),
		),
		'emailRegTemplate' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_evtreg_config']['emailRegTemplate'],
			'exclude'                 => true,
			'inputType'               => 'select',
		    'foreignKey'              => 'tl_evtreg_mail.name',
			'eval'                    => array('mandatory'=>false, 'includeBlankOption'=>false, 'tl_class'=>'w50')
		),
		'adminEmail' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_evtreg_config']['adminEmail'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>64, 'rgxp'=>'email', 'tl_class'=>'w50'),
		),
		'emailAdminVerification' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_evtreg_config']['emailAdminVerification'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 m12'),
		),
		'emailAdminTemplate' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_evtreg_config']['emailAdminTemplate'],
			'exclude'                 => true,
			'inputType'               => 'select',
		    'foreignKey'              => 'tl_evtreg_mail.name',
			'eval'                    => array('mandatory'=>false, 'includeBlankOption'=>false, 'tl_class'=>'w50')
		),
		
			'payment' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_evtreg_config']['payment'],
			'exclude'                 => true,
			'default'                 => '',
			'inputType'               => 'checkbox',
			'filter'                  => true,
			'eval'                    => array('submitOnChange'=>true)
		),
		
		/*
		'paymentType' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_evtreg_config']['paymentType'],
			'exclude'                 => true,
			'inputType'               => 'select',
		    'foreignKey'              => 'tl_iso_payment_modules.name',
			'eval'                    => array('mandatory'=>false, 'includeBlankOption'=>false, 'tl_class'=>'w80')
		),
		*/
	
	/*	
		'api_username' => array
		(
			'label'         => &$GLOBALS['TL_LANG']['tl_evtreg_config']['api_username'],
			'exclude'       => true,
			'inputType'     => 'text',
			'eval'          => array('mandatory'=>false, 'tl_class'=>'w50')
		),
		
		
		'api_password' => array
		(
			'label'         => &$GLOBALS['TL_LANG']['tl_evtreg_config']['api_password'],
			'exclude'       => true,
			'inputType'     => 'text',
			'eval'          => array('mandatory'=>false, 'tl_class'=>'w20')
		),
		
		
		'api_signature' => array
		(
			'label'         => &$GLOBALS['TL_LANG']['tl_evtreg_config']['api_signature'],
			'exclude'       => true,
			'inputType'     => 'text',
			'eval'          => array('mandatory'=>false, 'tl_class'=>'w60')
		),
		
		'paypal_standard' => array
		(
		'label'         => &$GLOBALS['TL_LANG']['tl_evtreg_config']['paypal_standard'],
		'exclude'       => true,
		'inputType'     => 'text',
		'eval'          => array('mandatory'=>false, 'tl_class'=>'w80')
		),
		

		'environment' => array
		(
			'label'         => &$GLOBALS['TL_LANG']['tl_evtreg_config']['environment'],
			'exclude'       => true,
			'inputType'     => 'select',
			'options'       => array('sandbox', 'live'),
			'reference'     => &$GLOBALS['TL_LANG']['tl_evtreg_config']
		),
				
		'payment_type' => array
		(
			'label'         => &$GLOBALS['TL_LANG']['tl_evtreg_config']['payment_type'],
			'exclude'       => true,
			'inputType'     => 'select',
			'options'       => array('Sale', 'Authorization', 'Order'),
			'reference'     => & $GLOBALS['TL_LANG']['tl_evtreg_config']
		),
		
		'currency_code' => array
		(
			'label'         => &$GLOBALS['TL_LANG']['tl_evtreg_config']['currency_code'],
			'exclude'       => true,
			'inputType'     => 'select',
			'options'       => array('AUD', 'CAD', 'CHF' , 'CZK' , 'DKK' , 'EUR' , 'GBP' , 'HKD' , 'HUF' , 'JPY' , 'NOK' , 'NZD' , 'PLN' , 'SEK' , 'SGD' , 'USD'),
			'reference'     => &$GLOBALS['TL_LANG']['tl_evtreg_config']	
			),
			
	*/
		'checkout_jumpTo' => array
		(
			'label'         => &$GLOBALS['TL_LANG']['tl_evtreg_config']['checkout_jumpTo'],
			'exclude'       => true,
			'inputType'     => 'pageTree',
			'eval'          => array('fieldType'=>'radio')
		),
			
		'success_jumpTo' => array
		(
			'label'         => &$GLOBALS['TL_LANG']['tl_evtreg_config']['success_jumpTo'],
			'exclude'       => true,
			'inputType'     => 'pageTree',
			'eval'          => array('fieldType'=>'radio')
		),
		
		'error_jumpTo' => array
		(
			'label'         => &$GLOBALS['TL_LANG']['tl_evtreg_config']['error_jumpTo'],
			'exclude'       => true,
			'inputType'     => 'pageTree',
			'eval'          => array('fieldType'=>'radio')
		),
					
			
		),
		);


/**
 * tl_evtreg_config class.
 * 
 * @extends Backend
 */
class tl_evtreg_config extends Backend
{
	

		public function checkPermission($dc)
	{

		if (strlen($this->Input->get('act')))
		{
			$GLOBALS['TL_DCA']['tl_evtreg_config']['config']['closed'] = false;
		}

	}



	/**
	 * Record is deleted, archive if necessary
	 */
	public function archiveRecord($dc)
	{
		return;
	}
	
	
}

