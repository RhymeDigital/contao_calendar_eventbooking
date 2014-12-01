<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

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
 * Language file for table tl_calendar_events (en).
 *
 * PHP version 5
 * @copyright  360fusion  2011
 * @author     Darrell Martin <darrell@360fusion.co.uk>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


$download = 0;
$download =  $_GET['dl']; 


$thisPath = get_full_url();
$thisPath = str_replace("/contao","",$thisPath);
$thisPath = strrchr($thisPath, '/' );
$thisPath = str_replace('/','',$thisPath);

//print_r($_SERVER);

include($_SERVER['DOCUMENT_ROOT'].$thisPath.'/system/config/localconfig.php');

if ($download == 1){ outputCertificate(); }
	
	
	 function outputCertificate()
			{
				  require_once 'Zend/Pdf.php';
					$pdf = Zend_Pdf::load('../system/modules/calendar_eventbooking/assets/certificates/'.urldecode(stripslashes($_GET['ct'])));
					$page = $pdf->pages[0];
					$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
					$page->setFont($font, 14);
					// attendee name
					drawCenteredText($page, 'Joe Bloggs', 530);
					// course name
				  $page->setFillColor(Zend_Pdf_Color_Html::color('#AA0000'));
				  drawCenteredText($page, urldecode(stripslashes($_GET['cn'])), 440);
					$page->setFillColor(Zend_Pdf_Color_Html::color('#000000'));
				  // course location
				  drawCenteredText($page, urldecode(stripslashes($_GET['cl'])), 330);
					// course dates
				  drawCenteredText($page, urldecode(stripslashes($_GET['cd'])), 220);
					header('Content-Type: application/x-download');
					header('Content-Disposition: attachment; filename="certificate.pdf"');
					header('Cache-Control: private, max-age=0, must-revalidate');
					header('Pragma: public');
					header('Content-type: application/pdf');
					echo $pdf->render();	
					return;
			}
			

		function get_full_url() {
        $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
        return
            ($https ? 'https://' : 'http://').
            (!empty($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'].'@' : '').
            (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ($_SERVER['SERVER_NAME'].
            ($https && $_SERVER['SERVER_PORT'] === 443 ||
            $_SERVER['SERVER_PORT'] === 80 ? '' : ':'.$_SERVER['SERVER_PORT']))).
            substr($_SERVER['SCRIPT_NAME'],0, strrpos($_SERVER['SCRIPT_NAME'], '/'));
    }


		function drawCenteredText($page, $text, $bottom) {  
		 foreach (explode(",", $text) as $i => $line) {   
		 	  $text_width = getTextWidth($line, $page->getFont(), $page->getFontSize());
		    $box_width = $page->getWidth()+10;
		    $left = ($box_width - $text_width) / 2;
		    $page->drawText($line, $left, $bottom - $i * ($page->getFontSize()+4), 'UTF-8');
		  }
		}

		function getTextWidth($text, $resource, $fontSize = null/*, $encoding = null*/) {
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


//$thisEvent = $self->Database->prepare("SELECT * FROM tl_calendar_events WHERE id = ?")->limit(1)->execute($_GET['id']);

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_register'] = array('Bookable event', 'Select to add booking registration to this event.');

$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_lastDate']   = array('Last registration date', 'Please enter the the last date on which registration is still available.');
$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_availableSpaces'] = array('Available spaces', 'Please enter the number of bookable spaces for this event');
$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_formHeadline'] = array('Booking registration page headline', 'Please enter a headline for the event\'s booking registration page');
$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_registrationFields'] = array('Form field selection', 'Please select the event registration fields associated with this event registration form');

$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_registrationMand'] = array('Mandatory form field selection', 'Please select the event registration mandatory fields');

$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_paymentTypes'] = array('Payment Types', 'Please select valid payment types for this event');





$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_cost'] = array('Booking fee', 'Enter booking fee if one applies. Do not enter &pound sign.');

$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_survey'] = array('Survey?', 'Select to add a survey to this event.');
$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_survey_select'] = array('Select survey', 'Select the booking survey associated with this event.');

$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_certificate'] = array('Certificate', 'Select to add a certificate to this event.');
$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_certificate_select'] = array('Select  certificate', 'Select the certificate associated with this event.');

$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_certificate_coursename'] = array('Certificate course name', 'Course name that will appear on the selected certificate');
$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_certificate_location'] = array('Certificate course location', 'Course location that will appear on the selected certificate');
$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_certificate_coursedates'] = array('Certificate course dates', 'Course dates that will appear on the selected certificate');
//$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_certificate_preview'] = array('<a href="'.$this->Environment->base.$this->Environment->request.'&cn='.urlencode(addslashes($thisEvent->evtreg_certificate_coursename)).'&ct='.urlencode(addslashes($thisEvent->evtreg_certificate_select)).'&cd='.urlencode(addslashes($thisEvent->evtreg_certificate_coursedates)).'&cl='.urlencode(addslashes($thisEvent->evtreg_certificate_location)).'&dl=1"><u>Certificate preview</u></a>', 'Click the link above to preview certificate');

$GLOBALS['TL_LANG']['tl_calendar_events']['eventselectentry_legend']  = 'Event Association';
$GLOBALS['TL_LANG']['tl_calendar_events']['eventselectpageid']  = array('Pages', 'Please select the pages that will display this event.');




/**
 * legends
 */

$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_legend_register'] = 'Event Booking';
$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_legend_survey'] = 'Survey Settings';
$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_legend_certificate'] = 'Certificate Settings';

/**
 * Buttons
 */

$GLOBALS['TL_LANG']['tl_calendar_events']['evtreg_group'] = array('Event Attendance Group', 'Show attendance group for Event ID %s');


?>