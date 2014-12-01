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
 * Table tl_evtreg_mods 
 */
$GLOBALS['TL_DCA']['tl_evtreg_mods'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ctable'                      => array('tl_evtreg_types'),
		'enableVersioning'            => true,
		'closed'					  => true,
		'onload_callback'			  => array
		(
			array('tl_evtreg_mods', 'checkPermission'),
		),
		'ondelete_callback'			  => array
		(
			array('tl_evtreg_mods', 'archiveRecord'),
		),
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('name'),
			'flag'                    => 1,
			'panelLayout'             => 'sort,filter;search,limit'
		),
		'label' => array
		(
			'fields'                  => array('name', 'type'),
			'format'                  => '%s <span style="color:#b3b3b3; padding-left:3px;">[%s]</span>',
			'label_callback'		  => array('tl_evtreg_mods', 'addIcon'),

		),
		'global_operations' => array
		(
			'back' => array
			(
				'label'					=> &$GLOBALS['TL_LANG']['MSC']['backBT'],
				'href'					=> 'table=',
				'class'					=> 'header_back',
				'attributes'			=> 'onclick="Backend.getScrollOffset();"',
			),
			'new' => array
			(
				'label'					=> &$GLOBALS['TL_LANG']['tl_evtreg_mods']['new'],
				'href'					=> 'act=create',
				'class'					=> 'header_new',
				'attributes'			=> 'onclick="Backend.getScrollOffset();"',
			),
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_evtreg_mods']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_evtreg_mods']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_evtreg_mods']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_evtreg_mods']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			),
			'buttons' => array
			(
				'button_callback'     => array('tl_evtreg_mods', 'moduleOperations'),
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'			=> array('type', 'protected'),
		'default'				=> '{type_legend},name,label,type;{api_legend},api_username,api_password,api_signature;{gateway_legend},paypal_standard,payment_type,currency_code,environment;{expert_legend:hide},guests,protected;{enabled_legend},enabled',
		'paypal'				=> '{type_legend},name,label,type;{gateway_legend},paypal_standard,identity_token,payment_type,currency_code,environment;{expert_legend:hide},guests,protected;{enabled_legend},enabled',
		'paypalexpress'		=> '{type_legend},name,label,type;{api_legend},api_username,api_password,api_signature;{gateway_legend},paypal_standard,identity_token,payment_type,currency_code,environment;{expert_legend:hide},guests,protected;{enabled_legend},enabled',
		'paypalpro'		=> '{type_legend},name,label,type;{api_legend},api_username,api_password,api_signature;{gateway_legend},paypal_standard,identity_token,allowedcc_types,payment_type,currency_code,environment;{expert_legend:hide},guests,protected;{enabled_legend},enabled',
	),
	
	// Subpalettes
	'subpalettes' => array
	(
		'protected'						=> 'groups',
	),

	// Fields
	'fields' => array
	(
		'type' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_evtreg_mods']['type'],
			'default'                 => 'cc',
			'exclude'                 => true,
			'filter'                  => false,
			'inputType'               => 'select',
			'default'				  => 'cash',
			'options_callback'        => array('tl_evtreg_mods', 'getModules'),
			'reference'               => &$GLOBALS['TL_LANG']['PAYE'],
			'eval'                    => array('helpwizard'=>true, 'submitOnChange'=>true, 'tl_class'=>'clr')
		),
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_evtreg_mods']['name'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50')
		),
		'label' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_evtreg_mods']['label'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
		),
		
			'api_username' => array
		(
			'label'         => &$GLOBALS['TL_LANG']['tl_evtreg_mods']['api_username'],
			'exclude'       => true,
			'inputType'     => 'text',
			'eval'          => array('mandatory'=>false, 'tl_class'=>'w10')
		),

			'api_password' => array
			(
				'label'         => &$GLOBALS['TL_LANG']['tl_evtreg_mods']['api_password'],
				'exclude'       => true,
				'inputType'     => 'text',
				'eval'          => array('mandatory'=>false, 'tl_class'=>'w10')
			),

			'api_signature' => array
			(
				'label'         => &$GLOBALS['TL_LANG']['tl_evtreg_mods']['api_signature'],
				'exclude'       => true,
				'inputType'     => 'text',
				'eval'          => array('mandatory'=>false, 'tl_class'=>'w10')
			),		
			  'identity_token' => array
			(
				'label'         => &$GLOBALS['TL_LANG']['tl_evtreg_mods']['identity_token'],
				'exclude'       => true,
				'inputType'     => 'text',
				'eval'          => array('mandatory'=>false, 'tl_class'=>'w10')
			),			
			'paypal_standard' => array
		(
			'label'         => &$GLOBALS['TL_LANG']['tl_evtreg_mods']['paypal_standard'],
			'exclude'       => true,
			'inputType'     => 'text',
			'eval'          => array('mandatory'=>false, 'tl_class'=>'w10')
		),
			'payment_type' => array	
		(
			'label'         => &$GLOBALS['TL_LANG']['tl_evtreg_mods']['payment_type'],
			'exclude'       => true,
			'inputType'     => 'select',
			'options'       => array('Sale', 'Authorization', 'Order'),
			'reference'     => & $GLOBALS['TL_LANG']['tl_evtreg_mods'],
		),
		'currency_code' => array	
			(
				'label'         => &$GLOBALS['TL_LANG']['tl_evtreg_mods']['currency_code'],
				'exclude'       => true,
				'inputType'     => 'select',
				'options'       => array('AUD', 'CAD', 'CHF' , 'CZK' , 'DKK' , 'EUR' , 'GBP' , 'HKD' , 'HUF' , 'JPY' , 'NOK' , 'NZD' , 'PLN' , 'SEK' , 'SGD' , 'USD'),
				'reference'     => &$GLOBALS['TL_LANG']['tl_evtreg_mods']
			),
		'environment' => array	
		(
			'label'         => &$GLOBALS['TL_LANG']['tl_evtreg_mods']['environment'],
			'exclude'       => true,
			'inputType'     => 'select',
			'options'       => array('sandbox', 'live'),
			'reference'     => &$GLOBALS['TL_LANG']['tl_evtreg_mods']
		),
		'guests' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_evtreg_mods']['guests'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
		),
		'protected' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_evtreg_mods']['protected'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'groups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_evtreg_mods']['groups'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_member_group.name',
			'eval'                    => array('multiple'=>true)
		),
		'debug' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_evtreg_mods']['debug'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
		),		
		'enabled' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_evtreg_mods']['enabled'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
		),
		
			'allowedcc_types' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_evtreg_mods']['allowedcc_types'],
			'exclude'       => true,
			'inputType'     => 'checkboxWizard',
			'options'       => array('MasterCard', 'Visa', 'American Express' , 'Discover' , 'Maestro'),
			'reference'     => &$GLOBALS['TL_LANG']['calevntbooking'],
			'eval'             => array('multiple'=>true)
		),
		
		
			
			
	)
);
  

