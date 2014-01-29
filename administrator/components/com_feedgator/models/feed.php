<?php

/**
* FeedGator - Aggregate RSS newsfeed content into a Joomla! database
* @version 3.0a3
* @package FeedGator
* @author Original author Stephen Simmons
* @now continued and modified by Matt Faulds, Remco Boom & Stephane Koenig and others
* @email mattfaulds@gmail.com
* @Joomla 1.5 Version by J. Kapusciarz (mrjozo)
* @copyright (C) 2005 by Stephen Simmons - All rights reserved
* @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
*
**/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

JPluginHelper::importPlugin( 'feedgator' );
jimport('joomla.application.component.model');
jimport('joomla.filesystem.file');
JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_feedgator/tables');

class FeedgatorModelFeed extends JModelLegacy
{
	var $_id = null; // feed id
	var $_data = null; // per feed data
	var $_imports = null; // per feed imports
	var $_plugin = null; // per feed plugin
	var $_params = null; // per feed params
	var $_defaultParams = null; // global feed params
	var $_defaultParamsData = null; // global feed default data

	function __construct()
	{
		parent::__construct();

		if(in_array(JFactory::getApplication()->input->get( 'task','','WORD' ),array('new','add'))) {
			$this->setId(0);
		} else {
			$array	= JFactory::getApplication()->input->post->get( 'cid', array(0), 'ARRAY' );
			if($array[0]) {
				$this->setId((int)$array[0]);
			}
		}
	}

	function setId($id,$force=false)
	{
		if($id != $this->_id OR $force == true) {
			$this->_id				= $id;
			$this->_data			= null;
			$this->_imports			= null;
			$this->_plugin			= null;
			$this->_params			= null;
			$this->_defaultParams	= null;
		}
	}

	function &getData()
	{
		$this->_loadData();

		return $this->_data;
	}

	function &getImports()
	{
		$this->_loadImports();
		return $this->_imports;
	}

	function &getParams($defaults = false,$tpl = 'feed')
	{
		if(!$this->_params) {
			$fgParams = $this->getConfig('fgParams',false);
			$xmlFile = JPATH_COMPONENT.'/models/forms/default_'.$tpl.'.xml';
			$fgParams->loadFile($xmlFile);
			if($this->getData()) {
				$tmpParams = json_decode($this->_data->params,true);
				$tmpParams = array_merge($tmpParams,(array)$this->_data);
				if($defaults AND $this->getDefaultParams()) {
					// adds in default data for feed processing
					$tmpParams = FeedgatorUtility::array_overlay($tmpParams,$this->_defaultParamsData);
				}
				unset($tmpParams['params']);

				$fgParams->bind($tmpParams);
			}

			$this->_params = $fgParams;
		}
		return $this->_params;
	}

	function &getDefaultParams($force = false)
	{
		if(!$this->_defaultParams) {
			if($this->_loadData(-2)) {
				$this->_defaultParamsData = json_decode($this->_defaultData->params,true);
				$this->_defaultParamsData = array_merge($this->_defaultParamsData,(array)$this->_defaultData);
				unset($this->_defaultParamsData['params']);
			}

			if($this->_defaultParamsData OR (!$this->_defaultParamsData AND $force)) {
				$xmlFile = JPATH_COMPONENT.'/models/forms/default_feed_default.xml';
				$options = array('control'=>'params');
				$this->_defaultParams = JForm::getInstance('deffgParams', $xmlFile, $options);
				$this->_defaultParams->bind($this->_defaultParamsData);
			}
		}
		return $this->_defaultParams;
	}

	function &getConfig($name = 'config',$setId = true)
	{
		$fg = JComponentHelper::getComponent('com_feedgator');
		$options = array('control'=>'params');
		$xmlFile = JPATH_COMPONENT.'/models/forms/default_settings.xml';
		$fgParams = JForm::getInstance($name, $xmlFile, $options);
		$fgParams->bind(json_decode($fg->params));
		if($setId) $fgParams->setValue('id',null,$fg->id);
		unset($fg);

		return $fgParams;
	}

