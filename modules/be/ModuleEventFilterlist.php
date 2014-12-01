<?php 

namespace Contao;

if (!defined('TL_ROOT')) die('You cannot access this file directly!');

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
 * Class ModuleEventFilterlist
 */
 
class ModuleEventFilterlist extends \Events
{

	/**
	 * Current date object
	 * @var integer
	 */
	protected $Date;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_eventfilterlist';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### EVENT FILTER LIST &#169; 360Fusion 2014 ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		$this->cal_calendar = $this->sortOutProtected(deserialize($this->cal_calendar, true));

		// Return if there are no calendars
		if (!is_array($this->cal_calendar) || count($this->cal_calendar) < 1)
		{
			return '';
		}

		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		$blnClearInput = false;

		global $objPage;
		$this->pageID = $objPage->id;
		$this->pageTitle = $objPage->title;

		// Jump to the current period
		if (!isset($_GET['year']) && !isset($_GET['month']) && !isset($_GET['day']))
		{
			switch ($this->cal_format)
			{
				case 'cal_year':
					$this->Input->setGet('year', date('Y'));
					break;

				case 'cal_month':
					$this->Input->setGet('month', date('Ym'));
					break;

				case 'cal_day':
					$this->Input->setGet('day', date('Ymd'));
					break;
			}

			$blnClearInput = true;
		}

		$blnDynamicFormat = in_array($this->cal_format, array('cal_day', 'cal_month', 'cal_year'));

		// Display year
		if ($blnDynamicFormat && $this->Input->get('year'))
		{
			$this->Date = new \Date($this->Input->get('year'), 'Y');
			$this->cal_format = 'cal_year';
			$this->headline .= ' ' . date('Y', $this->Date->tstamp);
		}

		// Display month
		elseif ($blnDynamicFormat && $this->Input->get('month'))
		{
			$this->Date = new \Date($this->Input->get('month'), 'Ym');
			$this->cal_format = 'cal_month';
			$this->headline .= ' ' . $this->parseDate('F Y', $this->Date->tstamp);
		}

		// Display day
		elseif ($blnDynamicFormat && $this->Input->get('day'))
		{
			$this->Date = new \Date($this->Input->get('day'), 'Ymd');
			$this->cal_format = 'cal_day';
			$this->headline .= ' ' . $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $this->Date->tstamp);
		}

		// Display all events or upcoming/past events
		else
		{
			$this->Date = new \Date();
		}

		list($strBegin, $strEnd, $strEmpty) = $this->getDatesFromFormat($this->Date, $this->cal_format);

		// Get all events
		$arrAllEvents = $this->getAllEvents($this->cal_calendar, $strBegin, $strEnd);
		($this->cal_order == 'descending') ? krsort($arrAllEvents) : ksort($arrAllEvents);

		$arrEvents = array();
		$dateBegin = date('Ymd', $strBegin);
		$dateEnd = date('Ymd', $strEnd);

		// Remove events outside the scope
		foreach ($arrAllEvents as $key=>$days)
		{
			if ($key < $dateBegin || $key > $dateEnd)
			{
				continue;
			}

			foreach ($days as $day=>$events)
			{
				foreach ($events as $event)
				{
					$event['firstDay'] = $GLOBALS['TL_LANG']['DAYS'][date('w', $day)];
					$event['firstDate'] = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $day);

					$arrEvents[] = $event;
				}
			}
		}
		
