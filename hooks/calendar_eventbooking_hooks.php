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
 * @copyright  Isotope eCommerce Workgroup 2011
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @version    $Id$
 */


class calendar_eventbooking_hooks extends Controller
{


	/**
	 * Current object instance (Singleton)
	 * @var object
	 */
	protected static $objInstance;

	/**
	 * Isotope object
	 * @var object
	 */
	protected $Isotope;

	/**
	 * Prevent cloning of the object (Singleton)
	 */
	final private function __clone() {}


	/**
	 * Prevent direct instantiation (Singleton)
	 */
	protected function __construct()
	{
		parent::__construct();

		$this->import('Database');
		$this->import('FrontendUser', 'User');
	}


	/**
	 * Instantiate a database driver object and return it (Factory)
	 *
	 * @return object
	 */
	public static function getInstance()
	{
		if (!is_object(self::$objInstance))
		{
			self::$objInstance = new calendar_eventbooking_hooks();
		}

		return self::$objInstance;
	}




	/**
	 * Callback for Isotope Hook "outputBackendTemplate"
	 */
	public function calendar_eventbooking_OutputBackendTemplate($strContent, $strTemplate) {
	
     if (((($strTemplate == 'be_main') && ($_GET['do']=='calendar') && ($_GET['table']=='tl_calendar_events') && ($_GET['act']=='edit'))))
    { 
		 	$insert = '	
			<script>
		    window.addEvent(\'domready\', function(){
		    Element.extend( 
		            { show:function(e){ this.setStyle(\'display\',\'block\'); },
		              hide:function(e){ this.setStyle(\'display\',\'none\');  }
		            });  
		            
				if (document.getElementById(\'opt_evtreg_certificate_0\').checked == true){
		       		$(\'ctrl_evtreg_certificate_preview\').hide(); 
		    }
		    }); 
			 </script>';		
		    	$strContent = str_replace("</head>",$insert.'
		    	</head>',$strContent);
    } 
    return $strContent; 
	}
	

	
	
}

