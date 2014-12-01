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
 * Table tl_evtreg_export 
 */
$GLOBALS['TL_DCA']['tl_evtreg_export'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'closed'					  => true,
		'onload_callback' => array
		(
	   array('tl_evtreg_export', 'checkPermission'),
		),
		'ondelete_callback'			  => array
		(
			array('tl_evtreg_export', 'archiveRecord'),
		),
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('eventTitle'),
			'flag'					  => 1,
			'panelLayout'             => 'filter;sort,search,limit'
		),
		'label' => array
		(
			'fields'                  => array('eventTitle', 'fallback'),
			'format'                  => '%s <span style="color:#b3b3b3; padding-left:3px;">[%s]</span>',
		),
		'global_operations' => array
		(
			'back' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['backBT'],
				'href'                => 'do=booking_setup',
				'class'               => 'header_back',
				'attributes'          => 'onclick="Backend.getScrollOffset();"',
			),
			

				'tools' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_evtreg_export']['tools'],
				'href'                => '',
				'class'               => 'header_calendar_eventbooking_tools',
				'attributes'          => 'onclick="Backend.getScrollOffset();" style="display:none"',
			),
			
			'export_all_events' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_evtreg_export']['export_all_events'],
				'href'                => 'key=export_all_events',
				'class'               => 'header_export_emails calendar_eventbooking-tools',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
		),
		'operations' => array
		(
		
		
			'event' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_evtreg_export']['event'],
				'href'                => 'key=event',
				'icon'                => 'system/modules/calendar_eventbooking/assets/edit.gif',
			),
		
			'attend' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_evtreg_export']['attend'],
				'href'                => 'key=attend',
				'icon'                => 'system/modules/calendar_eventbooking/assets/recipients.png',
			),
			
		

			'export' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_evtreg_export']['export'],
				'href'                => 'key=xls',
				'icon'                => 'system/modules/calendar_eventbooking/assets/exportEXCEL.gif',
			),
			
				'pdf' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_evtreg_export']['pdf'],
				'href'                => 'key=pdf',
				'icon'                => 'system/modules/calendar_eventbooking/assets/iconPDF.gif',
			),
			
				'pdflabels' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_evtreg_export']['pdflabels'],
				'href'                => 'key=pdflabels',
				'icon'                => 'system/modules/calendar_eventbooking/assets/labels.png',
			),
			
			
					'mail' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_evtreg_export']['mail'],
				'href'                => 'key=mail',
				'icon'                => 'system/modules/calendar_eventbooking/assets/icon-mail.gif',
			),

		)
	),

	// Fields
	'fields' => array
	(
		'eventTitle' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_evtreg_export']['eventTitle'],
			'exclude'                 => true,
		),
		'label' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_evtreg_export']['label'],
			'exclude'                 => true,
		),
	)
);


/**
 * tl_evtreg_export class.
 * 
 * @extends Backend
 */
class tl_evtreg_export extends Backend
{
	

		public function checkPermission($dc)
	{
		return;
	}



	/**
	 * Record is deleted, archive if necessary
	 */
	public function archiveRecord($dc)
	{
		return;
	}
	
	
}

