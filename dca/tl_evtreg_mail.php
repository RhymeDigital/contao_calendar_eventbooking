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
 * Table tl_evtreg_mail
 */
$GLOBALS['TL_DCA']['tl_evtreg_mail'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'closed'					  => true,
		'switchToEdit'				  => true,
		'ctables'                     => array('tl_evtreg_mail_content'),
		'onload_callback' => array
		(
			array('tl_evtreg_mail', 'checkPermission'),
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
			'panelLayout'             => 'filter;search,limit'
		),
		'label' => array
		(
			'fields'                  => array('name', 'sender'),
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
				'label'               => &$GLOBALS['TL_LANG']['tl_evtreg_mail']['new'],
				'href'                => 'act=create',
				'class'               => 'header_new',
				'attributes'          => 'onclick="Backend.getScrollOffset();"',
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
				'label'               => &$GLOBALS['TL_LANG']['tl_evtreg_mail']['edit'],
				'href'                => 'table=tl_evtreg_mail_content',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_evtreg_mail']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_evtreg_mail']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_evtreg_mail']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			),
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{name_legend},name;{address_legend},senderName,sender,cc,bcc;{expert_legend:hide},template',
	),

	// Fields
	'fields' => array
	(
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_evtreg_mail']['name'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'long'),
		),
		'senderName' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_evtreg_mail']['senderName'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
		),
		'sender' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_evtreg_mail']['sender'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'rgxp'=>'email', 'tl_class'=>'w50'),
		),
		'cc' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_evtreg_mail']['cc'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'rgxp'=>'extnd', 'tl_class'=>'w50'),
		),
		'bcc' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_evtreg_mail']['bcc'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'rgxp'=>'extnd', 'tl_class'=>'w50'),
		),
		'template' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_evtreg_mail']['template'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'default'                 => 'mail_default',
			'options'                 => $this->getTemplateGroup('mail_'),
			'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
		),
	)
);


class tl_evtreg_mail extends Backend
{
	
	public function checkPermission($dc)
	{

		if (strlen($this->Input->get('act')))
		{
			$GLOBALS['TL_DCA']['tl_evtreg_mail']['config']['closed'] = false;
		}
	}
}

