<?php

/**
* FeedGator Joomla Native Content Importing Plugin
* @version 3.0
* @package FeedGator
* @author Matt Faulds
* @email mattfaulds@gmail.com
* @copyright (C) 2010 Matthew Faulds - All rights reserved
* @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
*
**/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_feedgator/tables');
jimport( 'joomla.plugin.plugin' );

class plgFeedgatorContent extends JPlugin
{
	// Title for use in menus etc
	var $title = 'Joomla Content';
	// Name of extension that plugin enable FeedGator to save to
	var $extension = 'com_content';
	// DB table to above extension
	var	$table = '#__content';
	// Name for "published' column in content items - com_content uses 'state'
	var $state = 'state';
	// Name for section column in content items or alias for section
	var $section = '<i>Unused</i>';
	// Section ID over-ride for content components without sections
	var $sectionid = null;
	// Object containing plugin data - id,extension,published,params(INI)
	var $data = null;
	// JParameter object with plugin parameters
	var $params = null;

	function __construct()
	{
		$jlang = JFactory::getLanguage();
		$jlang->load('plg_fg_content', JPATH_ADMINISTRATOR, 'en-GB', true);

		$this->model = FGFactory::getPluginModel();
		$this->model->setExt($this->extension);
		$this->_db = JFactory::getDbo();
	}

	function setData($data)
	{
		$this->data = $data;
		$this->sectionid = -1*$this->data->id; //legacy
	}

	function &getData()
	{
		if(!$this->data) {
			$this->model->setExt($this->extension);
			$this->setData($this->model->getPluginData());
		}
		return $this->data;
	}

	function &getParams($feedId = -1,$force = false)
	{
		if(!$this->params) {
			$options = array('control'=>'params');
			$this->params = JForm::getInstance( 'FG_'.$this->extension, $this->model->getFilePath(), $options);
			$this->params->bind($this->model->getParams($feedId));
		}
		return $this->params;
	}

	function componentCheck()
    {
		return true;
	}

	/**
	* This function is necessary to prevent strict errors with Joomla JTable::isCheckedOut being called statically by JHtml::('grid.checkedout',$row,$i)
	*/
	function bindContent(&$row)
	{
		$nrow = JTable::getInstance('content');
		$nrow->bind((array)$row);

		return $nrow;
	}

	function countContentItems($where)
	{
		$this->_buildWhere($where);
		// Get the total number of records specified by where clause
		$query = '(SELECT COUNT(*)' .
				' FROM ' . $this->table .' AS c' .
				' LEFT JOIN #__categories AS cc ON cc.id = c.catid' .
				' LEFT JOIN #__feedgator_imports AS fi ON fi.content_id = c.id AND fi.plugin = '. $this->_db->Quote($this->extension) .
				' LEFT JOIN #__feedgator AS fg ON fg.id = fi.feed_id' .
				$where.')';
		return $query;
		//$this->_db->setQuery($query);
		//return $this->_db->loadResult();
	}

	function countContentQuery()
	{
		// Get the total number of records in range (added later)
		$query = 'SELECT COUNT(*)' .
				' FROM ' . $this->table .
				' WHERE id IN (%s)' .
				' AND ('.$this->state.' = 1 OR '.$this->state.' = 0)';
		return $query;
	}

	function getContentItem($id)
	{
		$query = 	'SELECT *' .
					' FROM ' . $this->table .
					' WHERE id = '. $this->_db->Quote($id) .
					' AND ('.$this->state.' = 1 OR '.$this->state.' = 0)';
		$this->_db->setQuery( $query );
		if(!$content = $this->_db->loadAssoc()) {
			return false;
		}

		return $content;
	}

	function getContentLink($id)
	{
	    return JRoute::_( 'index.php?option='.$this->extension.'&task=edit&cid[]='. $id );
	}

	function getContentItemsQuery($where)
	{
		require_once(JPATH_ADMINISTRATOR.'/components/com_content/models/articles.php');
		require_once(dirname(__FILE__).'/contentmodel.php');
		$articlesModel = new ContentModelArticlesFG;
		$query = $articlesModel->getContentItems($where);
		return $query;
	}

	function getFeedItems($where)
	{
		$this->_buildWhere($where);
		$query =	'SELECT fg.*, cc.title AS cat_name, \''.$this->section.'\' AS section_name, u.name AS editor FROM #__feedgator fg'.
					' LEFT JOIN #__categories AS cc ON cc.id = fg.catid' .
					' LEFT JOIN #__users AS u ON u.id = fg.checked_out '.
					$where;
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}

