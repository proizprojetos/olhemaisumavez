<?php

/**
* FeedGator - Aggregate RSS newsfeed content into a Joomla! database
* @version 3.0a1
* @package FeedGator
* @author Matt Faulds
* @email mattfaulds@gmail.com
* @copyright (C) 2010 Matthew Faulds - All rights reserved
* @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
*
**/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

JPluginHelper::importPlugin( 'feedgator' );
jimport('joomla.application.component.model');
JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_feedgator/tables');

class FeedgatorModelTools extends JModelLegacy
{
	var $_id = null;
	var $_data = null;
	var $_imports = null;
	var $_plugin = null;

	public function __construct()
	{
		parent::__construct();

		$this->pluginModel =FGFactory::getPluginModel();
		$this->feedModel =FGFactory::getFeedModel();
	}

	public function findDuplicates()
	{
		$plugins_data = $this->pluginModel->loadInstalledPlugins();
		foreach($plugins_data as $plugin_data) {
			if($plugin_data->published) {
				$this->pluginModel->setExt($plugin_data->extension);
				$plugin = $this->pluginModel->getPlugin();
				$query = $plugin->findDuplicates('internal',null);
				$this->_db->setQuery($query,0,1);
				if($this->_db->loadResult()) {
					return true;
				}
			}
		}
		return false;
	}

	public function getDuplicates()
	{
		$plugins_data = $this->pluginModel->loadInstalledPlugins();
		foreach($plugins_data as $plugin_data) {
			if($plugin_data->published) {
				$this->pluginModel->setExt($plugin_data->extension);
				$plugin = $this->pluginModel->getPlugin();
				$query[] = $plugin->findDuplicates('internal',null);
			}
		}
		$query = implode(' UNION ',array_filter($query));
		$query .= ' ORDER BY content_type';
		$this->_db->setQuery($query);
		if($dups = $this->_db->loadObjectList()) {
			return $dups;
		}
		return false;
	}

	public function ignoreDuplicate()
	{
		$rel = JRequest::getCmd('rel','','get');
		$id = substr($rel,strpos($rel,'_')+1);
		$content_type = JRequest::getCmd('type','','get');
		if($rel and $content_type) {
			$this->pluginModel->setExt($content_type);
			$this->pluginModel->getParams(-1);
			$params = $this->pluginModel->_data->paramsArray[-1];
			isset($params['ignore']) ? $params['ignore'] .= ','.$id : $params['ignore'] = $id;
			unset($params['--TXT--']);
			if($this->pluginModel->store(-1,$params)) {
				return true;
			}
		}
		return false;
	}

	public function checkLatestVersion(&$fgParams,$version=null,$stable='3190',$dev='5405')
	{
		$version = ( $version ? $version : FeedgatorHelper::getFGVersion() );
		$short_v = substr($version,0,5);
		$dev_level = substr($version,5);

		//need to implement some caching here
		$frs = 'http://joomlacode.org/gf/project/feedgator/frs/?action=FrsReleaseBrowse&frs_package_id=';
		$url = 	$frs.$stable;
		if($dev) $url2 = $frs.$dev;

		$stable = array();
		$dev = array();

		if($page = FeedgatorUtility::getUrl($url,$fgParams->getValue('scrape_type'))) {
			$stable = $this->loadJoomlaCode($page);
			$stable['upgrade'] = 0;
		}
		if((!$page OR $fgParams->getValue('notify_dev')) AND $url2) {
			if($page = FeedgatorUtility::getUrl($url2,$fgParams->getValue('scrape_type'))) {
				$dev = $this->loadJoomlaCode($page);
				$dev['upgrade'] = 0;
			}
		}
		if(empty($stable) AND empty($dev)) {
			$stable['v'] = 'unknown';
			$stable['upgrade'] = 0;
		}

		if($short_v < $stable['v'] OR ($short_v == $stable['v'] AND $dev_level)) {
			$stable['upgrade'] = 1;
		}
		if(isset($dev['upgrade'])) {
			if($version < $dev['v']) {
				$dev['upgrade'] = 1;
			}
		}
		//send to cache
		return array('stable'=>$stable,'dev'=>$dev);
	}

	public function checkJPlugins()
	{
		$query = 	'SELECT extension_id FROM #__extensions WHERE element = \'feedgator_system\'';

		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}