	function _setFolderParams($preview,&$fgParams)
	{
		jimport( 'joomla.filesystem.folder' );
		if(in_array(JFactory::getApplication()->input->get( 'task','','WORD' ),array('import','importall','cron','pseudocron'))) {
			switch($fgParams->getValue('sub_folder',0)) {
				case 0: $sub = ''; break;
				case 1: $sub = 'daily/'.gmdate('Y/m/d/'); break; //day
				case 2: $sub = 'weekly/'.gmdate('Y/W/'); break; //week
				case 3: $sub = 'monthly/'.gmdate('Y/m/'); break; //month
			}
			$fgParams->setValue('img_folder',null,$fgParams->getValue('img_folder',null,'media/feedgator/images/').$sub);
			$fgParams->setValue('img_srcpath',null,($fgParams->getValue('rel_src',null,0) ? ($preview ? '../' : '') : $fgParams->getValue('base')).$fgParams->getValue('img_folder'));
			$fgParams->setValue('img_savepath',null,JPATH_ROOT.'/'.JFolder::makeSafe( str_replace('/','\\',$fgParams->getValue('img_folder'))) );
			$fgParams->setValue('srcpath',null,($fgParams->getValue('rel_src',null,0) ? ($preview ? '../' : '') : $fgParams->getValue('base')).$fgParams->getValue('media_folder',null,'media/feedgator/'));
			$fgParams->setValue('savepath',null,JPATH_ROOT.'/'.JFolder::makeSafe( str_replace('/','\\',$fgParams->getValue('media_folder',null,'media/feedgator/'))) );
		}
	}

	function &getPlugin($ext = null, $preview = false)
	{
	//this must be an error
		$fgParams = $this->getParams();
		if(!$ext) {
			$ext = $fgParams->getValue('content_type') ? $fgParams->getValue('content_type') : '- '.JText::_('Content Type Not Set');
		}

		$pluginModel = FGFactory::getPluginModel();
		$this->_plugin = $pluginModel->getPlugin($ext);
		if(!isset($this->_plugin->title)) {
			$this->_plugin = new stdClass();
			$this->_plugin->errorMsg = JText::_('Unable to load plugin') ." $ext.";
		} elseif(!@$this->_plugin->data->published) {
			$this->_plugin->errorMsg = JText::_('Plugin not published for') ." $ext.";
		}
		return $this->_plugin;
	}

	function isCheckedOut( $uid=0 )
	{
		if ($this->_loadData()) {
			if ($uid) {
				return ($this->_data->checked_out && $this->_data->checked_out != $uid);
			} else {
				return $this->_data->checked_out;
			}
		}
	}

