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

class ModuleEventsPaymentError extends \Module
{
	
	protected $strTemplate = 'mod_eventpaymenterror';


	public function generate() {
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### Event Payment Error &#169; 360Fusion 2012 ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'typolight/main.php?do=modules&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}
	
	

	protected function compile()
	{
		
		 $this->strTemplate = $this->evtregerror_template;
		 $this->Template = new \FrontendTemplate($this->strTemplate);
		 
		 
		$GLOBALS['TL_LANGUAGE'] = $objPage-> language; 
	  $this->Template->result = $_SESSION['nvpReqArray']['L_LONGMESSAGE0'];
	  $this->Template->back = $_SESSION['nvpReqArray']['BACK'];
		return;
	}
	
}
?>