<?php 
namespace Contao;

if (!defined('TL_ROOT')) die('You cannot access this file directly!');

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
 * @copyright  360fusion  2011
 * @author     Darrell Martin <darrell@360fusion.co.uk>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


class ModuleEventsReader extends \Events
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_event';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### EVENT READER ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;




			return $objTemplate->parse();
		}
		


		// Set the item from the auto_item parameter
		if (!isset($_GET['events']) && $GLOBALS['TL_CONFIG']['useAutoItem'] && isset($_GET['auto_item']))
		{
			\Input::setGet('events', \Input::get('auto_item'));
		}
		
		// Do not index or cache the page if no event has been specified
		if (!\Input::get('events'))
		{
			global $objPage;
			$objPage->noSearch = 1;
			$objPage->cache = 0;
			return '';
		}

		$this->cal_calendar = $this->sortOutProtected(deserialize($this->cal_calendar));

		// Do not index or cache the page if there are no calendars
		if (!is_array($this->cal_calendar) || empty($this->cal_calendar))
		{
			global $objPage;
			$objPage->noSearch = 1;
			$objPage->cache = 0;
			return '';
		}


		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		global $objPage;

		$this->Template->event = '';
		$this->Template->referer = 'javascript:history.go(-1)';
		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];

		$time = time();

		// Get current event
		$objEvent = \CalendarEventsModel::findPublishedByParentAndIdOrAlias(\Input::get('events'), $this->cal_calendar);

		if ($objEvent === null)
		{
			// Do not index or cache the page
			$objPage->noSearch = 1;
			$objPage->cache = 0;

			// Send a 404 header
			header('HTTP/1.1 404 Not Found');
			$this->Template->event = '<p class="error">' . sprintf($GLOBALS['TL_LANG']['MSC']['invalidPage'], \Input::get('events')) . '</p>';
			return;
		}

		// Overwrite the page title
		if ($objEvent->title != '')
		{
			$objPage->pageTitle = strip_tags(strip_insert_tags($objEvent->title));
		}


		// Overwrite the page description
		if ($objEvent->teaser != '')
		{
			$objPage->description = $this->prepareMetaDescription($objEvent->teaser);
		}

		$intStartTime = $objEvent->startTime;
		$intEndTime = $objEvent->endTime;
		$span = \Calendar::calculateSpan($intStartTime, $intEndTime);

		// Do not show dates in the past if the event is recurring (see #923)
		if ($objEvent->recurring)
		{
			$arrRange = deserialize($objEvent->repeatEach);

			while ($intStartTime < time() && $intEndTime < $objEvent->repeatEnd)
			{
				$intStartTime = strtotime('+' . $arrRange['value'] . ' ' . $arrRange['unit'], $intStartTime);
				$intEndTime = strtotime('+' . $arrRange['value'] . ' ' . $arrRange['unit'], $intEndTime);
			}
		}

		if ($objPage->outputFormat == 'xhtml')
		{
			$strTimeStart = '';
			$strTimeEnd = '';
			$strTimeClose = '';
		}
		else
		{
			$strTimeStart = '<time datetime="' . date('Y-m-d\TH:i:sP', $intStartTime) . '">';
			$strTimeEnd = '<time datetime="' . date('Y-m-d\TH:i:sP', $intEndTime) . '">';
			$strTimeClose = '</time>';
		}

		// Get date
		if ($span > 0)
		{
			$date = $strTimeStart . \Date::parse(($objEvent->addTime ? $objPage->datimFormat : $objPage->dateFormat), $intStartTime) . $strTimeClose . ' - ' . $strTimeEnd . \Date::parse(($objEvent->addTime ? $objPage->datimFormat : $objPage->dateFormat), $intEndTime) . $strTimeClose;
		}
		elseif ($intStartTime == $intEndTime)
		{
			$date = $strTimeStart . \Date::parse($objPage->dateFormat, $intStartTime) . ($objEvent->addTime ? ' (' . \Date::parse($objPage->timeFormat, $intStartTime) . ')' : '') . $strTimeClose;
		}
		else
		{
			$date = $strTimeStart . \Date::parse($objPage->dateFormat, $intStartTime) . ($objEvent->addTime ? ' (' . \Date::parse($objPage->timeFormat, $intStartTime) . $strTimeClose . ' - ' . $strTimeEnd . \Date::parse($objPage->timeFormat, $intEndTime) . ')' : '') . $strTimeClose;
		}

		$until = '';
		$recurring = '';

		// Recurring event
		if ($objEvent->recurring)
		{
			$arrRange = deserialize($objEvent->repeatEach);
			$strKey = 'cal_' . $arrRange['unit'];
			$recurring = sprintf($GLOBALS['TL_LANG']['MSC'][$strKey], $arrRange['value']);

			if ($objEvent->recurrences > 0)
			{
				$until = sprintf($GLOBALS['TL_LANG']['MSC']['cal_until'], \Date::parse($objPage->dateFormat, $objEvent->repeatEnd));
			}
		}

		// Override the default image size
		if ($this->imgSize != '')
		{
			$size = deserialize($this->imgSize);

			if ($size[0] > 0 || $size[1] > 0)
			{
				$objEvent->size = $this->imgSize;
			}
		}

		$objTemplate = new \FrontendTemplate($this->cal_template);
		$objTemplate->setData($objEvent->row());

		$objTemplate->date = $date;
		$objTemplate->start = $intStartTime;
		$objTemplate->end = $intEndTime;
		$objTemplate->class = strlen($objEvent->cssClass) ? ' ' . $objEvent->cssClass : '';
		$objTemplate->recurring = $recurring;
		$objTemplate->until = $until;
		$objTemplate->locationLabel = $GLOBALS['TL_LANG']['MSC']['location'];

		$objTemplate->lastBookingDate = NULL;
		$objTemplate->spacesRemaining = NULL;
		$objTemplate->availableSpaces = NULL;
		
		
		$objTemplate->registrationpage = $this->Environment->base.$this->getPageFromID($this->cal_eventregistrationpage).'?id='.$objEvent->id;
		
		 $eventDetails = $this->Database->prepare("SELECT evtreg_availableSpaces, evtreg_availableSpacesRemaining, evtreg_lastDate FROM tl_calendar_events WHERE id=?")
		 	->limit(1)
          ->execute($objEvent->id);
          
          	if ($eventDetails->evtreg_availableSpaces != NULL) {
             $lastBookingDate = date("l F dS Y", $eventDetails->evtreg_lastDate);
   			   $objTemplate->lastBookingDate = $lastBookingDate;
	          $objTemplate->spacesRemaining = $eventDetails->evtreg_availableSpacesRemaining;
			   $objTemplate->availableSpaces = $eventDetails->evtreg_availableSpaces;
			 }

		
		$objTemplate->details = '';
		$objElement = \ContentModel::findPublishedByPidAndTable($objEvent->id, 'tl_calendar_events');
		
		if ($objElement !== null)
		{
			while ($objElement->next())
			{
				$objTemplate->details .= $this->getContentElement($objElement->id);
			}
		}

		$objTemplate->addImage = false;

		// Add an image
		if ($objEvent->addImage && $objEvent->singleSRC != '')
		{
			$objModel = \FilesModel::findByUuid($objEvent->singleSRC);

			if ($objModel === null)
			{
				if (!\Validator::isUuid($objEvent->singleSRC))
				{
					$objTemplate->text = '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
				}
			}
			elseif (is_file(TL_ROOT . '/' . $objModel->path))
			{
				// Do not override the field now that we have a model registry (see #6303)
				$arrEvent = $objEvent->row();
				$arrEvent['singleSRC'] = $objModel->path;

				$this->addImageToTemplate($objTemplate, $arrEvent);
			}
		}

		$objTemplate->enclosure = array();

		// Add enclosures
		if ($objEvent->addEnclosure)
		{
			$this->addEnclosuresToTemplate($objTemplate, $objEvent->row());
		}

		$this->Template->event = $objTemplate->parse();

		// HOOK: comments extension required
		if ($objEvent->noComments || !in_array('comments', \ModuleLoader::getActive()))
		{
			$this->Template->allowComments = false;
			return;
		}

		$objCalendar = $objEvent->getRelated('pid');
		$this->Template->allowComments = $objCalendar->allowComments;

		// Comments are not allowed
		if (!$objCalendar->allowComments)
		{
			return;
		}

		// Adjust the comments headline level
		$intHl = min(intval(str_replace('h', '', $this->hl)), 5);
		$this->Template->hlc = 'h' . ($intHl + 1);

		$this->import('Comments');
		$arrNotifies = array();

		// Notify the system administrator
		if ($objCalendar->notify != 'notify_author')
		{
			$arrNotifies[] = $GLOBALS['TL_ADMIN_EMAIL'];
		}

		// Notify the author
		if ($objCalendar->notify != 'notify_admin')
		{
			if (($objAuthor = $objEvent->getRelated('author')) !== null && $objAuthor->email != '')
			{
				$arrNotifies[] = $objAuthor->email;
			}
		}

		$objConfig = new \stdClass();

		$objConfig->perPage = $objCalendar->perPage;
		$objConfig->order = $objCalendar->sortOrder;
		$objConfig->template = $this->com_template;
		$objConfig->requireLogin = $objCalendar->requireLogin;
		$objConfig->disableCaptcha = $objCalendar->disableCaptcha;
		$objConfig->bbcode = $objCalendar->bbcode;
		$objConfig->moderate = $objCalendar->moderate;

		$this->Comments->addCommentsToTemplate($this->Template, $objConfig, 'tl_calendar_events', $objEvent->id, $arrNotifies);
	}

	
	
	
	
		private function getPageFromID($intId){
		global $objPage;

		if (strlen($intId) && $intId != $objPage->id)
		{
			$objNextPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
										  ->limit(1)
										  ->execute($intId);

			if ($objNextPage->numRows)
			{
				return ($this->getUrl($objNextPage->fetchAssoc()));
			}
		}

		$this->reload();
		//return;
	}
	
	private function getUrl($arrRow, $strParams=''){
		$strUrl = ($GLOBALS['TL_CONFIG']['rewriteURL'] ? '' : 'index.php/') . (strlen($arrRow['alias']) ? $arrRow['alias'] : $arrRow['id']) . $strParams . $GLOBALS['TL_CONFIG']['urlSuffix'];

		if ($GLOBALS['TL_CONFIG']['disableAlias'])
		{
			$strRequest = '';

			if ($strParams)
			{
				$arrChunks = explode('/', preg_replace('@^/@', '', $strParams));

				for ($i=0; $i<count($arrChunks); $i=($i+2))
				{
					$strRequest .= sprintf('&%s=%s', $arrChunks[$i], $arrChunks[($i+1)]);
				}
			}

			$strUrl = 'index.php?id=' . $arrRow['id'] . $strRequest;
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['generateFrontendUrl']) && is_array($GLOBALS['TL_HOOKS']['generateFrontendUrl']))
		{
			foreach ($GLOBALS['TL_HOOKS']['generateFrontendUrl'] as $callback)
			{
				$this->import($callback[0]);
				$strUrl = $this->$callback[0]->$callback[1]($arrRow, $strParams, $strUrl);
			}
		}

		return $strUrl;
	}
			
}

?>