	function checkin()
	{
		if ($this->_id) {
			$feed = JTable::getInstance('Feed','Table');

			if(! $feed->checkin($this->_id)) {
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}
		return false;
	}

	function checkout($uid = null)
	{
		if ($this->_id) {
			if (is_null($uid)) {
				$user	= JFactory::getUser();
				$uid	= $user->get('id');
			}

			$feed = JTable::getInstance('Feed','Table');
			if(!$feed->checkout($uid, $this->_id)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
		}
		return false;
	}

	function store($post)
	{
		$row = JTable::getInstance('Feed','Table');
		if($post['content_type'] == '-1') $post['content_type'] = 'com_content'; 		// force content_type if old style and not set

		$pdata = array();
		$pdata['id'] = $this->_id;
		$pdata['created'] = gmdate('Y-m-d H:i:s');
		$pdata['created_by'] = $post['created_by'];
		$pdata['title'] = $post['title'];
		$pdata['content_type'] = $post['content_type'];
		$pdata['sectionid'] = $post['sectionid'];
		$pdata['feed'] = $post['feed'];
		$pdata['catid'] = $post['catid'];
		$pdata['published'] = $post['published'];
		$pdata['front_page'] = $post['front_page'];
		$pdata['default_author'] = $post['default_author'];
		$pdata['default_introtext'] = $post['default_introtext'];
		$pdata['filtering'] = $post['filtering'];
		$pdata['filter_whitelist'] = $post['filter_whitelist'];
		$pdata['filter_blacklist'] = $post['filter_blacklist'];

		unset($post['title'],$post['content_type'],$post['sectionid'],$post['feed'],$post['catid'],$post['published'],$post['front_page'],$post['default_author'],$post['default_introtext'],$post['created'],$post['created_by'],$post['filtering'],$post['filter_whitelist'],$post['filter_blacklist']);

		$pdata['params'] = json_encode($post);

		foreach($pdata as $k => $v) {
			if($v == '') {
				$pdata[$k] = null;
			}
		}

		if($this->_id == -2 AND !$this->_loadData(-2)) {
			$this->_db->setQuery( 'INSERT INTO #__feedgator (id) VALUES (-2)' );
			$this->_db->query();
		}

		if (!$row->save($pdata)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}

	function copy($cid = array())
	{
		$user 	= JFactory::getUser();

		$row = JTable::getInstance('Feed','Table');
		$now = gmdate('Y-m-d H:i:s');

		if (count( $cid )) {
			foreach($cid as $id) {
				$row->load($id);
				$row->id 		= 0;
				$row->title		= 'Copy of '.$row->title;
				$row->created 	= $now;
				$row->imports	= '';
				if(!$row->save($row)) {
					return false;
				}
			}
		}

		return true;
	}

	function delete($cid = array())
	{
		$result = false;

		if (count( $cid )) {
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			$query = 'DELETE FROM #__feedgator'
			. ' WHERE id IN ( '.$cids.' )';

			$this->_db->setQuery( $query );

			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	function publish($cid = array(), $publish = 1)
	{
		$user 	= JFactory::getUser();

		if (count( $cid )) {
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			$query = 'UPDATE #__feedgator'
			. ' SET published = '.(int) $publish
			. ' WHERE id IN ( '.$cids.' )'
			. ' AND ( checked_out = 0 OR ( checked_out = '.(int) $user->get('id').' ) )'
			;

			$this->_db->setQuery( $query );

			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}
		return true;
	}

	function frontpage($cid = array(), $frontpage = 1)
	{
		$user 	= JFactory::getUser();

		if (count( $cid )) {
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			$query = 'UPDATE #__feedgator'
			. ' SET front_page = '.(int) $frontpage
			. ' WHERE id IN ( '.$cids.' )'
			. ' AND ( checked_out = 0 OR ( checked_out = '.(int) $user->get('id').' ) )'
			;

			$this->_db->setQuery( $query );

			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}
		return true;
	}

	function import($formData,$preview,$update)
	{
		global $fgParams, $p; // temporary fix for cron issue

		$dispatcher = JDispatcher::getInstance();
		$fgConfig = JComponentHelper::getParams ('com_feedgator');
		$tzOffset = JFactory::getConfig()->get('config.offset');
		$task = JFactory::getApplication()->input->get( 'task','','WORD' );

		$mosMsg = '';
		$initTime = JFactory::getDate('now', $tzOffset)->format('D F j, Y, H:i:s T',false,false);

		$adminMsg = '';
		$feedMsg = '';
		$innerMsg = '';
		$feedsProc = 0;
		$totTime = 0;
		$totItems = 0;

		$cacheDir = JPATH_ROOT.'/cache';
		$cache_exists = ( !$fgConfig->get('use_sp_cache') OR !is_writable( $cacheDir ) ) ? false : true;

		if(!ini_get('allow_url_fopen')) ini_set('allow_url_fopen', 1); //allows importing images and text

		//process each feed
		foreach($formData as $feedId) {
			FeedgatorUtility::profiling('Start Process Feed: '.$feedId);
			$addItems = 0;
			$procItems = 0;
			$errors = array();
			$this->setId($feedId);
			// gets params with defaults overloaded
			$fgParams = $this->getParams(true);
			$fgParams->setValue('preview',null,$preview);
		//	echo '<pre>';
		//	jexit(print_r($fgParams));
			$this->_setFolderParams($preview,$fgParams);

			if ( !$fgParams->getValue('published') ) return JText::_('Feed Not Published');
			if (get_magic_quotes_gpc()){
				$fgParams->setValue('feed',null,stripslashes($fgParams->getValue('feed',null,true)));
			}

			// if cron check if should continue based on feed processing interval
			if($task == 'cron' OR $task == 'fgautomator') {
				$now = JFactory::getDate();

				if($last = $fgParams->getValue('last_run')) {
					$last = JFactory::getDate($last);
					$diff = $now->toUnix() - $last->toUnix();
				} else {
					$diff = -1;
				}

				if ($diff < 0 OR $diff > ( ($task == 'cron') ? $fgParams->getValue('cron_interval')*60 : $fgParams->getValue('pseudocron_interval')*60 ) ) {
					$doImport = 1;
				} else {
					$doImport = 0;
				}
			} else {
				$doImport = 1;
			}

			if($doImport) {
				$startTime = round(microtime(true),2);
				// attempt to stop timeouts and errors stopping all imports in cron/pseudo-cron
				try {
					// load internal plugin
					$this->getPlugin(null,$preview);
					if(isset($this->_plugin->errorMsg)) {
						return $this->_plugin->errorMsg;
					}

					// process the feed with SimplePie
					$rssDoc = new SimplePieFG();
					$rssDoc->set_input_encoding('utf-8');
					if($fgParams->getValue('feed_encoding')) {
						$rssDoc->set_input_encoding($fgParams->getValue('feed_encoding'));
					}
					$rssDoc->set_feed_url($fgParams->getValue('feed'));
					if ($fgParams->getValue('force_fsockopen')) {
						$rssDoc->force_fsockopen(true);
					}
					if($cache_exists) {
						$rssDoc->set_cache_location($cacheDir);
						$rssDoc->enable_cache(true);
						$rssDoc->set_cache_duration(60 * SPIE_CACHE_AGE);
					} else {
						$rssDoc->enable_cache(false);
					}
					$rssDoc->set_stupidly_fast(true);
					$rssDoc->enable_order_by_date(true);
					if($fgParams->getValue('set_sp_timeout')) {
						$rssDoc->set_timeout((int)$fgParams->getValue('set_sp_timeout'));
					}
					try {
						$rssDoc->init();
					}
					catch(Exception $e)
					{
						$feedsProc++;
						$feedMsg .= '<b>Feed import failed with error: '.$e->getMessage().'</b>';
						continue;
					}

					//$rssDoc->handle_content_type();
					if ($rssDoc->get_type() & SIMPLEPIE_TYPE_NONE) {
						return JText::sprintf('FG_UNABLE_TO_PROCESS',$fgParams->getValue('title').' ('.$fgParams->getValue('feed').')');
					} elseif($rssDoc->error) {
						return 'SimplePie error: '.$rssDoc->error.' for '.$fgParams->getValue('title').' ('.$fgParams->getValue('feed').')';
					} else {
						$channelTitle = $rssDoc->get_title();
						$itemArray = $rssDoc->get_items();

						if (is_array($itemArray)) {
							$num = count($itemArray)-1;
							for($i=$num;$i>=0;$i--) { //traverse items backwards to get the oldest item first
							//if($i == 1) break;
								$item = &$itemArray[$i];
								if($task == 'fgautomator') {
									$process = (boolean)(!$fgParams->getValue('pseudocron_import_limit',null,1) OR $addItems < $fgParams->getValue('pseudocron_import_limit',null,1));
								} elseif($task == 'cron') {
									$process = (boolean)(!$fgParams->getValue('cron_import_limit') OR $addItems < $fgParams->getValue('cron_import_limit'));
								} else {
									$process = (boolean)(!$fgParams->getValue('import_limit') OR $addItems < $fgParams->getValue('import_limit'));
								}
								if($process) {
									FeedgatorUtility::profiling('Start Process SimplePie Items: '.$item->get_id());
									$procItems++;
									if(!$content = FeedgatorHelper::processFeedItem($item,$fgParams,$this->_plugin,$this->_id,$channelTitle,$preview,$update)) {
										if($procItems == $num) {
											//preview new article has failed so show the first one in the stack
											FeedgatorUtility::profiling('Force Preview of Next Item');
											//need method to do this properly
											//$i = $num+1;
										}
										continue; // move to next item if no content generated
									}
									if(!$update AND $fgParams->getValue('create_art',null,1)) {
										//onBeforeFGSaveArticle -> $content contains the full article ready to save
								        $results = $dispatcher->trigger( 'onBeforeFGSaveArticle', array( $content, $fgParams) );

										if($preview) return FeedgatorHelper::getPreviewArticle($content,$fgParams,$channelTitle); // generate preview

										FeedgatorUtility::profiling('Start Save Content Item');
										// changed behaviour to stop errors quitting the processing of feed items
										if($this->_saveArticle($content,$fgParams)) {
											$addItems++;
										} else {
											$errors[] = $content['mosMsg'];
										}
										FeedgatorUtility::profiling('End Save Content Item');

										//onAfterFGSave -> $content contains the full article after saving with the new ID if applicable
								        $results = $dispatcher->trigger( 'onAfterFGSaveArticle', array( $content, $fgParams) );
									}
								}
								unset($content);
								if($i==0 AND $fgParams->getValue('create_art',null,1)) {
									$this->_plugin->reorder($fgParams->getValue('catid'),$fgParams);
								}
							}
						}
					} // end SimplePie processing
					FeedgatorUtility::profiling('End Process SimplePie Items');
					$rssDoc->__destruct();
					unset($itemArray,$rssDoc);
					FeedgatorUtility::profiling('Destroy SimplePie');

					// update last_run status
					$last_run = gmdate('Y-m-d H:i:s');
					$procTime = round(microtime(true),2) - $startTime;

					if(!$update) {
						if($fgParams->getValue('imports')) {
							$imports = explode(',',$fgParams->getValue('imports'));
							$imports[0] += $addItems;
							$imports[1] += $procItems;
							$imports[2] += $procTime;
							if(!$imports[3]) $imports[3] = $channelTitle;
						} else {
							if(!isset($imports)) $imports = array(0,0,0,'');
							$imports[0] += $addItems;
							$imports[1] += $procItems;
							$imports[2] += $procTime;
							$imports[3] = $channelTitle;
						}
						$this->_db->setQuery( 'UPDATE #__feedgator SET last_run = '.$this->_db->Quote($last_run).',imports = '.$this->_db->Quote(implode(',',$imports)).' WHERE id = '.(int)$feedId );
						$this->_db->query();
					}

					$feedMsg .= sprintf('<b>%d</b> new content item(s) imported (<i>%d processed</i>) in %ds for <b>%s</b> (%s).',$addItems,$procItems,$procTime,$fgParams->getValue('title'),$channelTitle);
					$feedMsg .= $fgParams->getValue('filtering') ? 'This feed import was filtered.<br />' : '<br />';
					$feedMsg .= !empty($errors) ? implode('<br/>',$errors).'<br />' : '';

					$feedsProc++;
					$totTime += $procTime;
					$totItems += $addItems;

					FeedgatorUtility::profiling('End Process Feed: '.$feedId);
					if($fgParams->getValue('debug')) {
						FeedgatorUtility::log(var_export($p->getBuffer()));
					}
				}
				catch(Exception $e)
				{
					$feedsProc++;
					$feedMsg .= '<b>Feed import failed with error: '.$e->getMessage().'</b>';
					continue;
				}
			}
		} // end foreach($formData as $feedId)

		if (!$feedsProc) {
			$adminMsg .= 'Nothing to process. Check your settings.';
			return $adminMsg;
		}

		$ajax = JFactory::getApplication()->input->get->get('ajax',0,'INT');
		if ($fgConfig->get('email_admin')) {
			FeedgatorUtility::profiling('Process For Admin Email');
			$eProc = 0;
			$eItems = 0;
			$eTime = 0;
			$eMsg = '';
			// check for digest mode, compile email if necessary and then update imports/last sent status
			$last = (boolean)($ajax AND JFactory::getApplication()->input->get->get('last',0,'INT'));
			if($fgConfig->get('email_digest',1) AND ($last OR $task == 'cron')) {
				$query = 'SELECT * FROM #__feedgator WHERE published = 1';
				$this->_db->setQuery($query);
				$rows = $this->_db->loadObjectList();
				$now = time();
				FeedgatorUtility::profiling('Process Digest Email');
				foreach($rows as &$row) {
					if(($last OR ($now >= ($row->last_email + ((int)$fgConfig->get('digest_period','24') *3600)))) AND $row->imports ) {
						$in[] = $row->id;
						$digest = explode(',',$row->imports); // imports contains $addItems,$procItems,$procTime,$channelTitle;
						if($fgConfig->get('send_if_null') OR $digest[0]) {
							$eMsg .= sprintf('<b>%d</b> new content item(s) imported (<i>%d processed</i>) in %ds for <b>%s</b> (%s). ',$digest[0],$digest[1],$digest[2],$row->title,$digest[3]);
							$eMsg .= $row->filtering ? 'This feed import was filtered.<br />' : '<br />';
							//$eMsg .= 'Now:'.$now.' Last email:'.($row->last_email + ((int)$fgConfig->get('digest_period','24') *360));
						}
						$eItems += $digest[0];
						$eTime += $digest[2];
						$eProc++;
					}
				}
				FeedgatorUtility::profiling('End Process Digest Email');
			}
			if (($eItems OR $fgConfig->get('send_if_null')) AND (!$fgConfig->get('email_digest',1) OR ($fgConfig->get('email_digest',1) AND isset($digest)))) {
				$exitTime  = JFactory::getDate('now', $tzOffset)->format('D F j, Y, H:i:s T',false,false);
				$adminMsg .= ( $fgConfig->get('email_digest',1) ? '<b>Feed Gator import digest report:</b>' : '<b>Results of the last Feed Gator import run:</b>')."\n\n";
				$adminMsg .= '<div id="feedinfo">'.($fgConfig->get('email_digest',1) ? '' : '<h1>START Feed Gator Import Processing: '.$initTime.'</h1>')."\n";
				$adminMsg .= '<span class="feedmsg">' . (isset($eMsg) ? $eMsg : $feedMsg) . "</span>\n";
				$adminMsg .= ($fgConfig->get('email_digest',1) ? '' : '<h1>END: '.$exitTime.'</h1>').'</div>'."\n";
				$adminMsg .= sprintf('<h2>%d content items imported in %d seconds (%d feeds processed)</h2>',$eItems,$eTime,$eProc);
				$adminMsg .= $ajax ? "\n".'<h4>May include imports which have not been notified by email earlier</h4>' : '';
				if(FeedgatorUtility::sendAdminEmail($adminMsg)) {
					$in = isset($in) ? implode(',',$in) : implode(',',$formData);
					$this->_db->setQuery( 'UPDATE #__feedgator SET last_email = '.$this->_db->Quote(time()).',imports = '.$this->_db->Quote('').' WHERE published = 1 AND id IN ('.$in.')');
					$this->_db->query();
				}
				FeedgatorUtility::profiling('Admin Email Sent');
			}
		}

		if($fgParams->getValue('debug')) {
			if(isset($p->_buffer)) {
				FeedgatorUtility::log(var_export($p->getBuffer()));
			}
		}

		$msg = $ajax ? sprintf('<div res="result" count="%d" proc="%d" time="%d">%s</div>',$totItems,$procItems,$totTime,$feedMsg) : sprintf('%s<br /><br /><b>%d</b> content items imported in %d seconds.<br /><br /><a href="javascript:closeMsgArea();">Close this window</a><br />',$feedMsg,$totItems,$totTime);
		return $msg;
	}

	function getLatestImports()
	{
		$query = 'SELECT * FROM #__feedgator_imports ORDER BY id DESC LIMIT 0,10';
		$this->_db->setQuery($query);
		$imports = $this->_db->loadAssocList();
		$rows = null;

		if(!empty($imports)) {
			foreach($imports as $import) {
				// at present we ignore enclosure only imports
				if($import['plugin'] != 'enclosure') {
					$this->setId($import['feed_id']);
					$this->getData();
					$ids[$import['plugin']]['ids'][] = $import['content_id'];
				}
			}
			if(isset($ids)) {
				foreach($ids as $content_type => &$data) {
					$plugin = $this->getPlugin($content_type);
					$where = ' WHERE c.id IN ('.implode(',',$data['ids']).')';
					$rparts[] = $plugin->getContentItemsQuery($where);
					$order = ' ORDER BY id DESC';
				}
				$rparts = (count($rparts) > 1) ? implode(' UNION ',$rparts) : $rparts[0];

				$this->_db->setQuery($rparts.$order);
				$rows = $this->_db->loadObjectList();
				if(is_array($rows)) {
					foreach($rows as &$row) {
						$plugin = $this->getPlugin($row->content_type);
						$row->content_link = $plugin->getContentLink($row->id);
						$row->feed_link = JRoute::_( 'index.php?option=com_feedgator&task=edit&cid[]='. $row->feedid );
					}
				}
			}
		}
		return $rows;
	}

	function upgradeComponentParams()
	{
		$crow = JTable::getInstance('component');

		// fix blank component entries error
		$query = 	"SELECT id " .
					"FROM #__components ".
					"WHERE name = '' " .
					"AND link = '' ".
					"AND menuid = '' ".
					"AND parent = '' ".
					"AND admin_menu_link = '' ".
					"AND admin_menu_alt = ''";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadResultArray();

		foreach($rows as $row) {
			$crow->delete($row);
		}

		$query = 'SELECT *' .
				' FROM #__components' .
				' WHERE ' . $this->_db->nameQuote( 'option' ) . '=' . $this->_db->Quote( 'com_feedgator' ) .
				' AND parent = 0';
		$this->_db->setQuery( $query, 0, 1 );
		$comp = $this->_db->loadObject();

		$params = FeedgatorUtility::parseINIString($comp->params);
		foreach ($params as $k => &$v) {
			switch($k)
			{
				case 'default_type':
					if(is_numeric($v)) {
						$v = ($v == '-2') ? 'com_k2' : 'com_content';
					}
				break 2;
			}
		}
		$params = FeedgatorUtility::makeINIString($params);
		$comp->params = $params;
		if($this->_db->updateObject('#__components',$comp,'id')) {
			return true;
		}
		return false;
	}

	function upgradeFeedParams($formData)
	{
		$frow = JTable::getInstance('Feed','Table');
		$irow = JTable::getInstance('Import','Table');

		foreach($formData as $feedId) {
			$frow->reset();
			$irow->reset();
			$this->setId($feedId);
			$data = $this->getData();

			$msg = '';

			// this needs improving to cover for other plugins/content types
			$frow->content_type = ($data->sectionid == -2) ? 'com_k2' : 'com_content';

			if(!empty($data->params)) {
				$params = FeedgatorUtility::parseINIString($data->params);
				//check params
				$nParams = array();
				$txt = array ();
				foreach ($params as $k => &$v) {
					switch($k)
					{
						case 'default_type':

						if(is_numeric($v)) {
							$nParams[$k] = ($v == '-2') ? 'com_k2' : 'com_content';
						}

						break;

						case 'save_img':

						if(!isset($params['alt_img_ext'])) {
							$nParams['alt_img_ext'] = $v;
							$nParams[$k] = 0;
						}

						break;

						case 'save_img_type':

						$nParams['alt_img_ext_type'] = $v;

						break;

						default:

						$nParams[$k] = $v;

						break;
					}
				}
				$data->params = FeedgatorUtility::makeINIString($nParams);
			}

			$imports = FeedgatorUtility::parseINIString($data->imports);
			$imports = array_unique($imports);

			foreach($imports as $hash => $content_id) {
				$tmpImp = array();
				$irow->id = null;
				$tmpImp['content_id'] = $content_id;
				$tmpImp['plugin'] = $this->_data->content_type;
				$tmpImp['hash'] = $hash;
				$tmpImp['feed_id'] = $this->_id;
				$irow->save($tmpImp);
			}
			$data->imports = '';
			if(!$frow->save($data)) $data_up = true;
		}
		if(isset($data_up)) {
			$msg =  '<br /><br /><strong class="red">There was a problem upgrading your feeds. Please check your parameters carefully</strong><br />'.
					'<br /><br /><strong><a href="index.php?option=com_feedgator">Click here to set up your feeds</a></strong>';
		} else {
			$msg = 	'<br /><br /><strong class="green">Old feeds upgrade successful!</strong>'.
					'<br /><br /><strong><a href="index.php?option=com_feedgator">Click here to set up your feeds</a></strong>';
		}
		return $msg;
	}

	function _loadImports()
	{
		if (empty($this->_imports) AND $this->_id)
		{
			$query = 'SELECT *'.
			' FROM #__feedgator_imports ' .
			' WHERE feed_id = '.(int) $this->_id;

			$this->_db->setQuery($query);
			$this->_imports = $this->_db->loadAssocList();

			return (boolean) $this->_imports;
		}
		return (boolean) $this->_id;
	}

	function _loadData($id = null)
	{
		if ((empty($this->_data) AND $this->_id) OR $id)
		{
			$query = 'SELECT *'.
			' FROM #__feedgator ' .
			' WHERE id = '.(int) ($id ? $id : $this->_id);

			$this->_db->setQuery($query);
			$data = $this->_db->loadObject();

			$id ? $this->_defaultData = $data : $this->_data = $data;

			return (boolean) $data;
		}
		return (boolean) $this->_id;
	}

	function _saveArticle(&$content,&$fgParams)
	{
		global $p;

		$user = JFactory::getUser();
		$imports = $this->getImports();

		if($content['id'] AND $fgParams->getValue('compare_existing') == 2) { // exhaustive duplicate check
			$exists = FeedgatorHelper::findDuplicates($content,$imports,$fgParams->getValue('hash'),$content['id'],$fgParams,$this->_plugin,$thorough=true,$exhaustive=true);

			if($exists AND is_int($exists)) {  // exists and no change
				FeedgatorUtility::profiling('Already Imported: Exhaustive Duplicate Check');
				return false;
			}

			//so now see what to do with new article
			switch($fgParams->getValue('merging'))
			{
				case 0: //don't merge, make new

					FeedgatorUtility::profiling('Already Imported: Ignore and Make New');
					break;

				case 1: //attempt to merge, makes new if fails

					if(JString::strpos($exists['introtext'].$exists['fulltext'], $content['introtext'].$content['fulltext']) !== false) {
						$exists['introtext'] = $content['introtext'];
						$exists['fulltext'] = $content['fulltext'];

						if($this->_plugin->save( $exists, $fgParams )) {
							FeedgatorUtility::profiling('Already Imported: Merged Article');
							return true;
						}
					}
					FeedgatorUtility::profiling('Already Imported: Failed Merge and Make New');
					break;

				case 2: // over-write

					$exists['introtext'] = $content['introtext'];
					$exists['fulltext'] = $content['fulltext'];
					$exists['overwrite'] = 1;

					$this->_plugin->save( $exists, $fgParams );
					FeedgatorUtility::profiling('Already Imported: Overwritten');

					return true;
			}
		}
		return $this->_plugin->save( $content, $fgParams );
	}
}