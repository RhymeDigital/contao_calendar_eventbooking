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



class ModuleEventsSurveyLogin extends \Module
{

	/**
	 * Template
	 * @var string
	 */


	protected $strTemplate = 'mod_eventssurveylogin';
		

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### EVENTS SURVEYS LOGIN &#169; 360Fusion 2012 ###';
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
				
				global $objPage;
				$GLOBALS['TL_LANGUAGE'] = $objPage->language;
			
				$completed_pageID = $this->evtsurveys_jumpTo;	
				$redirect_to_completed_page = $this->Environment->base.$this->getPageFromID($completed_pageID);	
				$this->Template->enctype = 'multipart/form-data';
				$this->Template->action = $this->Environment->base.$this->Environment->request;
				$this->Template->strField = 'email';
		
				  if ($this->Input->post('FORM_SUBMIT') == 'submitted'){
				 	// check if email address exists in the DB
						$details = $this->Database->prepare("SELECT email,firstname,lastname FROM tl_event_registration WHERE email = ?")->limit(1)->execute($this->Input->post('email'));
				  	if ($details->email){	
				  		$_SESSION['EVTREG']['email'] = $details->email;
				  		$_SESSION['EVTREG']['firstname'] = $details->firstname;
				  		$_SESSION['EVTREG']['lastname'] = $details->lastname;
				  		$memberDetails = $this->Database->prepare("SELECT id FROM tl_member WHERE email = ?")->limit(1)->execute($this->Input->post('email'));		
				  		$_SESSION['EVTREG']['id'] = $memberDetails->id;
				  		header('Location: '.$redirect_to_completed_page.'');
				  	}	else { $this->Template->hasError = '<p class="error">Email address not found</p>'; }
				  }
		  	return;
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