	function findDuplicates($type,$string,$catid = null)
	{
		// type can be id, alias, title or internal
		if($type == 'internal') { // for com_content we use the alias to find internal duplicates
			$this->getParams();
			$query =	'SELECT '. $this->_db->Quote($this->extension) .' AS content_type, fg.title AS feed_title,c.title,c.alias,COUNT(*) AS num,' .
						' GROUP_CONCAT(CONCAT_WS(\'|\',CONVERT(c.id,CHAR(11)),CONVERT(null,CHAR(11)),CONVERT(c.catid,CHAR(11)),c.title) ORDER BY c.id ASC SEPARATOR \'||\') AS results' .
						' FROM ' . $this->table . ' AS c' .
						' INNER JOIN #__feedgator_imports AS fi ON fi.content_id = c.id AND fi.plugin = '. $this->_db->Quote($this->extension) .
						' INNER JOIN #__feedgator AS fg ON fg.id = fi.feed_id' .
						' WHERE (c.'.$this->state.' = 1 OR c.'.$this->state.' = 0)' .
						($this->params->getValue('ignore') ? ' AND c.id NOT IN ('.$this->params->getValue('ignore').')' : '' ).
						' GROUP BY alias' .
						' HAVING ( COUNT(*) > 1 )';
			return '('.$query.')';
		} else {
			$query = 	'SELECT c.id' .
						' FROM ' . $this->table . ' AS c' .
					//	' INNER JOIN #__categories AS cc ON cc.id = '.(int)$catid .
						' WHERE c.'. $type .' = '. (($type == 'id') ? (int)$string : $this->_db->Quote($string)) .
						' AND c.catid = '. (int)$catid .
						' AND (c.'.$this->state.' = 1 OR c.'.$this->state.' = 0)';
			$this->_db->setQuery( $query );
			return $this->_db->loadResult();
		}
	}

	// legacy function
	function getSectionList(&$fgParams,$default=false)
	{
		$options[] = JHTML::_('select.option', -1, '- '.JText::_('J!1.6+ Doesn\'t Have Sections!').' -', 'id', 'title');

		return $options;
	}

	function getCategoryList(&$fgParams,$default=false)
	{
		$where = ' WHERE extension = \'com_content\' ';
		$order = '';
		$sectionid = 0;

		//Joomla categories
		$query = 	'SELECT id, title' .
					' FROM #__categories' .
					$where .
					$order;
		$this->_db->setQuery( $query );
		$categories = $this->_db->loadObjectList();
	//	print_r($categories);

		if($default) {
			$options[] = JHTML::_('select.option', '', JText::_( 'Use Default' ), 'id', 'title');
		}

		if($sectionid == 0) {
			$options[] = JHTML::_('select.option', -1, JText::_( 'Select Joomla Category' ), 'id', 'title');
		} else {
			$options = array();
		}

		$options = array_merge( $options, $categories );

		return $options;
	}

	function getCatSelectLists($filter,&$fgParams)
	{
		$this->getData(); // ensure plugin data loaded
		$prefix = $this->data->id.'_';

		$categories[] = JHTML::_('select.option', $prefix, JText::_('Use Default'));
		$categories[] = JHTML::_('select.option', $prefix.'0', '- '.JText::_('Select Joomla Category').' -');

		// get list of categories for dropdown filter
		$query = 'SELECT CONCAT(\''.$prefix.'\', cc.id) AS value, cc.title AS text' .
				' FROM #__categories AS cc' .
				$filter . // this is null except for Joomla sections
		$this->_db->setQuery($query);
		$categories = array_merge($categories,$this->_db->loadObjectList());

		return $categories;
	}

	function getSecSelectLists(&$fgParams)
	{
		$this->getData(); // ensure plugin data loaded

		$sections[] = JHTML::_('select.option',  '', JText::_( 'Use Default' ) );
		$sections[] = JHTML::_('select.option',  '-1', '- '. JText::_( 'Select Joomla Section' ) .' -' );

		return $sections;
	}

	function getFieldNames(&$content)
	{
		$query = 	'SELECT CONCAT_WS(\', \', s.title, c.title)' .
					' FROM #__categories AS c' .
					' LEFT JOIN #__sections AS s ON s.id = c.section' .
					' WHERE c.id = '. $this->_db->Quote($content['catid']);
		$this->_db->setQuery( $query );

		return $this->_db->loadResult();
	}

	function getSectionCategories(&$fgParams)
	{
		$sectioncategories = array($this->sectionid => $this->getCategoryList($fgParams));

		return $sectioncategories;
	}

	function addDefaultImage($position,&$content,&$fgParams,$img = null)
	{
		$this->getParams();

		if($img) {
		//to allow an over-ride
			//$img = '<img src="'.JURI::root().'/media/feedgator/images/feeds/'.$fgParams->get('feed_img').'" class="fgFeedImg" alt="'.$fgParams->get('title').'" />';
			//$content['default_img'] = $img;
			//need to add image to top of stack
			//array_unshift($content['images']['stack'],array('src'=>$src));
		} else {
			if($fgParams->getValue('feed_img')) {
				FeedgatorUtility::profiling('Adding Default Image');

				$base = $fgParams->getValue('rel_src',0) ? '/' : str_replace('administrator/','',JURI::base());
				$src = 'media/feedgator/images/feeds/'.$fgParams->getValue('feed_img');
				$savepath = JPATH_ROOT.'/'.$src;
				$img = '<img src="'.$base.$src.'" class="fgFeedImg" alt="'.$fgParams->getValue('title').'" />';
				$content['default_img'] = $src;
				$content['default_img_html'] = $img;

				//need to add image to top of stack
				array_unshift($content['images']['stack'],array('src'=>$src,'savepath'=>$savepath));

				switch($position)
				{
					case 'introtext':
						$content['introtext'] = $content['default_img_html'].$content['introtext'];
					break;

					case 'both':
						$content['introtext'] = $content['default_img_html'].$content['introtext'];
						$content['fulltext'] = $content['default_img_html'].$content['fulltext'];
					break;

					default:
					break;
				}
			}
		}
	}

