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



class ModuleEventsSurveyReader extends \Module
{

	/**
	 * Template
	 * @var string
	 */


	protected $strTemplate = null;
		
	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### EVENTS SURVEYS READER &#169; 360Fusion 2012 ###';
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
		
		$_SESSION['EVTREG']['eid'] = $_GET['eid'];

		

		$thisSurvey = $this->Database->prepare("SELECT id,title FROM tl_survey WHERE id=?")->limit(1)->execute($this->Input->get('id'));

		//ok first lets check if there is an article for the survey
		$article = $this->Database->prepare("SELECT title,alias FROM tl_article WHERE title=?")->limit(1)->execute($thisSurvey->title);

		
		if (!$article->title) { // no article exists
				$articlefields['pid'] = $objPage->id;
				$articlefields['tstamp'] = time();
				$articlefields['title'] = $thisSurvey->title;
				$articlefields['alias'] = strtolower(str_replace(" ", "-",$articlefields['title']));
				$articlefields['author'] = 1;
				$articlefields['inColumn'] = 'main';
				$articlefields['published'] = 1;
				$articlefields['sorting'] = 128;	
				$newarticle = $this->Database->prepare("INSERT INTO tl_article %s")->set($articlefields)->execute();
				
				$thisarticle = $this->Database->prepare("SELECT id FROM tl_article WHERE id=?")->limit(1)->execute(mysql_insert_id());
		    if ($thisarticle->numRows){
					$contentfields['pid'] = $thisarticle->id;
					$contentfields['sorting'] = 64;	
					$contentfields['tstamp'] = time();
					$contentfields['type'] = 'survey';
					$contentfields['headline'] = 'a:2:{s:4:"unit";s:2:"h1";s:5:"value";s:0:"";}';
					$contentfields['sortOrder'] = 'ascending';
					$contentfields['mooType'] = 'start';
					$contentfields['perRow'] = 4;
					$contentfields['cssID'] =  'a:2:{i:0;s:0:"";i:1;s:0:"";}';
					$contentfields['space'] =  'a:2:{i:0;s:0:"";i:1;s:0:"";}';
					$contentfields['com_order'] = 'ascending';
					$contentfields['com_template'] = 'com_default';
					$contentfields['survey'] = $this->Input->get('id');
					$contentfields['surveyTpl'] = 'ce_survey';
			  	$newcontent = $this->Database->prepare("INSERT INTO tl_content %s")->set($contentfields)->execute();
				}
		}

		$article = $this->Database->prepare("SELECT title,alias FROM tl_article WHERE title=?")->limit(1)->execute($thisSurvey->title);
		header('Location: 	'.strtolower(str_replace(" ", "-",$objPage->title)).'/articles/'.$article->alias.$GLOBALS['TL_CONFIG']['urlSuffix']);
		return;	
	}
	
	
	
		
	
	
}