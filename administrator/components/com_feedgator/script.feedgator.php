<?php

/**
* FeedGator - Aggregate RSS newsfeed content into a Joomla! database
* @version 3.0a2
* @package FeedGator
* @author Matt Faulds
* @email mattfaulds@gmail.com
* @copyright (C) 2010 Matthew Faulds - All rights reserved
* @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
*
**/

// no direct access
defined('_JEXEC') or die('Restricted access');

class com_feedgatorInstallerScript
{
    function preflight($route, $adapter) {}

    function install($adapter) {}

    function update($adapter) {}

    function uninstall($adapter)
    {
 		require_once(JPATH_ADMINISTRATOR.'/components/com_feedgator/helpers/feedgator.helper.php');

		/** Remove the settings table */
		$db = JFactory::getDBO();
		$querys[] = 'DROP TABLE IF EXISTS #__feedgator';
		$querys[] = 'DROP TABLE IF EXISTS #__feedgator_plugins';
		$querys[] = 'DROP TABLE iF EXISTS #__feedgator_imports';
		foreach ($querys as $query) {
			$db->setQuery( $query );
			if( $db->query() === FALSE ) {
				echo stripslashes($db->getErrorMsg());
			}
		}
		?>
		<h2><?php echo JText::_('FeedGator Uninstallation Status'); ?></h2>
		<div>Uninstalling version <strong><?php echo FeedgatorHelper::getFGVersion(); ?></strong><br />
		<br />
		Database tables removed. Uninstall complete!
		<br />
		</div>
		<?php
	}


 function postflight($route, $adapter)
 {
 	    jimport('joomla.installer.installer');
		jimport('joomla.filesystem.file');

		require_once(JPATH_ADMINISTRATOR.'/components/com_feedgator/helpers/feedgator.helper.php');
		require_once(JPATH_ADMINISTRATOR.'/components/com_feedgator/helpers/feedgator.utility.php');
		JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_feedgator/tables');
		JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_feedgator/models');

		JHtml::_('behavior.framework');

		$doc = JFactory::getDocument();
		$doc->addStyleSheet('components/com_feedgator/css/styles.css');

		$token = JSession::getFormToken();
		$base = JUri::base();
		$script = "
			window.addEvent( 'domready', function() {
				var base = '$base';
				var btn = $('fgupgradebtn');
				if(btn) {
					btn.addEvent('click', function() {
						var log = $('fgupgrade');
						var url = base + 'index.php?option=com_feedgator&task=upgrade&$token=1';
						new Ajax(url, {
							onRequest: function() {
								btn.setStyle('display','none');
								log.empty().appendText('Upgrading feeds...').addClass('waiting');
							},
							update: log,
							onComplete: function() { log.removeClass('waiting'); }
						}).request();
					});
				}
			});
		";
		$doc->addScriptDeclaration($script);

		$db = JFactory::getDBO();

		$query = 	"CREATE TABLE IF NOT EXISTS `#__feedgator` (
					`id` int(10) NOT NULL auto_increment,
					`title` varchar(100) NOT NULL default 'Untitled',
					`feed` text NOT NULL default '',
					`content_type` varchar(50) NULL,
					`sectionid` int(10) NOT NULL default '0',
					`catid` int(10) NOT NULL default '0',
					`default_author` varchar(100) NULL,
					`default_introtext` varchar(250) NULL,
					`created_by` int(11) NOT NULL default '0',
					`created` datetime NOT NULL default '0000-00-00 00:00:00',
					`checked_out` int(11) unsigned NOT NULL default '0',
					`checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
					`last_run` datetime NOT NULL default '0000-00-00 00:00:00',
					`last_email` int(11) NOT NULL default '0',
					`published` tinyint(1) NOT NULL default '0',
					`front_page` tinyint(1) NOT NULL default '0',
					`filtering` tinyint(1) NOT NULL default '0',
					`filter_whitelist` text NOT NULL default '',
					`filter_blacklist` text NOT NULL default '',
					`params` text NOT NULL default '',
					`imports` text NOT NULL default '',
					PRIMARY KEY  (`id`)
					) ENGINE=MyISAM;";

		$db->setQuery($query);
		$db->query();

		$query = 	"CREATE TABLE IF NOT EXISTS `#__feedgator_imports` (
					`id` int(11) NOT NULL auto_increment,
					`content_id` int(11) NOT NULL,
					`plugin` text NOT NULL,
					`feed_id` int(11) NOT NULL,
					`hash` text NOT NULL,
					PRIMARY KEY (`id`),
					INDEX (`feed_id`),
					INDEX (`content_id`)
					) ENGINE=MyISAM;";