	public function checkPlugins()
	{
		/*
		$query = 	'SELECT REPLACE(p.element,"plg_fg","com") AS plugin FROM #__extensions AS p'.
					' WHERE p.type = \'plugin\' AND p.folder = \'feedgator\'';

		$this->_db->setQuery($query);
		$plugins = $this->_db->loadAssocList();
		*/

		$query = 	'SELECT COUNT(fg.id) AS fgids, COUNT(fgg.id) AS fggids' .
					' FROM #__feedgator AS fg' .
					' INNER JOIN #__feedgator_plugins AS fp ON fg.content_type = fp.extension' .
					' RIGHT JOIN #__feedgator AS fgg ON fg.id = fgg.id';
		$this->_db->setQuery($query);
		$counts = $this->_db->loadRow();

		if($counts[0] == $counts[1]) {
			$rows = $this->pluginModel->loadInstalledPlugins();
			foreach($rows as &$row) {
				if($row->pub_count) {
					return true;
				}
			}
		}

		return false;
	}

	public function checkImports()
	{
		$count = 0;

		$query = 'SELECT * FROM #__feedgator_imports WHERE (content_id != -1 AND content_id != -2) AND plugin != '.$this->_db->Quote('enclosure');
		$this->_db->setQuery($query);
		$rows = $this->_db->loadAssocList();
		if(!empty($rows)) {
			foreach($rows as &$row) {
				if($plugin = $this->pluginModel->getPlugin($row['plugin'])) {
					if(!isset($ids[$row['plugin']]['query'])) $ids[$row['plugin']]['query'] = $plugin->countContentQuery();
					$ids[$row['plugin']]['ids'][] = $row['content_id'];
				}
			}
			if(isset($ids)) {
				foreach($ids as $content_type => &$data) {
					$query = sprintf($data['query'],implode(',',$data['ids']));
					$this->_db->setQuery($query);
					$count += $this->_db->loadResult();
				}
			}
			$total = count($rows);
		} else {
			$total = 0;
		}

		return ($total == $count);
	}

	public function syncImports()
	{
		$app = JFactory::getApplication();
		$msg = '<h4>'.JText::_('Import Synchronisation').'</h4>';

		$query = 'SELECT * FROM #__feedgator_imports WHERE (content_id != -1 AND content_id != -2) AND plugin != '.$this->_db->Quote('enclosure');
		$this->_db->setQuery($query);
		$rows = $this->_db->loadAssocList();
		foreach($rows as &$row) {
			if($plugin =$this->pluginModel->getPlugin($row['plugin'])) {
				if(!$plugin->getContentItem($row['content_id'])) {
					$ids[] = $row['id'];
				}
			} else {
				$msg .= 'Unable to sync imports using '.$row['plugin'];
			}
		}
		if(isset($ids)) {
			$total = count($ids);
			$ids = implode(',',$ids);
			$query = 'DELETE FROM #__feedgator_imports WHERE id '. (($total > 1) ? 'IN ('.$ids.')' : '= '.$ids);
			$this->_db->setQuery($query);
			$this->_db->loadResult();
		}
		isset($total) ? $msg .= $total.JText::_(' log entries deleted!') : $msg .= JText::_('No log entries deleted!');
		$app->redirect('index.php?option=com_feedgator&task=tools',$msg);
	}

	public function loadJoomlaCode($page)
	{
		$data = array();
		$dom = new DOMDocument();

		libxml_use_internal_errors(TRUE);
		$dom->loadHTML($page);
		libxml_clear_errors();
		$tables = $dom->getElementsByTagName('table');
		foreach($tables as $table) {
			if(trim($table->getAttribute('class')) == "tabular") {
				break;
			}
		}
		$trs = $table->getElementsByTagName('tr');
		foreach($trs as $tr) {
			if(trim($tr->getAttribute('class')) == "l") {
				$tds = $tr->getElementsByTagName('td');
				break;
			}
		}

		$a = $tds->item(0)->getElementsByTagName('a')->item(0);
		$data['v'] = str_replace('FeedGator','',$a->nodeValue);

		$data['date'] = $tds->item(1)->nodeValue;
		$data['name'] = $tds->item(2)->nodeValue;
		$data['link'] = 'http://www.joomlacode.org'.$tds->item(2)->getElementsByTagName('a')->item(0)->getAttribute('href');
		$data['size'] = $tds->item(3)->nodeValue;

		return $data;
	}
}