/**
 * tl_evtreg_mods class.
 * 
 * @extends Backend
 */
class tl_evtreg_mods extends Backend
{
	
	public function checkPermission($dc)
	{
		if (strlen($this->Input->get('act')))
		{
			$GLOBALS['TL_DCA']['tl_evtreg_mods']['config']['closed'] = false;
		}
		
		// Hide archived (used and deleted) modules
		$arrModules = $this->Database->execute("SELECT id FROM tl_evtreg_mods WHERE archive<2")->fetchEach('id');
		
		if (!count($arrModules))
		{
			$arrModules = array(0);
		}

		$GLOBALS['TL_DCA']['tl_evtreg_mods']['list']['sorting']['root'] = $arrModules;

		// Check current action
		switch ($this->Input->get('act'))
		{
			case 'edit':
			case 'copy':
			case 'delete':
			case 'show':
				if (!in_array($this->Input->get('id'), $arrModules))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' payment module ID "'.$this->Input->get('id').'"', 'tl_evtreg_mods checkPermission()', TL_ACCESS);
					$this->redirect($this->Environment->script.'?act=error');
				}
				break;

			case 'editAll':
			case 'copyAll':
			case 'deleteAll':
				$session = $this->Session->getData();
				$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $arrModules);
				$this->Session->setData($session);
				break;
		}
	}
	
	
	/**
	 * Record is deleted, archive if necessary
	 */
	public function archiveRecord($dc)
	{
	}


	/**
	 * Return a string of more buttons for the current payment module.
	 * 
	 * @todo Collect additional buttons from payment modules.
	 * @access public
	 * @param array $arrRow
	 * @return string
	 */
	public function moduleOperations($arrRow)
	{
		$strClass = $GLOBALS['EVTPREG_MOD'][$arrRow['mods']];

		if (!strlen($strClass) || !$this->classFileExists($strClass))
			return '';
			
		try 
		{
			$objModule = new $strClass($arrRow);
			return $objModule->moduleOperations();
		}
		catch (Exception $e) {}
		
		return '';
	}
	


	
	/**
	 * Get a list of all payment modules available.
	 * 
	 * @access public
	 * @return array
	 */
	public function getModules()
	{
		$arrModules = array();
		
		if (is_array($GLOBALS['EVTPREG_MOD']) && count($GLOBALS['EVTPREG_MOD']))
		{
			foreach( $GLOBALS['EVTPREG_MOD'] as $module => $class )
			{
				$arrModules[$module] = (strlen($GLOBALS['TL_LANG']['PAYE'][$module][0]) ? $GLOBALS['TL_LANG']['PAYE'][$module][0] : $module);
			}
		}
		
		return $arrModules;
	}
	

	
	/**
	 * Add an image to each record
	 * @param array
	 * @param string
	 * @return string
	 */
	public function addIcon($row, $label)
	{
		$image = 'published';

		if (!$row['enabled'])
		{
			$image = 'un'.$image;
		}

		return sprintf('<div class="list_icon" style="background-image:url(\'system/themes/%s/images/%s.gif\');">%s</div>', $this->getTheme(), $image, $label);
	}
}

