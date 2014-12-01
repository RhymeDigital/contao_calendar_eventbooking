-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************



-- 
-- Table `tl_survey_participant`
-- 

CREATE TABLE `tl_survey_participant` (
  `eventid` int(10) unsigned NOT NULL default '0',
  KEY `eventid` (`eventid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------



-- 
-- Table `tl_module`
-- 

CREATE TABLE `tl_module` (
  `cal_eventregistrationpage` int(10) unsigned NOT NULL default '0',
  `evtreg_success_jumpTo` int(11) NOT NULL default '0',
  `evtreg_error_jumpTo` int(11) NOT NULL default '0',
  `evtsurveys_jumpTo` int(11) NOT NULL default '0',
  `evtsurveysreader_jumpTo` int(11) NOT NULL default '0',
  `evtreg_template` varchar(255) NOT NULL default '',
 	`cal_calendar` blob NULL,
  `cal_noSpan` char(1) NOT NULL default '',
  `cal_startDay` smallint(5) unsigned NOT NULL default '1',
  `cal_format` varchar(32) NOT NULL default '',
  `cal_order` varchar(32) NOT NULL default '',
  `cal_limit` smallint(5) unsigned NOT NULL default '0',
  `cal_template` varchar(32) NOT NULL default '',
  `cal_ctemplate` varchar(32) NOT NULL default '',
  `cal_showQuantity` char(1) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------


-- 
-- Table `tl_article`
-- 

CREATE TABLE `tl_article` (
  KEY `title` (`title`),
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------



-- 
-- Table `tl_calendar_events`
-- 


CREATE TABLE `tl_calendar_events` (
  `evtreg_register` char(1) NOT NULL default '',
  `evtreg_lastDate` varchar(10) NOT NULL default '',
  `evtreg_availableSpaces` varchar(255) NOT NULL default '',  
  `evtreg_formHeadline` varchar(255) NOT NULL default '',
  `evtreg_availableSpacesRemaining` varchar(255) NOT NULL default '',  
  `evtreg_cost` varchar(10) NOT NULL default '',
  `evtreg_registrationFields` blob NULL,
  `evtreg_survey` char(1) NOT NULL default '',
  `evtreg_survey_select` int(10) unsigned NOT NULL default '0',
  `evtreg_certificate` char(1) NOT NULL default '',
  `evtreg_certificate_select` varchar(255) NOT NULL default '',
  `evtreg_certificate_coursename` blob NULL,
  `evtreg_certificate_location` blob NULL,
  `evtreg_certificate_coursedates` blob NULL
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_evtreg_export`
-- 

CREATE TABLE `tl_evtreg_export` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `eventTitle` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_event_registration`
-- 

CREATE TABLE `tl_event_registration` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `eventID` int(10) unsigned NOT NULL default '0',
  `eventName` varchar(255) NOT NULL default '',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `jobTitle` varchar(255) NOT NULL default '',
  `gmcNumber` varchar(255) NOT NULL default '',
  `dietaryRequirements` varchar(255) NOT NULL default '',
  `disabilities` varchar(255) NOT NULL default '',
  `firstname` varchar(255) NOT NULL default '',
  `lastname` varchar(255) NOT NULL default '',
  `addressOne` varchar(255) NOT NULL default '',
  `addressTwo` varchar(255) NOT NULL default '',
  `city` varchar(255) NOT NULL default '',
  `county` varchar(64) NOT NULL default '',
  `postcode` varchar(32) NOT NULL default '',
  `country` varchar(2) NOT NULL default '',
  `dateOfBirth` varchar(11) NOT NULL default '',
  `gender` varchar(32) NOT NULL default '',
  `company` varchar(255) NOT NULL default '',
  `phone` varchar(64) NOT NULL default '',
  `mobile` varchar(64) NOT NULL default '',
  `fax` varchar(64) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `website` varchar(255) NOT NULL default '',
  `notes` blob NULL,
  `date` varchar(255) NOT NULL default '',
  `dinner` varchar(255) NOT NULL default '',
  `transport` varchar(255) NOT NULL default '',
  `user_photo` varchar(255) NULL,
  `transactionid` varchar(255) NOT NULL default '',
  `attended` char(1) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `attended` (`attended`),
  KEY `email` (`email`),
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

-- 
-- Table `tl_evtreg_mail`
-- 

CREATE TABLE `tl_evtreg_mail` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `senderName` varchar(255) NOT NULL default '',
  `sender` varchar(255) NOT NULL default '',
  `cc` varchar(255) NOT NULL default '',
  `bcc` varchar(255) NOT NULL default '',
  `template` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_evtreg_mail_content`
-- 

CREATE TABLE `tl_evtreg_mail_content` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `language` varchar(255) NOT NULL default '',
  `fallback` char(1) NOT NULL default '',
  `subject` varchar(255) NOT NULL default '',
  `text` text NULL,
  `textOnly` char(1) NOT NULL default '',
  `html` text NULL,
  `attachments` blob NULL,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_evtreg_config`
-- 

CREATE TABLE `tl_evtreg_config` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `label` varchar(255) NOT NULL default '',
  `adminEmail` varchar(255) NOT NULL default '',
  `emailRegVerification` int(1) unsigned NOT NULL default '0',
  `emailAdminVerification` int(1) unsigned NOT NULL default '0',
  `emailRegTemplate` char(1) NOT NULL default '',
  `emailAdminTemplate` char(1) NOT NULL default '',
  `archive` int(1) unsigned NOT NULL default '0',
  `payment` char(1) NOT NULL default '',
  `checkout_jumpTo` int(11) NOT NULL default '0',
  `success_jumpTo` int(11) NOT NULL default '0',
  `error_jumpTo` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

-- 
-- Table `tl_evtreg_mods`
-- 

CREATE TABLE `tl_evtreg_mods` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `type` varchar(64) NOT NULL default '',
  `label` varchar(255) NOT NULL default '',
  `paypal_standard` varchar(255) NOT NULL default '',
  `api_username` varchar(255) NOT NULL default '',
  `api_password` varchar(255) NOT NULL default '',
  `api_signature` varchar(255) NOT NULL default '',
  `identity_token` varchar(255) NOT NULL default '',
  `payment_type` varchar(20) NOT NULL default '',
  `currency_code` varchar(3) NOT NULL default '',
  `environment` varchar(20) NOT NULL default '',
  `allowedcc_types` blob NULL,
  `guests` char(1) NOT NULL default '',
  `protected` char(1) NOT NULL default '',
  `groups` blob NULL,
  `debug` char(1) NOT NULL default '',
  `enabled` char(1) NOT NULL default '',
  `archive` int(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_evtreg_types`
-- 

CREATE TABLE `tl_evtreg_types` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `enabled` char(1) NOT NULL default '',
  PRIMARY KEY  (`id`),
) ENGINE=MyISAM DEFAULT CHARSET=utf8;