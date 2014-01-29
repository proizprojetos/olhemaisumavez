#!/usr/bin/php
<?php

/**
* FeedGator - Aggregate RSS newsfeed content into a Joomla! database
* @version 2.4
* @package FeedGator
* @author Original author Stephen Simmons
* @now continued and modified by Matt Faulds, Remco Boom & Stephane Koenig and others
* @email mattfaulds@gmail.com
* @Joomla 1.5 Version by J. Kapusciarz (mrjozo)
* @copyright (C) 2005 by Stephen Simmons - All rights reserved
* @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
*
**/

// Please note that you may wish to remove the hashbang placed at the top of this file before the php start tag
// You may also need to edit the path to php for the hashbang

// set error reporting to not warn of session headers already set by hashbang in process.php
error_reporting(E_ERROR | E_PARSE);

define( '_JEXEC', 1 );

define('JPATH_BASE', substr(__FILE__,0,strrpos(__FILE__, DS.'administrator')));

require_once(JPATH_BASE.'/includes/defines.php' );
require_once(JPATH_ADMINISTRATOR.'/includes/framework.php' );
require_once(JPATH_ADMINISTRATOR.'/includes/helper.php' );
require_once(JPATH_ADMINISTRATOR.'/includes/toolbar.php' );

$mainframe = JFactory::getApplication('site');
$mainframe->initialise();

require_once(JPATH_ADMINISTRATOR.'/components/com_feedgator/controller.php');
require_once(JPATH_ADMINISTRATOR.'/components/com_feedgator/factory.feedgator.php');
require_once(JPATH_ADMINISTRATOR.'/components/com_feedgator/helpers/feedgator.helper.php');
require_once(JPATH_ADMINISTRATOR.'/components/com_feedgator/helpers/feedgator.utility.php');

if(file_exists(JPATH_ROOT.'/plugins/system/addkeywords.php')) {
	require_once(JPATH_ROOT.'/plugins/system/addkeywords.php');
}
FeedgatorUtility::profiling('Start cron');

define('SPIE_CACHE_AGE', 60*10);

require_once(JPATH_ADMINISTRATOR.'/components/com_feedgator/inc/simplepie/simplepie131.php');
require_once(JPATH_ADMINISTRATOR.'/components/com_feedgator/inc/simplepie/overrides.php');
FeedgatorUtility::profiling('Loaded SimplePie');

JRequest::setVar('task','cron','get');
JRequest::setVar(JUtility::getToken(),'1','get');

$jlang = JFactory::getLanguage();
// Back-end translation
$jlang->load('com_feedgator', JPATH_ADMINISTRATOR, 'en-GB', true);
$jlang->load('com_feedgator', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
$jlang->load('com_feedgator', JPATH_ADMINISTRATOR, null, true);

$config = array('base_path'=>JPATH_ADMINISTRATOR.'/components/com_feedgator');
$controller = new FeedgatorController($config);
$controller->import('all');

FeedgatorUtility::profiling('End');
echo 'Import finished';