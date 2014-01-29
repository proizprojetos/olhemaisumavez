<?php
/**
 * FeedGator System (Automator) Plugin
 * @version	3.0a1
 * @author	Matt Faulds
 * @license	GPL 2
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );
jimport( 'joomla.registry.registry' );

class plgSystemFeedGator extends JPlugin
{
	protected $interval			= 300;

	function plgSystemFeedGator( &$subject, $params )
	{
		parent::__construct( $subject, $params );

		$this->plugin	= JPluginHelper::getPlugin('system', 'feedgator_system');
		$this->params	= new JRegistry($this->plugin->params);

		$this->interval	= (int) ($this->params->get('interval', 5)*60);

		// correct value if value is under the minimum
		if ($this->interval < 300) { $this->interval = 300; }
	}

	function onAfterRoute()
	{
		$app = JFactory::getApplication();

		//FG Automator import
		if ($app->isSite() AND $this->params->get('fgautomator',0)) {
			$now = JFactory::getDate();
			$now = $now->toUnix();

			if($last = $this->params->get('last_import')) {
				$diff = $now - $last;
			} else {
				$diff = $this->interval+1;
			}

			if ($diff > $this->interval) {

				$jlang = JFactory::getLanguage();
				// Back-end translation
				$jlang->load('com_feedgator', JPATH_ADMINISTRATOR, 'en-GB', true);
				$jlang->load('com_feedgator', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
				$jlang->load('com_feedgator', JPATH_ADMINISTRATOR, null, true);

				require_once(JPATH_ADMINISTRATOR.'/components/com_feedgator/controller.php');
				require_once(JPATH_ADMINISTRATOR.'/components/com_feedgator/factory.feedgator.php');
				require_once(JPATH_ADMINISTRATOR.'/components/com_feedgator/helpers/feedgator.helper.php');
				require_once(JPATH_ADMINISTRATOR.'/components/com_feedgator/helpers/feedgator.utility.php');
				if(file_exists(JPATH_ROOT.'/plugins/system/addkeywords.php')) {
					require_once(JPATH_ROOT.'/plugins/system/addkeywords.php' );
				}
				FeedgatorUtility::profiling('Start pseudo-cron');

				define('SPIE_CACHE_AGE', 60*10);

				require_once(JPATH_ADMINISTRATOR.'/components/com_feedgator/inc/simplepie131.php');
				require_once(JPATH_ADMINISTRATOR.'/components/com_feedgator/inc/simplepie/overrides.php');
				FeedgatorUtility::profiling('Loaded SimplePie');

				JRequest::setVar('task','fgautomator','get');
				JRequest::setVar(JSession::getFormToken(),'1','get');

				$config = array('base_path'=>JPATH_ADMINISTRATOR.'/components/com_feedgator');
				$controller = new FeedgatorController($config);
				if($result = $controller->import('all')) {
					jimport( 'joomla.registry.format' );
					$db		= JFactory::getDbo();
					$this->params->set('last_import',$now);
					$handler = JRegistryFormat::getInstance('json');
					$params = new JObject();
					$params->set('interval',$this->params->get('interval',5));
					$params->set('last_import',$now);
					$params = $handler->objectToString($params,array());

					$query = 	'UPDATE #__extensions'.
								' SET params='.$db->Quote($params).
								' WHERE element = '.$db->Quote('plg_sys_feedgator').
								' AND folder = '.$db->Quote('system').
								' AND enabled >= 1'.
								' AND type ='.$db->Quote('plugin').
								' AND state >= 0';
					$db->setQuery($query);
					$db->query();
				}
				FeedgatorUtility::profiling('End');
			}
		}
	}
}