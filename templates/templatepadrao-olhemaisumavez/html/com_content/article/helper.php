<?php

class modLcaHelper {
	
		var $params;
	
		function __construct(&$catid) {
			$this->j15 = version_compare(JVERSION, "1.6.0", "<=");
			$this->catid = $catid;
			//$this->params = $params;
			//$this->itemid = $this->getItemid();
			$document = JFactory::getDocument();
			$document->addStyleSheet(JURI::base().'modules/mod_lca/assets/css/style.css');
			$document->addScript(JURI::base().'modules/mod_lca/assets/js/lca.js');
		}

		function &getList() {
			$db = JFactory::getDBO();
			$date = JFactory::getDate();
			$user = JFactory::getUser();
			$now = method_exists($date, 'toMySQL') ? $date->toMySQL() : $date->toSql();
			$nullDate	= $db->getNullDate();
			$out = (object)array();
			$out->articulos = array();
			$out->years = array();
			$out->meses = array();
			
			$query = 'SELECT '.
			' a.publish_up AS co, '.
			'a.id, a.title, a.alias, a.catid, c.alias as calias'.
			' FROM #__content AS a'.
			' LEFT JOIN #__categories AS c ON c.id=a.catid'.
			//' LEFT JOIN #__sections AS s ON s.id=a.sectionid'.
			' WHERE (a.state = 1) '.
			' AND (a.catid = '.$this->catid. ')'.
			' AND ( a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' )'.
			' AND ( a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )';
			//' AND s.published = 1';
			//' ORDER BY co '.$this->params->get("o_article", "desc");
			$db->setQuery($query);
			$rows = $db->loadObjectList();
			//print_r($rows);
			
			//$monthsArray = $this->getMonths($this->params->get("o_month") == "desc");
			$monthsArray = array();
			//$this->params = $params;
			$desc = "desc";
			if ($desc) {
				for ($i=12;$i>0;$i--)
					$monthsArray[$this->monthToString($i)] = array();
			}
			else {
				for ($i=1;$i<=12;$i++)
					$monthsArray[$this->monthToString($i)] = array();
			}
			
			foreach ($rows as $row) {
				$d = $this->getYear($row->co);
				if (!isset($out->articulos[$d])) 
					$out->articulos[$d] = $monthsArray;
			}
			
			krsort($out->articulos);
			$cut_title = '0';
			foreach ($rows as $row) {
				$d = $this->getYear($row->co);
				$m = $this->getMonth($row->co);
				
				if (isset($out->articulos[$d])) {
					$out->lastyear = $d;
					if ($this->j15)
						$url = ContentHelperRoute::getArticleRoute($row->id.":".$row->alias, $row->catid.":".$row->calias, $row->sectionid);
					else
						$url = ContentHelperRoute::getArticleRoute($row->id.":".$row->alias, $row->catid.":".$row->calias);
					//if ($this->itemid && strpos($url, "&Itemid=") === false) $url .= '&Itemid='.$this->itemid;
					$link = JRoute::_($url);
					$month = $this->monthToString($m);
					if ($cut_title && strlen($row->title) > $cut_title)
						$row->title = substr($row->title, 0, $cut_title).'...';
					if (0)
						$out->articulos[$d][$month][] = '<span style="cursor:pointer" title="'.$this->getDate($row->co).'">'.$this->getDay($row->co).'</span> - <a href="'.$link.'">'.$row->title.'</a>';
					else
						$out->articulos[$d][$month][] = '<a href="'.$link.'">'.$row->title.'</a>';
					$out->years[$d] = isset($out->years[$d]) ? $out->years[$d]+1 : 1;
					if (!isset($out->meses[$d])) $out->meses[$d] = array();
					$out->meses[$d][$month] = isset($out->meses[$d][$month]) ? $out->meses[$d][$month]+1 : 1;
				}
			}
			
			return $out;
		}
		
		function getistArtigos() {
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			
			$date = JFactory::getDate();
			
			$nowDate = $db->quote($date->toSql());
			
			$query->select('year(publish_up) as \'ano\' ,month(publish_up) \'mes\' ,count(*) \'quantidade\'')
			->from('#__content as materia')
			->order('month(publish_up) asc, year(publish_up) desc')
			->group('month(publish_up), year(publish_up)');	
			
			$db->setQuery($query);
			$rows = $db->loadObjectList();
			print_r($rows);
		}
	
		function monthToString($month) {
			$data = array('', JText::_('JANUARY'), JText::_('FEBRUARY'), JText::_('MARCH'), JText::_('APRIL'), JText::_('MAY'), JText::_('JUNE'),
						JText::_('JULY'), JText::_('AUGUST'), JText::_('SEPTEMBER'), JText::_('OCTOBER'), JText::_('NOVEMBER'), JText::_('DECEMBER'));
			return $data[(int)$month];
		}	
		
		private function getDate($date) {
			$date = explode(" ", $date);
			return $date[0];
		}
		private function getYear($date) {
			$date = explode("-", $date);
			return $date[0];
		}
		private function getMonth($date) {
			$date = explode("-", $date);
			return $date[1];
		}
		private function getDay($date) {
			$date = $this->getDate($date);
			$date = explode("-", $date);
			return $date[2];
		}
		
		function getImg($img) {
			$data = new stdClass;
			$data->expand = "▼";
			$data->collapse = "►";

			return $data;
		}

}