<?php

/**
* @Copyright Copyright (C) 2013 - JoniJnm.es
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');

class modSlideromuz {
	
	public static function getArtigos(&$params) {
		
		$categoria = $params->get('catid');
		
		$db = JFactory::getDBO();
		$date = JFactory::getDate();
		$user = JFactory::getUser();
		$now = method_exists($date, 'toMySQL') ? $date->toMySQL() : $date->toSql();
		$nullDate	= $db->getNullDate();
		
		$query = 'SELECT '.
		' a.publish_up AS co, '.
		'a.id, a.title, a.alias, a.catid, c.alias as calias, a.images, a.introtext'.
		' FROM #__content AS a'.
		' LEFT JOIN #__categories AS c ON c.id=a.catid'.
		' WHERE (a.state = 1) '.
		' AND (a.catid = '.$categoria. ')'.
		' AND ( a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' )'.
		' AND ( a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )';
		$db->setQuery($query,0,3);
		$rows = $db->loadObjectList();
			
		return $rows;
	}
	
	
}