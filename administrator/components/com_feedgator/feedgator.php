<?php
/**
* FeedGator - Aggregate RSS newsfeed content into a Joomla! database
* @version 3.0a1
* @package FeedGator
* @author Original author Stephen Simmons
* @now continued and modified by Matt Faulds, Remco Boom & Stephane Koenig and others
* @email mattfaulds@gmail.com
* @Joomla 1.5 Version by J. Kapusciarz (mrjozo)
* @copyright (C) 2005 by Stephen Simmons - All rights reserved
* @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
*
**/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Require the base controller
require_once (JPATH_ADMINISTRATOR.'/components/com_feedgator/controller.php');
require_once (JPATH_ADMINISTRATOR.'/components/com_feedgator/factory.feedgator.php');
require_once (JPATH_ADMINISTRATOR.'/components/com_feedgator/toolbar/toolbar.feedgator.php');
require_once (JPATH_ADMINISTRATOR.'/components/com_feedgator/helpers/feedgator.helper.php');
require_once (JPATH_ADMINISTRATOR.'/components/com_feedgator/helpers/feedgator.utility.php');
FeedgatorUtility::profiling('Start');

define('SPIE_CACHE_AGE', 60*10);
require_once(JPATH_ADMINISTRATOR.'/components/com_feedgator/inc/simplepie/simplepie131.php');
require_once(JPATH_ADMINISTRATOR.'/components/com_feedgator/inc/simplepie/overrides.php');
FeedgatorUtility::profiling('Loaded SimplePie');

$jlang = JFactory::getLanguage();
// Back-end translation
$jlang->load('com_feedgator', JPATH_ADMINISTRATOR, 'en-GB', true);
$jlang->load('com_feedgator', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
$jlang->load('com_feedgator', JPATH_ADMINISTRATOR, null, true);


$controller = new FeedgatorController();

$task = JFactory::getApplication()->input->get('task','','CMD');

switch (strtolower($task))
{
	case 'add'  :
	case 'new'  :
	case 'edit' :
		$controller->editFeed();
		break;
	case 'copy' :
		$controller->copyFeed();
		break;
	case 'editdefault' :
		$controller->editFeed(true);
		break;
	case 'apply' :
		$controller->saveFeed(true);
		break;
	case 'applydefault' :
		$controller->saveFeed(true,true);
		break;
	case 'save' :
		$controller->saveFeed();
		break;
	case 'savedefault' :
		$controller->saveFeed(false,true);
		break;
	case 'applysettings' :
		$controller->saveSettings(true);
		break;
	case 'publish' :
		$controller->publishFeeds(1,'publish');
		break;
	case 'unpublish' :
		$controller->publishFeeds(0,'unpublish');
		break;
	case 'front_yes' :
		$controller->frontpageFeeds(1,'front_yes');
		break;
	case 'front_no' :
		$controller->frontpageFeeds(0,'front_no');
		break;
	case '' :
	case null:
		$controller->cpanel();
		break;
	default :
		$controller->execute( $task );
		break;
}
// Redirect if set by the controller
$controller->redirect();

FeedgatorUtility::profiling('End');