		$db->setQuery( $query );
		$db->query();

		$query = 	"CREATE TABLE IF NOT EXISTS `#__feedgator_plugins` (
					`id` int NOT NULL auto_increment,
					`extension` varchar(100) NOT NULL,
					`published` int(1) default 0,
					`params` text NOT NULL,
					PRIMARY KEY (`id`)
					) ENGINE=MyISAM;";

		$db->setQuery( $query );
		$db->query();

		$installer = new JInstaller;

		$path = $adapter->getParent()->getPath('source');
		$installer->install($path.'/plugins/system/plg_feedgator_system');
		$installer->install($path.'/plugins/feedgator/plg_fg_content');
		$installer->install($path.'/plugins/feedgator/plg_fg_k2');


		//need an uninstall method for the fgautomator plugin

		$table_type = 'extension';
		$query = "SELECT * FROM #__".$table_type."s WHERE element='feedgator_system' AND folder='system'";
		$db->setQuery($query);
		$exist_sys_plg = $db->loadObject();
		if(!$exist_sys_plg) {
			$className = 'JTable'.$table_type;
			require_once(JPATH_LIBRARIES.'/joomla/database/table'.DS.$table_type.'.php');
			$p = new $className($db);
			$p->reorder('`folder` = "system"');
			$query = "UPDATE #__".$table_type."s SET ordering=0 WHERE element='feedgator_system' AND folder='system'";
			$db->setQuery($query);
			$sys_plg = $db->query() ? 1 : 0;
			$p->reorder('`folder` = "system"');
		} else {
			$sys_plg = 1;
		}

		?>
		<div style="padding-left:10px;padding-bottom:10px">
			<h2><?php echo JText::_('FeedGator Installation Status'); ?></h2>
			<?php if(!isset($upgrade)) { ?>
				Installing version: <strong class="blue"><?php echo FeedgatorHelper::getFGVersion(); ?></strong>
				<br />
				<br />
				<strong class="green">Component and internal plugins installation successful!</strong>
				<?php if($sys_plg AND !$exist_sys_plg) { ?>
				<br />
				<br />
				<strong class="green">FeedGator plugin installation successful!</strong>
				<?php } elseif($sys_plg AND $exist_sys_plg) { ?>
				<br />
				<br />
				<strong class="green">FeedGator system plugin upgrade successful!</strong>
				<?php } ?>
				<br />
				<br />
				<br />
				<strong><a href="<?php echo 'index.php?option=com_feedgator&task=feeds'; ?>">Click here to set up your feeds</a></strong>
				<br />
			<?php } else { ?>
				Upgrading version: <strong class="red"><?php echo $from; ?></strong> to <strong class="blue"><?php echo FeedgatorHelper::getFGVersion(); ?></strong>
				<?php if($sys_plg AND $exist_sys_plg) { ?>
				<br />
				<br />
				<strong class="green">FeedGator system plugin installation successful!</strong>
				<?php } elseif($sys_plg AND !$exist_sys_plg) { ?>
				<br />
				<br />
				<strong class="green">FeedGator system plugin upgrade successful!</strong>
				<?php } ?>
				<br />
				<br />
				<div id="fgupgrade">
					<strong><a href="index.php?option=com_feedgator&task=feeds">Click here to go straight to your feeds</a></strong>
				</div>
				<br />
			<?php } ?>
			<br />
			<br />
		</div>
		<?php
	}

}