	/*
	* $image is an array with filename, src, and savepath to image file on local server
	*/
	function saveImages($image,$id,&$content,&$fgParams)
	{
		return false;
	}

	function save(&$content,&$fgParams)
	{
		if(!isset($this->row)) {
			$row = JTable::getInstance('content');
			$this->row = $row;
		} else {
			$row = $this->row;
		}
		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('system');
		JPluginHelper::importPlugin('content');
		if (!$row->bind( $content )) {
			$content['mosMsg'] = $this->title . '***ERROR: bind' . $this->_db->getErrorMsg();
			return false;
		}
		$row->id = (int) $row->id;
		$isNew = ($row->id < 1);

		$row->featured = $fgParams->getValue('front_page');
		$row->language = $fgParams->getValue('feed_language') ? $fgParams->getValue('feed_language') : '*';

		$row->access = $fgParams->getValue('access'); //J1.6 uses 1 for public…

		// Make sure the data is valid
		/*if (!$row->check()) {
			$e = '';
			foreach ($row->getErrors() as $error) {
				$e .= $error.'<br/>';
			}
			$content['mosMsg'] = '***ERROR*(check)*  Feed - '.$content['title'].':' . $this->_db->getErrorMsg().'<br/>'.$e;
			return false;
			//continue;
		}*/

		//set to hide introtext when viewing full article unless only making introtext
		$row->attribs = 'show_intro=0';
		if($fgParams->getValue('onlyintro')) {
			$row->attribs = '';
		}

		//Trigger OnContentBeforeSave
		$result = $dispatcher->trigger( 'onContentBeforeSave', array('com_content',&$row, $isNew));

		// Store the content to the database

		// Make sure the article does not already exist
		$exists = $this->findDuplicates('alias',$row->alias,$row->catid);
		$stored = false;
		if(!$exists) {
			$stored = $row->store();
		} elseif($fgParams->getValue('force_new') AND $row->load(array('alias'=>$content['alias'],'catid'=>$content['catid'])) AND ($row->id != $content['id'] OR $content['id']==0)) { // 1.6 won't let articles with same alias import!
			$datenow = JFactory::getDate();
			$row->alias .= '_'.$datenow->toFormat("%Y-%m-%d-%H-%M-%S");
			$row->id = $content['id'];
			$row->state = (int)$fgParams->getValue('auto_publish');
			$stored = $row->store();
		}

		if(!$stored) {
			$content['mosMsg'] = $this->title .' error saving '. $row->title . ' - article may already exist or be trashed. ' . $this->_db->stderr();
			return false;
		}
		$content['id'] = $row->id;

		// Check the article and update item order
		//$row->checkin();
		//$row->reorder('catid = '.(int) $row->catid.' AND state >= 0');

		require_once (JPATH_ADMINISTRATOR.'/components/com_content/tables/featured.php');
		$fp = new ContentTableFeatured($this->_db);

		if ($fgParams->getValue('front_page')) {
			// Is the item already viewable on the frontpage?
			if (!$fp->load($row->id))
			{
				// Insert the new entry
				$query = 'INSERT INTO #__content_frontpage' .
						' VALUES ( '. (int) $row->id .', 1 )';
				$this->_db->setQuery($query);
				if (!$this->_db->query())
				{
					JError::raiseError( 500, $this->_db->stderr() );
					return false;
				}
				$fp->ordering = 1;
			}
			$fp->reorder();
		}
		//}

		//Trigger OnContentAfterSave
		$dispatcher->trigger( 'onContentAfterSave', array($this->extension,&$row, $isNew));

		$cache = JFactory::getCache($this->extension);
		$cache->clean();

		FeedgatorHelper::saveImport($fgParams->getValue('hash'),$fgParams->getValue('id'),$content['id'],$this->extension,$fgParams);

		return true;
	}

	function reorder($catid,&$fgParams)
	{
		if(!$this->row) {
			$row = JTable::getInstance('content');
			$this->row = $row;
		} else {
			$row = $this->row;
		}
		if($row->reorder('catid = '.(int) $catid.' AND state >= 0')) {
			return true;
		}
		return false;
	}

	function _buildWhere(&$where,$w=true)
	{
		if($this->state != 'state' AND strpos($where,'state') !== false) {
			$where = str_replace('state',$this->state,$where);
		}
		$a = ' AND ';
		$w = $w ? 'WHERE ' : '';
		$where ? $where .= $a.'fg.content_type = '.$this->_db->Quote($this->extension) : $where = $w.'fg.content_type = '.$this->_db->Quote($this->extension);
	}

	//Not yet in use
	function saveTags($cid,$metakey)
	{
		return false;
	}
}