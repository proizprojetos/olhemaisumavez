<?php

/**
* FeedGator - Aggregate RSS newsfeed content into a Joomla! database
* @version 3.0a2
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

jimport( 'joomla.application.component.view' );
jimport('joomla.form.form');
JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_feedgator/tables');

class FeedgatorViewFeedgator extends JViewLegacy
{
	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		$user = JFactory::getUser();
		$model = FGFactory::getFeedModel();
		$toolsModel = FGFactory::getToolsModel();

		if(PHP_VERSION < 5) {
			$app->enqueueMessage(JText::_('FG_PHP_VERSION'),'error');
		}

		if(!class_exists('DOMDocument')) {
			$app->enqueueMessage(JText::_('FG_DOMDOCUMENT'),'error');
		}

		$edit = (in_array($app->input->get('task',null,'CMD'),array('new','add')) ? false : true);

		JHtml::_('behavior.tooltip');

		$doc->addStyleSheet('components/com_feedgator/css/styles.css');

		//deprecated but left as may be useful for jQuery conversion
		$this->request = "Request.HTML({
						method: 'get',
						noCache: true,
						url: url,";
		$this->json_request = 'Request.JSON({
								url: url,
								noCache: true,';
		$this->send = 'send';

		if($tpl) {

			if(!$edit) {
				$id = 0;
			} else {
				$cid			= $app->input->get( 'cid', array(0), '', 'array' );
				JArrayHelper::toInteger($cid, array(0));
				$id				= $app->input->get( 'id', $cid[0], '', 'int' );
			}
			$model->setId($id);

			if($tpl == 'settings') {
				$config = $model->getConfig();
				$this->config = $config;
			}

			elseif($tpl == 'imports') {
				$fgParams = $model->getParams();
				$this->fgParams = $fgParams;
				$this->buildImportLists();
			}

			elseif($tpl == 'tools') {
				$token = JSession::getFormToken();
				$base = JURI::base();

				$script = "
				window.addEvent( 'domready', function() {
					$('duplink').addEvent('click', function() {
						toggle($('duptable'));
					});
					$$('.dupdrill').addEvent('click', function(e) {
						var rel = e.target.getProperty('rel');
						toggle($(rel));
					});
					var toggle = function(el) {
						el.setStyle('display', (el.getStyle('display') == 'none') ? '' : 'none')
					}
					$$('.ignoredup').addEvent('click', function(e) {
						var type = e.target.getProperty('type');
						var rel = e.target.getProperty('rel');
						var base = '$base';
						var url = base+'index.php?option=com_feedgator&task=ignoreDuplicate&".$token."=1&type='+type+'&rel='+rel+'&format=raw';
						new $this->request
							onComplete: function() { $(rel).setStyle('display','none'); }
						}).$this->send();
					})
				});";
				$doc->addScriptDeclaration($script);

				if($dups = $toolsModel->getDuplicates()) {
					foreach($dups as &$dup) {
						$plugin = $model->getPlugin($dup->content_type);
						$data = explode('||',$dup->results);
						foreach($data as &$datum) {
							$datum = explode('|',$datum);
						}
						$dup->dups = array();
						for($i=0;$i<$dup->num;$i++) {
							$d = new StdClass();
							$d->id = @$data[$i][0];
							$d->content_link = $plugin->getContentLink($d->id);
							$d->sectionid = @$data[$i][1];
							$d->catid = @$data[$i][2];
							$d->title = @$data[$i][3];
							$dup->dups[$i] = $d;
						}
					}
				}
				$this->dups = $dups;
			}

			elseif($tpl == 'feed' OR $tpl == 'feed_default') {
				$fgParams = ($tpl == 'feed') ? $model->getParams() : $model->getDefaultParams(true);
				$this->fgParams = $fgParams;
				$this->buildEditLists();
				$cid = $fgParams->getValue('id');
				$token = JSession::getFormToken();
				$base = JUri::base();
				$selector = '$$(\'.panel12\')';
				$script = "
				window.addEvent( 'domready', function() {
					listContentItemTask = function( id, task ) {
					    var f = document.adminForm;
					    cb = eval( 'f.' + id );
					    if (cb) {
					        for (i = 0; true; i++) {
					            cbx = eval('f.cb'+i);
					            if (!cbx) break;
					            cbx.checked = false;
					        } // for
					        cb.checked = true;
					        f.boxchecked.value = 1;
					        f.option.value = 'com_content';
					        submitbutton(task);
					    }
					    return false;
					}

					$selector.addEvent('click', function() {
						var div = $('pluginparams');
						var base = '$base';
						var ext = $('paramscontent_type').getProperty('value');
						var url = base+'index.php?option=com_feedgator&task=getPluginParams&cid=$cid&ext='+ext+'&format=raw';
						new $this->request
							onRequest: function() { div.empty().appendText('Processing...').addClass('waiting'); },
							update: div,
							onComplete: function() { div.removeClass('waiting'); }
						}).$this->send();
					})
					/*$('panel13').addEvent('click', function() {
						var div = $('feedimports');
						var base = '$base';
						var ext = $('paramsontent_type').getProperty('value');
						var url = base+'index.php?option=com_feedgator&task=imports&filter_feedid=$cid&ajax=1&cid=$cid&ext='+ext+'&format=raw';
						new Request.HTML({
							method: 'get',
							url: url,
							onRequest: function() { div.empty().appendText('Processing...').addClass('waiting'); },
							update: div,
							onComplete: function() { div.removeClass('waiting'); }
						}).send();
					})*/";

				if($edit AND $app->input->get('task',null,'CMD') != 'editdefault') {

					$script .= "
					var base = '$base';
					var msgarea = $('fgmsgarea');
			    	var count = 0;
			    	var proc = 0;
			    	var time = 0;

					closeMsgArea = function() {
						msgarea.setStyle('display','none');
					}

					importFunc = function(type) {
						msgarea.empty().setStyle('display','block').appendText('Processing...').addClass('waiting');
						var arr = new Array();
						var obj = new Object();
						obj.id = $cid;
						obj.title = $('params_title').getProperty('value');
						arr[0] = obj;
						setupFeeds(arr,type);
					}

				    var setupFeeds = function(feeds,type) {
				    	count = 0;
			    		proc = 0;
			    		time = 0;
				        msgarea.empty();
			        	var d = new Element('div', {'id':'fgimports'}).inject(msgarea);
			        	var l = feeds.length;
			        	var i = 0;
			        	var last = 0;
				        feeds.each(function(feed) {
				        	if(i == l-1) { last = 1; }
				            var ti = new Element('div', {'class':'title','rel': feed.id}).setStyle('display','none').appendText('Feed '+feed.title+' (ID='+feed.id+')').inject(d);
				            var de = new Element('div', {'class': 'toimport','rel': feed.id}).setProperty('last',last).inject(d);
				            i++;
				        });
				        msgarea.removeClass('waiting');
				        if(type == 'all') { type = 'feed'; }
			        	importFeeds(type);
				    }

				    var importFeeds = function(type) {
			    		var imports = $$('.toimport');
				    	if(imports.length > 0) {
				    		var div = new Element('div');
							var url = base+'index.php?option=com_feedgator&task=import&$token=1&ajax=1&type='+type+'&cid[]='+imports[0].getProperty('rel')+'&last='+imports[0].getProperty('last')+'&format=raw';
							new $this->request
   								onRequest: function() { imports[0].appendText('Processing...').addClass('waiting'); },
   								update: div,
					            onComplete: function() { updateTable(div,imports[0],type); }
					        }).$this->send();
				    	}
				    }

				    var updateTable = function(div,de,type) {
				    	var data = div.getFirst('div');
				    	if(data && data.getProperty('res')) {
					    	data.inject(de.empty().removeClass('waiting'));
						    count = count+(1*data.getProperty('count'));
						    proc = proc+(1*data.getProperty('proc'));
						    time = time+(1*data.getProperty('time'));
				    	} else {
				    		div.inject(de.empty().removeClass('waiting'));
				    	}
					    de.removeClass('toimport').addClass('imported');
					    importFeeds(type);
					}";
				}
				$script .= "
				})";
				$doc->addScriptDeclaration($script);

				if($edit) {
					if ($model->isCheckedOut( $user->get('id') )) {
						$msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_( 'The feed' ), $model->get('title') );
						$app->redirect( 'index.php?option=com_feedgator', $msg );
					}
				}
			}

			elseif($tpl == 'feeds') {
				$this->buildFeedLists($fgParams);
				$token = JSession::getFormToken();
				$base = JUri::base();
				$script = "
				window.addEvent( 'domready', function() {
					var base = '$base';
					var msgarea = $('fgmsgarea');
			    	var count = 0;
			    	var proc = 0;
			    	var time = 0;

					closeMsgArea = function() {
						msgarea.setStyle('display','none');
					}

					importFunc = function(type) {
						var really = false;

						if(type == 'all') {
							var really = confirm('Are you sure you want to Import All RSS feeds?');
						} else {
							if ($('adminForm').boxchecked.value == 0) {
								alert('Please make a selection from the list to import');
							} else {
								var really = true;
							}
						}

						if (really) {
							var ids = new Array();
							var checks = $$('#adminForm input').filter(function(item){
								if(item.getProperty('type') == 'checkbox' && item.getProperty('name') != 'toggle') return item;
							});
							checks.each(function(el) {
								if(el.checked || type == 'all') {
									ids.include(el.getProperty('value'));
								}
							});
							if(type == 'all') {
								var url = base+'index.php?option=com_feedgator&task=import&".$token."=1&ajax=1&type='+type+'&cid[]='+ids+'&format=raw';
								new $this->json_request
	   								onRequest: function() { msgarea.empty().setStyle('display','block').appendText('Processing...').addClass('waiting'); },
						            onComplete: function(jsonObj) {
						                setupFeeds(jsonObj,type);
						            }
						        }).send();
							} else {
								msgarea.empty().setStyle('display','block').appendText('Processing...').addClass('waiting');
								var arr = new Array();
								for(i=0;i<ids.length;i++) {
									var obj = new Object();
									obj.id = ids[i];
									obj.title = $$('.feedtitle').filter(function(item){
										if(item.getProperty('rel') == ids[i]) return item.getProperty('title');
									});
									arr[i] = obj;
								};
								setupFeeds(arr,type);
							}
						}
					}

				    var setupFeeds = function(feeds,type) {
				    	count = 0;
			    		proc = 0;
			    		time = 0;
				        msgarea.empty();
			        	var d = new Element('div', {'id':'fgimports'}).inject(msgarea);
			        	var l = feeds.length;
			        	var i = 0;
			        	var last = 0;
				        feeds.each(function(feed) {
				        	if(i == l-1) { last = 1; }
				            var ti = new Element('div', {'class':'title','rel': feed.id}).setStyle('display','none').appendText('Feed '+feed.title+' (ID='+feed.id+')').inject(d);
				            var de = new Element('div', {'class': 'toimport','rel': feed.id}).setProperty('last',last).inject(d);
				            i++;
				        });
				        msgarea.removeClass('waiting');
				        if(type == 'all') { type = 'feed'; }
			        	importFeeds(type);
				    }

				    var importFeeds = function(type) {
			    		var imports = $$('.toimport');
				    	if(imports.length > 0) {
				    		var div = new Element('div');
							var url = base+'index.php?option=com_feedgator&task=import&".$token."=1&ajax=1&type='+type+'&cid[]='+imports[0].getProperty('rel')+'&last='+imports[0].getProperty('last')+'&format=raw';
							new $this->request
   								onRequest: function() { imports[0].appendText('Processing...').addClass('waiting'); },
   								update: div,
					            onComplete: function() { updateTable(div,imports[0],type); }
					        }).$this->send();
				    	} else {
				    		if(type != 'preview') {
					    		var br = new Element('br');
					    		var totals = new Element('div', {'class':'totals'}).adopt(br).appendText(count+' content item(s) imported ('+proc+' processed) in '+time+' seconds.').inject($('fgimports'));
					    		var br2 = br.clone().inject(totals);
					    		var a = new Element('a', {'href':'javascript:closeMsgArea();'}).appendText('Close this window').inject(totals);
				    		}
				    	}
				    }

				    var updateTable = function(div,de,type) {
				    	var data = div.getFirst('div');
				    	if(data && data.getProperty('res')) {
					    	data.inject(de.empty().removeClass('waiting'));
						    count = count+(1*data.getProperty('count'));
						    proc = proc+(1*data.getProperty('proc'));
						    time = time+(1*data.getProperty('time'));
				    	} else {
				    		if(type != 'preview') {
				    			var rel = de.getProperty('rel');
					    		var ti = $$('.title').filter(function(item){ if(item.getProperty('rel') == rel) item.setStyle('display','').appendText(': Error - ') });
				    		}
				    		div.inject(de.empty().removeClass('waiting'));
				    	}
					    de.removeClass('toimport').addClass('imported');
					    importFeeds(type);
					}
				})";
				$doc->addScriptDeclaration($script);

				$pluginModel= FGFactory::getPluginModel();
				$this->plugins = $pluginModel->loadInstalledPlugins();
			} elseif($tpl == 'about') {
				$fgParams = $model->getParams();
				$this->fgParams = $fgParams;
				$version_data = $toolsModel->checkLatestVersion($fgParams);
				$this->version_data = $version_data;
			}

		} else { // default page

			$fgParams = $model->getParams();
			$version_data = $toolsModel->checkLatestVersion($fgParams);
			$base = (boolean)$fgParams->getValue('base');
			$defaults = (boolean)$model->_loadData(-2);
			$this->jplugin = $toolsModel->checkJPlugins();
			$this->fgplugins = $toolsModel->checkPlugins();
			$this->version_data = $version_data;
			$this->import_sync = $toolsModel->checkImports();
			$this->duplicates = $toolsModel->findDuplicates();
			$this->latest_imports = $model->getLatestImports();
			$this->globals = $base;
			$this->defaults = $defaults;
		}

		parent::display($tpl);
	}

	function buildEditLists()
	{
		$app			= JFactory::getApplication();
		$user			= JFactory::getUser();
		$db				= JFactory::getDBO();
		$model 			= FGFactory::getFeedModel();
		$option			= $app->input->get('option',null,'CMD');

		if ($model->get('id') AND !in_array($app->input->get('task',NULL,'cmd'),array('new','add'))) {
			$model->checkout($user->get('id'));
			jimport('joomla.utilities.date');
			$createdate = new JDate($this->fgParams->getValue('created'));
			$this->fgParams->setValue('created',null,$createdate->toUnix());
		}

		//$default = ($tpl == 'feed') ? 1 : 0;
		$default = false;
		$dynaLists = FeedgatorHelper::getDynaLists($this->fgParams,$default);

		$this->contentsections = $dynaLists['contentsections'];
		$this->sectioncategories = $dynaLists['sectioncategories'];
	}

	function buildFeedLists(&$fgParams)
	{
		$app		= JFactory::getApplication();
		$db			= JFactory::getDBO();
		$model 		= FGFactory::getFeedModel();
		$pluginModel= FGFactory::getPluginModel();
		$context	= 'com_feedgator.feeds';
		$option		= $app->input->get('option',null,'CMD');
		$limit      = $app->getUserStateFromRequest( $context.'viewlistlimit', 'limit', 10 ,'int');
		$limitstart = $app->getUserStateFromRequest( $context.'view'.$option.'limitstart', 'limitstart', 0 ,'int');
		$search     = $app->getUserStateFromRequest( "search{$option}", 'search', '' ,'word');
		$search				= JString::strtolower($search);
		$filter_order		= $app->getUserStateFromRequest( $context.'filter_order',		'filter_order',		'fg.id',	'cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'asc',	'word' );

		$plugins_data = $pluginModel->loadInstalledPlugins();

		$where = array();
		if ($search) {
			$where[] =  '(LOWER( fg.title ) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ) .
						' OR fg.id = ' . (int) $search . ')';
		}
		//ensures default not shown in feed list
		$where[] = ' fg.id > 0';

		$where = (count($where) ? ' WHERE '.implode(' AND ', $where) : '');

		$db->setQuery( 'SELECT * FROM #__feedgator WHERE id > 0');
		if($feeds = $db->loadObjectList()) {
			// need to identify feeds assigned to an unpublished plugin
			$found = array();
			foreach($feeds as &$feed) {
				if(!in_array($feed->content_type,$found)) {
					foreach($plugins_data as &$plugin_data) {
						if($plugin_data->extension == $feed->content_type) {
							$plugin =$model->getPlugin($plugin_data->extension);
							if(!isset($plugin->errorMsg)) {
							isset($rows) ? array_splice($rows, count($rows), 0, $plugin->getFeedItems($where)) : $rows = $plugin->getFeedItems($where);
							$found[] = $feed->content_type;
						}
					}
					}
					if(!in_array($feed->content_type,$found)) {
						$feed->title .= ' - '.JText::_('Plugin Missing');
						$feed->cat_name = '<strong><i>'.JText::_('Plugin Missing').'</i></strong>';
						$feed->section_name = '<strong><i>'.JText::_('Plugin Missing').'</i></strong>';
						$feed->editor = '<strong><i>'.JText::_('Plugin Missing').'</i></strong>';
						isset($rows) ? array_splice($rows, count($rows), 0, array($feed)) : $rows = array($feed);
					}
				}
			}
		}
		$total = (isset($rows) ? count($rows) : 0);
		// Create the pagination object
		jimport('joomla.html.pagination');
		$pagination = new JPagination($total, $limitstart, $limit);
		if(!empty($rows)) {
			JArrayhelper::sortObjects( $rows, $this->_getFilterAlias($filter_order), $filter_order_Dir == 'asc' ? 1 : -1 );
			$rows = array_slice($rows,$pagination->limitstart,$pagination->limit);
		}

		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;
		// search filter
		$lists['search'] = $search;

		$this->rows = isset($rows) ? $rows : array();
		$this->page = $pagination;
		$this->search = $search;
		$this->lists = $lists;
	}

	function buildImportLists()
	{
		// Initialize variables
		$app		= JFactory::getApplication();
		$db			= JFactory::getDBO();
		$model 		= FGFactory::getFeedModel();
		$pluginModel= FGFactory::getPluginModel();
		$filter		= null;

		// Get some variables from the request
		$option				= $app->input->get('option',null,'CMD');
		$context			= 'com_feedgator.imports';
		$filter_order		= $app->getUserStateFromRequest( $context.'filter_order',		'filter_order',		'',	'cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'',	'word' );
		$filter_state		= $app->getUserStateFromRequest( $context.'filter_state',		'filter_state',		'',	'word' );
		$filter_authorid	= $app->getUserStateFromRequest( $context.'filter_authorid',	'filter_authorid',	0,	'int' );
		$filter_feedid		= $app->getUserStateFromRequest( $context.'filter_feedid',		'filter_feedid',	-1,	'int' );
		$search				= $app->getUserStateFromRequest( $context.'search',				'search',			'',	'string' );
		if (strpos($search, '"') !== false) {
			$search = str_replace(array('=', '<'), '', $search);
		}
		$search = JString::strtolower($search);

		$filter_sectionid	= $app->getUserStateFromRequest( $context.'filter_sectionid',	'filter_sectionid',	-1,	'cmd' ); // need to change to view default content type by default
		if($filter_sectionid AND $filter_sectionid < -1) {
			$s_pluginid	= -1*$filter_sectionid;
		}

		$filter_catid		= $app->getUserStateFromRequest( $context.'filter_catid',		'filter_catid',	'',	'cmd' ); // adjusted to allow per-plugin categories
		if($filter_catid) {
			$c_pluginid	= substr($filter_catid,0,strpos($filter_catid,'_'));
			$filter_catid		= substr($filter_catid,strpos($filter_catid,'_')+1);
			if($filter_catid == 0) unset($c_pluginid);
		}
		if(@$s_pluginid != @$c_pluginid AND isset($s_pluginid)) {
			$pluginid = $s_pluginid;
		} elseif(isset($c_pluginid)) {
			$pluginid = $c_pluginid;
		}

		$limit		= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart	= $app->getUserStateFromRequest($context.'limitstart', 'limitstart', 0, 'int');

		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ( $limit != 0 ? (floor($limitstart / $limit) * $limit) : 0 );

		if (!$filter_order) {
			$filter_order = 'id';
		}
		if ($filter_order == 'ordering') {
			$order = ' ORDER BY section_name, cat_name, ordering '. $filter_order_Dir;
		} else {
			$order = ' ORDER BY '. $this->_getFilterAlias($filter_order) .' '. $filter_order_Dir .', section_name, cat_name, ordering';
		}

		// only load content for the relevant plugin for the feed view
		$plugins_data = $pluginModel->loadInstalledPlugins();
		foreach($plugins_data as $plugin_data) {
			if($this->fgParams->getValue('id') OR isset($pluginid)) {
				$plugin =$model->getPlugin($plugin_data->extension);
				if($plugin_data->extension == $this->fgParams->getValue('content_type') OR $plugin_data->id == @$pluginid) {
					$where = $this->_buildWhere($plugin_data->extension == 'com_content' ? true : false,$filter_feedid,$filter_sectionid,$filter_catid,$filter_authorid,$filter_state,$search,$db,$this->fgParams);
					$tparts = $plugin->countContentItems($where);
					$rparts = $plugin->getContentItemsQuery($where);
					$categories = $plugin->getCatSelectLists($filter,$this->fgParams);
					isset($sections) ?  array_splice($sections, count($sections), 0, $plugin->getSecSelectLists($this->fgParams)) : $sections = $plugin->getSecSelectLists($this->fgParams);
				} elseif(isset($pluginid)) {
					isset($sections) ?  array_splice($sections, count($sections), 0, $plugin->getSecSelectLists($this->fgParams)) : $sections = $plugin->getSecSelectLists($this->fgParams);
				}
			} else {
				if($plugin_data->published) {
					$plugin =$model->getPlugin($plugin_data->extension);
					$where = $this->_buildWhere($plugin_data->extension == 'com_content' ? true : false,$filter_feedid,$filter_sectionid,$filter_catid,$filter_authorid,$filter_state,$search,$db,$this->fgParams);
					$tparts[] = $plugin->countContentItems($where);
					$rparts[] = $plugin->getContentItemsQuery($where);
					isset($categories) ? array_splice($categories, count($categories), 0, $plugin->getCatSelectLists($filter,$this->fgParams)) : $categories = $plugin->getCatSelectLists($filter,$this->fgParams);
					isset($sections) ?  array_splice($sections, count($sections), 0, $plugin->getSecSelectLists($this->fgParams)) : $sections = $plugin->getSecSelectLists($this->fgParams);
				}
			}
		}
		is_array($tparts) ? $tparts = implode(' + ',$tparts) : '';
		$db->setQuery('SELECT '.$tparts);
		$total = $db->loadResult();

		// Create the pagination object
		jimport('joomla.html.pagination');
		$pagination = new JPagination($total, $limitstart, $limit);
		if(is_array($rparts)) $rparts = implode(' UNION ',$rparts); // each part of the union has where clause...
		$db->setQuery($rparts.$order, $pagination->limitstart, $pagination->limit);
	//	echo($db->_sql);
		$rows = $db->loadObjectList();
	//	print_r($rows);
		foreach($rows as &$row) {
			$plugin =$model->getPlugin($row->content_type);
			$row->content_link = $plugin->getContentLink($row->id);
		}
		$javascript = 'onchange="document.adminForm.submit();"';
		$lists['catid'] = JHTML::_('select.genericlist',  $categories, 'filter_catid', 'class="inputbox" size="1" '.$javascript, 'value', 'text', isset($pluginid) ? $pluginid.'_'.$filter_catid : $filter_catid);
		$lists['sectionid'] = JHTML::_('select.genericlist',  $sections, 'filter_sectionid', 'class="inputbox" size="1" '.$javascript, 'value', 'text', $filter_sectionid);

		// get list of feeds
		$db->setQuery( 'SELECT id AS value, title AS text FROM #__feedgator WHERE published = 1' );
		$feeds[] = JHTML::_('select.option', '0', '- '.JText::_('Select Feed').' -', 'value', 'text');
		$feeds = array_merge($feeds,$db->loadObjectList());
		$lists['feed'] = JHTML::_('select.genericlist',  $feeds, 'filter_feedid', 'class="inputbox" size="1" '.$javascript, 'value', 'text', $filter_feedid);

		// get list of Authors for dropdown filter
		$query = 'SELECT c.created_by, u.name' .
				' FROM #__content AS c' .
				' LEFT JOIN #__users AS u ON u.id = c.created_by' .
				' WHERE c.state <> -1' .
				' AND c.state <> -2' .
				' GROUP BY u.name' .
				' ORDER BY u.name';
		$authors[] = JHTML::_('select.option', '0', '- '.JText::_('Select Author').' -', 'created_by', 'name');
		$db->setQuery($query);
		$authors = array_merge($authors, $db->loadObjectList());
		$lists['authorid'] = JHTML::_('select.genericlist',  $authors, 'filter_authorid', 'class="inputbox" size="1" '.$javascript, 'created_by', 'name', $filter_authorid);

		// state filter
		$lists['state'] = JHTML::_('grid.state', $filter_state, 'Published', 'Unpublished', 'Archived', 'Trashed');

		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;

		// search filter
		$lists['search'] = $search;

		$this->model = $model; //only added to allow hack for strict error with JHTML::_('grid.checkedout',$row,$i);
		$this->rows = $rows;
		$this->page = $pagination;
		$this->search = $search;
		$this->lists = $lists;
	}

	function _getFilterAlias($str)
	{
		if($pos = strpos($str,'.')) {
			$str = substr($str,strlen(substr($str,0,$pos+1)));
		}
		return $str;
	}

	function _buildWhere($com_content,$filter_feedid,$filter_sectionid,$filter_catid,$filter_authorid,$filter_state,$search,&$db,&$fgParams)
	{
		// this function is an horrid hack to avoid trying to sort on sectionid for other content types - this is for review!
		//$where[] = "c.state >= 0";
		$where[] = 'fi.content_id = c.id';

		/*
		 * Add the filter specific information to the where clause
		 */

		if ($filter_sectionid >= 0 AND $com_content) {
			$filter = ' WHERE cc.section = '. (int) $filter_sectionid;
		}

		// Feed filter
		if ($filter_feedid > 0) {
			$where[] = 'fg.id = ' . (int) $filter_feedid;
		}
		// Section filter
		if ($filter_sectionid > 0 AND !$fgParams->getValue('id') AND $com_content) {
			$where[] = 'c.sectionid = ' . (int) $filter_sectionid;
		}
		// Category filter
		if ($filter_catid > 0 AND !$fgParams->getValue('id')) {
			$where[] = 'c.catid = ' . (int) $filter_catid;
		}
		// Author filter
		if ($filter_authorid > 0) {
			$where[] = 'c.created_by = ' . (int) $filter_authorid;
		}
		// Content state filter
		if ($filter_state) {
			if ($filter_state == 'P') {
				$where[] = 'c.state = 1';
			} else {
				if ($filter_state == 'U') {
					$where[] = 'c.state = 0';
				} else if ($filter_state == 'A') {
					$where[] = 'c.state = 2';
				} else if ($filter_state == 'T' OR $filter_state == 'D') {
					$where[] = 'c.state = -2';
				} else {
					$where[] = 'c.state != -2';
				}
			}
		} else {
			$where[] = 'c.state != -2';
		}
		// Keyword filter
		if ($search) {
			$where[] = '(LOWER( c.title ) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ) .
				' OR c.id = ' . (int) $search . ')';
		}

		// Build the where clause of the content record query
		$where = (count($where) ? ' WHERE '.implode(' AND ', $where) : '');

		return $where;
	}
}