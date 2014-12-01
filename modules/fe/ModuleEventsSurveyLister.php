<?php 
namespace Contao;
if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 *
 * The TYPOlight webCMS is an accessible web content management system that 
 * specializes in accessibility and generates W3C-compliant HTML code. It 
 * provides a wide range of functionality to develop professional websites 
 * including a built-in search engine, form generator, file and user manager, 
 * CSS engine, multi-language support and many more. For more information and 
 * additional TYPOlight applications like the TYPOlight MVC Framework please 
 * visit the project website http://www.typolight.org.
 *
 * PHP version 5
 * @copyright  360fusion  2011
 * @author     Darrell Martin <darrell@360fusion.co.uk>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */



class ModuleEventsSurveyLister extends \Module
{

	/**
	 * Template
	 * @var string
	 */


	protected $strTemplate = 'mod_eventssurveylister';
		

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### EVENTS SURVEYS LISTER &#169; 360Fusion 2012 ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'typolight/main.php?do=modules&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		
	//	global $objPage;
	//	$GLOBALS['TL_LANGUAGE'] = $objPage->language;
		

		
		$this->Template->email = $_SESSION['EVTREG']['email'];
		$this->Template->firstname = $_SESSION['EVTREG']['firstname'];
		$this->Template->lastname = $_SESSION['EVTREG']['lastname'];
		
		$events = $this->Database->prepare("SELECT eventID FROM tl_event_registration WHERE email=? AND attended=?")->execute($this->Template->email, 1);
		
			$theseEvents = array();
			$arrEvents = array();
			
			  while ($events->next()) {
			  	array_push($theseEvents, $events->eventID);
			  } 
			$theseEvents = array_unique($theseEvents);

	    
	    
	    for ($i=0; $i < count($theseEvents); $i++){
				$thisEvent = $this->Database->prepare("SELECT * FROM tl_calendar_events WHERE id = ?")->limit(1)->execute($theseEvents[$i]);
	
	
	
			$download = 0;
			$download =  $_GET['dl']; 
			
			
			
			// check to see if the user has completed the survey and parse a flag to the template
			$participant = $this->Database->prepare("SELECT finished FROM tl_survey_participant WHERE pid=? AND email=? AND eventid=?")->limit(1)->execute($thisEvent->evtreg_survey_select,$_SESSION['EVTREG']['email'],$theseEvents[$i]);		






				$arrEvents[] = array
				(
				'eventID' => $theseEvents[$i],
				'raw'			=> $thisEvent->row(),
				'date'			=> $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $thisEvent->startDate),
				'title'			=> $thisEvent->title,
				'finished' 	=> $participant->finished,
 				'coursename'=> $thisEvent->evtreg_certificate_coursename,
 			  'courselocation' => $thisEvent->evtreg_certificate_location,
  			'coursedates' => $thisEvent->evtreg_certificate_coursedates,
				'surveyid'			=> $thisEvent->evtreg_survey_select,
				'surveylink' => $this->getPageFromID($this->evtsurveysreader_jumpTo).'?id='.$thisEvent->evtreg_survey_select.'&eid='.$theseEvents[$i],
				'certid'			=> $thisEvent->evtreg_certificate_select,
				'certlink' => $this->Environment->base.$this->Environment->request.'?cn='.urlencode(addslashes($thisEvent->evtreg_certificate_coursename)).'&ct='.urlencode(addslashes($thisEvent->evtreg_certificate_select)).'&cd='.urlencode(addslashes($thisEvent->evtreg_certificate_coursedates)).'&cl='.urlencode(addslashes($thisEvent->evtreg_certificate_location)).'&dl=1'
				);
				
			}


		$this->Template->events = $arrEvents;
		$this->Template->dateLabel = $GLOBALS['TL_LANG']['calevntbooking']['event_date'];
		$this->Template->titleLabel = $GLOBALS['TL_LANG']['calevntbooking']['event_title'];
		$this->Template->surveyLabel = $GLOBALS['TL_LANG']['calevntbooking']['event_survey'];
		$this->Template->certLabel = $GLOBALS['TL_LANG']['calevntbooking']['event_cert'];
		

		if ($download == 1){ $this->outputCertificate(); }

		return;	
	}
	
	
	
	private function outputCertificate()
			{
				  require_once 'Zend/Pdf.php';
					$pdf = Zend_Pdf::load('./system/modules/calendar_eventbooking/assets/certificates/'.urldecode(stripslashes($_GET['ct'])));
					$page = $pdf->pages[0];
					$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
					$page->setFont($font, 14);
					// attendee name
					$this->drawCenteredText($page, $_SESSION['EVTREG']['firstname'].' '.$_SESSION['EVTREG']['lastname'], 530);
					// course name
				  $page->setFillColor(Zend_Pdf_Color_Html::color('#AA0000'));
				  $this->drawCenteredText($page, urldecode(stripslashes($_GET['cn'])), 440);
					$page->setFillColor(Zend_Pdf_Color_Html::color('#000000'));
				  // course location
				  $this->drawCenteredText($page, urldecode(stripslashes($_GET['cl'])), 330);
					// course dates
				  $this->drawCenteredText($page, urldecode(stripslashes($_GET['cd'])), 220);
					header('Content-Type: application/x-download');
					header('Content-Disposition: attachment; filename="certificate.pdf"');
					header('Cache-Control: private, max-age=0, must-revalidate');
					header('Pragma: public');
					header('Content-type: application/pdf');
					echo $pdf->render();	
					return;
			}
	
		
		private function drawCenteredText($page, $text, $bottom) {  
		 foreach (explode(",", $text) as $i => $line) {   
		 	  $text_width = $this->getTextWidth($line, $page->getFont(), $page->getFontSize());
		    $box_width = $page->getWidth()+10;
		    $left = ($box_width - $text_width) / 2;
		    $page->drawText($line, $left, $bottom - $i * ($page->getFontSize()+4), 'UTF-8');
		  }
		}

		private function getTextWidth($text, $resource, $fontSize = null/*, $encoding = null*/) {
		    //if( $encoding == null ) $encoding = 'UTF-8';
		
		    if( $resource instanceof Zend_Pdf_Page ){
		        $font = $resource->getFont();
		        $fontSize = $resource->getFontSize();
		    }elseif( $resource instanceof Zend_Pdf_Resource_Font ){
		        $font = $resource;
		        if( $fontSize === null ) throw new Exception('The fontsize is unknown');
		    }
		
		    if( !$font instanceof Zend_Pdf_Resource_Font ){
		        throw new Exception('Invalid resource passed');
		    }
		
		    $drawingText = $text;//iconv ( '', $encoding, $text );
		    $characters = array ();
		    for($i = 0; $i < strlen ( $drawingText ); $i ++) {
		        $characters [] = ord ( $drawingText [$i] );
		    }
		    $glyphs = $font->glyphNumbersForCharacters ( $characters );
		    $widths = $font->widthsForGlyphs ( $glyphs );
		
		    $textWidth = (array_sum ( $widths ) / $font->getUnitsPerEm ()) * $fontSize;
		    return $textWidth;
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