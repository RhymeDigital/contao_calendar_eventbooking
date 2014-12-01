<?php 

namespace Contao;


if (!defined('TL_ROOT')) die('You can not access this file directly!');

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




class ModuleEventsBookingSetup extends \BackendModule
{

	protected $strTemplate = 'be_evtreg_setup';
	
	
	protected function compile()
	{
		$this->import('BackendUser', 'User');
		
		// Modules
		$arrGroups = array();
		
		

		foreach ($GLOBALS['EVTREG_MOD'] as $strGroup=>$arrModules)
		{
			foreach (array_keys($arrModules) as $strModule)
			{
	
					$arrGroups[$GLOBALS['TL_LANG']['EVTREG'][$strGroup]][$GLOBALS['EVTREG_MOD'][$strGroup][$strModule]['tables'][0]] = array
					(
						'name' => $GLOBALS['TL_LANG']['EVTREG'][$strModule][0],
						'description' => $GLOBALS['TL_LANG']['EVTREG'][$strModule][1],
						'icon' => $arrModules[$strModule]['icon']
					);

			}
		}

		$this->Template->arrGroups = $arrGroups;
		$this->Template->script = $this->Environment->script;
		$this->Template->welcome = $GLOBALS['TL_LANG']['EVENTREG']['config_module'];
	}
}