// echo '<br>'.$event['href'];

		unset($arrAllEvents);
		$total = count($arrEvents);
		$limit = $total;
		$offset = 0;

		// Overall limit
		if ($this->cal_limit > 0)
		{
			$total = min($this->cal_limit, $total);
			$limit = $total;
		}

		// Pagination
		if ($this->perPage > 0)
		{
			$page = $this->Input->get('page') ? $this->Input->get('page') : 1;
			$offset = ($page - 1) * $this->perPage;
			$limit = min($this->perPage + $offset, $total);

			$objPagination = new \Pagination($total, $this->perPage);
			$this->Template->pagination = $objPagination->generate("\n  ");
		}

		$strMonth = '';
		$strDate = '';
		$strEvents = '';
		$dayCount = 0;
		$eventCount = 0;
		$headerCount = 0;
		$imgSize = false;

		// Override the default image size
		if ($this->imgSize != '')
		{
			$size = deserialize($this->imgSize);

			if ($size[0] > 0 || $size[1] > 0)
			{
				$imgSize = $this->imgSize;
			}
		}

		// Parse events
		for ($i=$offset; $i<$limit; $i++)
		{
			$event = $arrEvents[$i];
			$blnIsLastEvent = false;

			// Last event on the current day
			if (($i+1) == $limit || !isset($arrEvents[($i+1)]['firstDate']) || $event['firstDate'] != $arrEvents[($i+1)]['firstDate'])
			{
				$blnIsLastEvent = true;
			}

			$objTemplate = new \FrontendTemplate($this->cal_template);
			$objTemplate->setData($event);

			// Month header
			if ($strMonth != $event['month'])
			{
				$objTemplate->newMonth = true;
				$strMonth = $event['month'];
			}

			// Day header
			if ($strDate != $event['firstDate'])
			{
				$headerCount = 0;
				$objTemplate->header = true;
				$objTemplate->classHeader = ((($dayCount % 2) == 0) ? ' even' : ' odd') . (($dayCount == 0) ? ' first' : '') . (($event['firstDate'] == $arrEvents[($limit-1)]['firstDate']) ? ' last' : '');
				$strDate = $event['firstDate'];

				++$dayCount;
			}




			// Add template variables
			$objTemplate->link = $event['href'];
			$objTemplate->classList = $event['class'] . ((($headerCount % 2) == 0) ? ' even' : ' odd') . (($headerCount == 0) ? ' first' : '') . ($blnIsLastEvent ? ' last' : '') . ' cal_' . $event['parent'];
			$objTemplate->classUpcoming = $event['class'] . ((($eventCount % 2) == 0) ? ' even' : ' odd') . (($eventCount == 0) ? ' first' : '') . ((($offset + $eventCount + 1) >= $limit) ? ' last' : '') . ' cal_' . $event['parent'];
			$objTemplate->readMore = specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['readMore'], $event['title']));
			$objTemplate->more = $GLOBALS['TL_LANG']['MSC']['more'];


 			$eventDetails = $this->Database->prepare("SELECT evtreg_availableSpaces, evtreg_availableSpacesRemaining, evtreg_lastDate FROM tl_calendar_events WHERE id=?")->limit(1)->execute($event['id']);
       	if ($eventDetails->evtreg_availableSpaces != NULL) {
             $lastBookingDate = date("l F dS Y", $eventDetails->evtreg_lastDate);
   			   $objTemplate->lastBookingDate = $lastBookingDate;
	          $objTemplate->spacesRemaining = $eventDetails->evtreg_availableSpacesRemaining;
			   $objTemplate->availableSpaces = $eventDetails->evtreg_availableSpaces;
			 }
			

			// Short view
			if ($this->cal_noSpan)
			{
				$objTemplate->day = $event['day'];
				$objTemplate->date = $event['date'];
				$objTemplate->span = ($event['time'] == '' && $event['day'] == '') ? $event['date'] : '';
			}
			else
			{
				$objTemplate->day = $event['firstDay'];
				$objTemplate->date = $event['firstDate'];
				$objTemplate->span = '';
			}

			$objTemplate->addImage = false;

			// Add an image
			if ($event['addImage'] && $event['singleSRC'] != '')
			{
				$objModel = \FilesModel::findByUuid($event['singleSRC']);

				if ($objModel === null)
				{
					if (!\Validator::isUuid($event['singleSRC']))
					{
						$objTemplate->text = '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
					}
				}
				elseif (is_file(TL_ROOT . '/' . $objModel->path))
				{
					if ($imgSize)
					{
						$event['size'] = $imgSize;
					}

					$event['singleSRC'] = $objModel->path;
					$this->addImageToTemplate($objTemplate, $event);
				}
			}

			$objTemplate->enclosure = array();

			// Add enclosure
			if ($event['addEnclosure'])
			{
				$this->addEnclosuresToTemplate($objTemplate, $event);
			}

			$strEvents .= $objTemplate->parse();

			++$eventCount;
			++$headerCount;
		}

		// No events found
		if ($strEvents == '')
		{
			$strEvents = "\n" . '<div class="empty">' . $strEmpty . '</div>' . "\n";
		}

		$this->Template->events = $strEvents;

		// Clear the $_GET array (see #2445)
		if ($blnClearInput)
		{
			$this->Input->setGet('year', null);
			$this->Input->setGet('month', null);
			$this->Input->setGet('day', null);
		}
		
			/**
	 * Get all events of a certain period
	 * @param array
	 * @param integer
	 * @param integer
	 * @return array
	 */
		
	}
	
		protected function getAllEvents($arrCalendars, $intStart, $intEnd)
	{
		if (!is_array($arrCalendars))
		{
			return array();
		}

		$this->import('String');

		$time = time();
		$this->arrEvents = array();

		foreach ($arrCalendars as $id)
		{
			$strUrl = $this->strUrl;
			

			// Get current "jumpTo" page
			$objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=(SELECT jumpTo FROM tl_calendar WHERE id=?)")
									  ->limit(1)
									  ->execute($id);

			if ($objPage->numRows)
			{
				$strUrl = $this->generateFrontendUrl($objPage->row(), '/%s');
			}

//echo '<br>'.$strUrl;

			// Get events of the current period
			$objEvents = $this->Database->prepare("SELECT *, (SELECT title FROM tl_calendar WHERE id=?) AS calendar, (SELECT name FROM tl_user WHERE id=author) author FROM tl_calendar_events WHERE pid=? AND ((startTime>=? AND startTime<=?) OR (endTime>=? AND endTime<=?) OR (startTime<=? AND endTime>=?) OR (recurring=1 AND (recurrences=0 OR repeatEnd>=?) AND startTime<=?))" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1" : "") . " ORDER BY startTime")
										->execute($id, $id, $intStart, $intEnd, $intStart, $intEnd, $intStart, $intEnd, $intStart, $intEnd);

			if ($objEvents->numRows < 1)
			{
				continue;
			}

/*
			$objParents = $this->Database->prepare("SELECT id, title FROM tl_calendar")
									  ->execute();
			
			if (!$objParents->numRows) { 		  
					return;
			}

			$parents = array();
		//	$i = 0;
			while($objParents->next())
			{
			//	$parents[$objParents->id] = $objParents->id;
				$parents[$objParents->id][title] = $objParents->title;
		//	    $i++;
			}
*/		
		
				
		//	print_r($parents);


//	echo 'PageID: '.$this->pageID.'<br/>';
// echo $objPage->id.'<br/>';
// echo $this->pageID.'<br/>';
// echo 'Title: '.$this->pageTitle.'<br/><br/>';

			while ($objEvents->next())
			{
	
				
 // echo $objEvents->pid.'<br/>';
 // echo $objEvents->eventselectpageid.'<br/>';
 // echo 'this->pageID: '.$this->pageID.'<br>';


	foreach (deserialize($objEvents->eventselectpageid, true) as $value) {
		//echo $value;
		if ($this->pageID == $value) {
			$this->addEvent($objEvents, $objEvents->startTime, $objEvents->endTime, $strUrl, $intStart, $intEnd, $id);
		}
	}

	//	$this->eventselectpageids = deserialize($objEvents->eventselectpageid);
	//  echo 'this->eventselectpageids: ';	
	//  print_r($this->eventselectpageids);



// echo $parents[$objEvents->pid][title].'<br/>';

/*

echo 'this->pageTitle: '.$this->pageTitle.'<br>';
echo 'parents[objEvents->pid][title]: '.$parents[$objEvents->pid][title].'<br>';
*/



/*
if (($objEvents->eventselectpageid == $this->pageID) || ($parents[$objEvents->pid][title] == $this->pageTitle)){
					$this->addEvent($objEvents, $objEvents->startTime, $objEvents->endTime, $strUrl, $intStart, $intEnd, $id);
	}
*/



			    
/*
   switch ($this->pageTitle)
				{
				case 'Courses':
				if ($parents[$objEvents->pid][title] == 'External Courses') {
						$this->addEvent($objEvents, $objEvents->startTime, $objEvents->endTime, $strUrl, $intStart, $intEnd, $id);
				}
				  break;
				case 'Events':
				if ($parents[$objEvents->pid][title] == 'External Events') {
						$this->addEvent($objEvents, $objEvents->startTime, $objEvents->endTime, $strUrl, $intStart, $intEnd, $id);
				}
				  break;
				}
				
*/				
				
				// Recurring events
				if ($objEvents->recurring)
				{
					$count = 0;
					$arrRepeat = deserialize($objEvents->repeatEach);

					while ($objEvents->endTime < $intEnd)
					{
						if ($objEvents->recurrences > 0 && $count++ >= $objEvents->recurrences)
						{
							break;
						}

						$arg = $arrRepeat['value'];
						$unit = $arrRepeat['unit'];

						if ($arg < 1)
						{
							break;
						}

						$strtotime = '+ ' . $arg . ' ' . $unit;

						$objEvents->startTime = strtotime($strtotime, $objEvents->startTime);
						$objEvents->endTime = strtotime($strtotime, $objEvents->endTime);

						// Skip events outside the scope
						if ($objEvents->endTime < $intStart || $objEvents->startTime > $intEnd)
						{
							continue;
						}

	foreach (deserialize($objEvents->eventselectpageid, true) as $value) {
		//echo $value;
		if ($this->pageID == $value) {
			$this->addEvent($objEvents, $objEvents->startTime, $objEvents->endTime, $strUrl, $intStart, $intEnd, $id);
		}
	}
/*
						if ($objEvents->eventselectpageid == $this->pageID){
							$this->addEvent($objEvents, $objEvents->startTime, $objEvents->endTime, $strUrl, $intStart, $intEnd, $id);
						}
*/						
						
						
					}
				}
			}
		}

		// Sort data
		foreach (array_keys($this->arrEvents) as $key)
		{
			ksort($this->arrEvents[$key]);
		}

		// HOOK: modify result set
		if (isset($GLOBALS['TL_HOOKS']['getAllEvents']) && is_array($GLOBALS['TL_HOOKS']['getAllEvents']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getAllEvents'] as $callback)
			{
				$this->import($callback[0]);
				$this->arrEvents = $this->$callback[0]->$callback[1]($this->arrEvents, $arrCalendars, $intStart, $intEnd, $this);
			}
		}

		return $this->arrEvents;
	}
}

?>