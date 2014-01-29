<?php

/**
* FeedGator - Aggregate RSS newsfeed content into a Joomla! database
* @version 3.0
* @package FeedGator
* @author Matt Faulds
* @email mattfaulds@gmail.com
* @copyright (C) 2012 Matthew Faulds - All rights reserved
* @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
*
**/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.installer.installer');
jimport('joomla.filesystem.file');

require_once (JPATH_ADMINISTRATOR.'/components/com_feedgator/factory.feedgator.php');
require_once(JPATH_ADMINISTRATOR.'/components/com_feedgator/helpers/feedgator.helper.php');
require_once(JPATH_ADMINISTRATOR.'/components/com_feedgator/helpers/feedgator.utility.php');
JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_feedgator/tables');
JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_feedgator/models');

class plgfeedgatorplg_fg_contentInstallerScript
{
	public function __constructor( $adapter)
	{

	}

	public function postflight($route, $adapter)
	{
		if($route = 'install') {
			$db = JFactory::getDBO();
			$doc = JFactory::getDocument();
			$pluginModel = FGFactory::getPluginModel();

			$ext = 'com_content';

			$query = "SELECT COUNT(*) FROM `#__feedgator_plugins` WHERE extension='$ext'";
			$db->setQuery($query);
			if ( $db->loadResult() == 0 ) {
				$pluginModel->setExt($ext);
				$xmlfile = $pluginModel->getFilePath();

				$row = JTable::getInstance('FGPlugin','Table');
				$row->extension = $ext;
				$row->published = 0;
				$row->params = '-1{'.$pluginModel->_loadDefaultParams().'}';
				$row->store();
			}

			$doc->addStyleSheet('components/com_feedgator/css/styles.css');

			?>
			<div style="padding-left:10px;padding-bottom:10px">
				<h2><?php echo JText::_('FeedGator Plugin Installation Status'); ?></h2>
				<br />
				<br />
				<strong class="green">FeedGator Joomla! content plugin installation successful!</strong>
				<br />
				<br />
			</div>
			<?php
		}
	}
}