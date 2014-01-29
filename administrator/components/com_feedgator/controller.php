<?php

/**
* FeedGator - Aggregate RSS newsfeed content into a Joomla! database
* @version 3.0a1
* @package FeedGator
* @author Original author Stephen Simmons
* @now continued and modified by Matt Faulds, Remco Boom & Stephane Koenig and others
* @email mattfaulds@gmail.com
* @Joomla 1.5 Version by J. Kapusciarz (mrjozo)
* @copyright (C) 2005 by Stephen Simmons, 2010 by Matt Faulds - All rights reserved.
* @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
*
**/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
jimport('joomla.client.helper');
JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_feedgator/tables');

/**
 * Feedgator Component Controller
 *
 * @since 1.5
 */
class FeedgatorController extends JControllerLegacy
{
	public function __construct( $config = array())
	{
		parent::__construct( $config );
		$this->_db = JFactory::getDbo();
	}

	// feedgator

	public function cpanel()
	{
		$model = FGFactory::getFeedModel();
		$view = $this->getView('Feedgator','html');
		$view->setModel($model);
		$view->display();
	}

	public function feeds()
	{
		$model = FGFactory::getFeedModel();
		$view = $this->getView('Feedgator','html');
		$view->setModel($model);
		$view->display('feeds');
	}

	public function settings()
	{
		$model = FGFactory::getFeedModel();
		$view = $this->getView('Feedgator','html');
		$view->setModel($model);
		$view->display('settings');
	}

	public function tools()
	{
		$model = FGFactory::getFeedModel();
		$view = $this->getView('Feedgator','html');
		$view->setModel($model);
		$view->display('tools');
	}

	public function imports()
	{
		$ajax = JFactory::getApplication()->input->get->get('ajax',0,'INT');
		$model = FGFactory::getFeedModel();
		$view = $this->getView('Feedgator','html');
		$view->setModel($model);
		if($ajax) {
			echo $view->display('imports');
			jexit();
		}
		$view->display('imports');
	}

	public function about()
	{
		$model = FGFactory::getFeedModel();
		$view = $this->getView('Feedgator','html');
		$view->setModel($model);
		$view->display('about');
	}

	public function support()
	{
		$model = FGFactory::getFeedModel();
		$view = $this->getView('Feedgator','html');
		$view->setModel($model);
		$view->display('support');
	}

	public function saveSettings($apply = false)
	{
		JSession::checkToken() or jexit( 'Invalid Token' );

		$input = JFactory::getApplication()->input;

		$component = $input->post->get( 'option','','CMD' );
		$table = JTable::getInstance('extension');
		$function = 'find';
		$component = array('element'=>$component);
		$id = $table->$function( $component );
		if ( !$table->load( $id )) $error = 1;

		if (isset($error))
		{
			JError::raiseWarning( 500, 'Not a valid component' );
			return false;
		}
		$post = array();
		$post['params'] = $input->post->get('params', array() ,'ARRAY');

		if(!$table->save( $post )) {
			JError::raiseWarning( 500, $table->getError() );
			return false;
		}

		$link = $apply ? 'index.php?option=com_feedgator&task=settings' : 'index.php?option=com_feedgator&task=feeds';
		$msg = $apply ? JText::_('Changes Applied') : JText::_('Settings Saved');
		$this->setRedirect($link,$msg);
	}

	public function upgrade()
	{
		JSession::checkToken('request') or die( 'Invalid Token' );

		$model = FGFactory::getFeedModel();
		$model->upgradeComponentParams();

		echo $this->importAll($update=true);
		jexit();
	}

	// feed

	public function copyFeed()
	{
		JSession::checkToken() or die( 'Invalid Token' );
		$cid = JFactory::getApplication()->input->post->get( 'cid', array(), 'ARRAY' );
		JArrayHelper::toInteger($cid);
		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to '.$action ) );
		}
		$model = FGFactory::getFeedModel();
		if(!$model->copy($cid)) {
			$msg = 'Error copying feed(s): '.$model->getError(true);
		}
		$msg = count( $cid ) .' feed(s) copied!';
		$this->setRedirect( 'index.php?option=com_feedgator&task=feeds',$msg );
	}

	public function editFeed($default = false)
	{
		if($default) JFactory::getApplication()->input->set('cid',-2);
		$model = FGFactory::getFeedModel();
		If(!$default AND !$model->getDefaultParams()) {
			$link = 'index.php?option=com_feedgator&task=editdefault';
			$msg = 'You must save the default feed before you can add a new feed';
			$this->setRedirect($link, $msg);
			$this->redirect();
		}
		$view = $this->getView('Feedgator','html');
		$view->setModel($model);
		$view->display( $default ? 'feed_default' : 'feed');
	}

	public function saveFeed($apply = false, $default = false)
	{
		JSession::checkToken() or die( 'Invalid Token' );
		$cid = JFactory::getApplication()->input->post->get('cid','','INT');
		$post = JFactory::getApplication()->input->post->get('params', array() ,'ARRAY');

		$model = FGFactory::getFeedModel();
		$deffgParams = $model->getDefaultParams();
		// force default content type
		if($post['content_type'] == '') {
			$post['content_type'] = $deffgParams->get('content_type');
		}
		$msg = $model->store($post) ? JText::_( 'Feed Saved' ) : JText::_( 'Error Saving Feed' );
		$model->checkin();

		$this->savePluginSettings($cid,$post['content_type']);

		$edit = $default ? 'editdefault' : 'edit';

		$link = $apply ? 'index.php?option=com_feedgator&task='.$edit.'&cid[]='.$cid : 'index.php?option=com_feedgator&task=feeds';
		$this->setRedirect($link, $msg);
	}

	public function publishFeeds($publish = 1, $action = 'publish')
	{
		JSession::checkToken() or die( 'Invalid Token' );

		$cid = JFactory::getApplication()->input->post->get( 'cid', array(), 'ARRAY' );
		JArrayHelper::toInteger($cid);
		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to '.$action ) );
		}
		$model = FGFactory::getFeedModel();
		if(!$model->publish($cid, $publish)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
		$this->setRedirect( 'index.php?option=com_feedgator&task=feeds' );
	}

	/**
	* Changes the frontpage state of one or more feeds
	*
	*/
	public function frontpageFeeds($frontpage = 1, $action = 'front_yes')
	{
		JSession::checkToken() or die( 'Invalid Token' );

		$cid = JFactory::getApplication()->input->post->get( 'cid', array(), 'ARRAY' );
		JArrayHelper::toInteger($cid);
		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to '.$action ) );
		}
		$model = FGFactory::getFeedModel();
		if(!$model->frontpage($cid, $frontpage)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
		$this->setRedirect( 'index.php?option=com_feedgator&task=feeds' );
	}

	public function remove()
	{
		JSession::checkToken() or die( 'Invalid Token' );

		// Initialize variables
		$cid = JFactory::getApplication()->input->post->get( 'cid', array(), 'ARRAY' );
		JArrayHelper::toInteger($cid);
		if (count($cid) < 1) {
			$msg =  JText::_('Select an item to delete');
			$app->redirect('index.php?option=com_feedgator', $msg, 'error');
		}
		$model = FGFactory::getFeedModel();
		if(!$model->delete($cid, $frontpage)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
		$msg = JText::sprintf('Item(s) deleted', count($cid));
		$this->setRedirect('index.php?option=com_feedgator&task=feeds', $msg);
	}

	/**
	* Cancels an edit operation
	*/
	public function cancel()
	{
		JSession::checkToken() or die( 'Invalid Token' );

		$model = FGFactory::getFeedModel();
		$model->checkin();
		$this->setRedirect('index.php?option=com_feedgator&task=feeds');
	}

	public function import($type=null)
	{
		JSession::checkToken('request') or die( 'Invalid Token' );

		if(!$type) $type = JFactory::getApplication()->input->get->get( 'type', '', 'WORD' );
		$formData = JFactory::getApplication()->input->get( 'cid', array(), 'ARRAY' );

		switch($type)
		{
			case 'all':

			if(JFactory::getApplication()->input->get( 'task','','CMD' ) == 'fgautomator') {
				return $this->importAll();
			} else {
				$this->importAll();
			}

			break;

			case 'feed':

			$this->importFeed($formData);

			break;

			case 'preview':

			$this->importFeed( $formData, true );

			break;
		}
	}

	private function importAll($update=false)
	{
		$ajax = JFactory::getApplication()->input->get->get('ajax',0,'INT');
		if($ajax) {
			$this->_db->setQuery( 'SELECT id, title FROM #__feedgator WHERE id > 0 AND published = 1 ORDER BY id' );
			$formData = $this->_db->loadAssocList();
			echo json_encode($formData);
			jexit();
		} else {
			$this->_db->setQuery( 'SELECT id FROM #__feedgator WHERE id > 0 AND published = 1 ORDER BY id' );
			$formData = $this->_db->loadColumn();
			return $this->importFeed( $formData, $preview=false, $update);
		}
	}

	private function importFeed( $formData = '', $preview = false, $update = false)
	{
		$model = FGFactory::getFeedModel();
		if($update) {
			echo $model->upgradeFeedParams($formData);
			jexit();
			//return $model->import($formData,$preview,$update);
		} else {
			if(JFactory::getApplication()->input->get( 'task','','CMD' ) == 'fgautomator') {
				return $model->import($formData,$preview,$update);
			} else {
				echo $model->import($formData,$preview,$update);
				jexit();
			}
		}
	}

	// plugin

	public function plugins()
	{
		$model = FGFactory::getPluginModel();
		$view = $this->getView('plugin','html');
		$view->setModel($model);
		$view->display();
	}

	public function pluginSettings()
	{
		$model = FGFactory::getPluginModel();
		$view = $this->getView('plugin','html');
		$view->setModel($model);
		$view->display('settings');
	}

	public function savePluginSettings($feedId = null, $content_type = null)
	{
		if($feedId === null) $feedId = JFactory::getApplication()->input->get('feedId',-2,'INT');
		if($content_type === null) $content_type = JFactory::getApplication()->input->get( 'ext','','CMD' );

		$pluginModel = FGFactory::getPluginModel();
		$pluginModel->setExt($content_type);
		$msg = ($pluginModel->store($feedId)) ?	'Default plugin settings saved' : 'Default plugin settings not saved';

		$this->setRedirect( 'index.php?option=com_feedgator&task=plugins',$msg );
	}

	public function changePluginState()
	{
		$id = JRequest::getInt('id');
		$ext = JRequest::getCmd('ext','');

		//need to check component installed!
		$model = FGFactory::getPluginModel();
		$plugin = $model->getPlugin($ext);
		if($plugin->componentCheck()) {
			$row = JTable::getInstance('FGPlugin','Table');
			$row->load($id);

			$name = $model->getFilename($row->extension);

			$pquery = "SELECT enabled FROM #__extensions WHERE element='$name' AND folder='feedgator'";
			$this->_db->setQuery($pquery);
			$row->published = ($this->_db->loadResult() ? 0 : 1);

			if ($row->store()) {
				$query = "UPDATE #__extensions SET enabled=$row->published WHERE element='$name' AND folder='feedgator'";
				$this->_db->setQuery($query);
				$this->_db->query();
				$msg = $row->published ? JText::_('Plugin Published') : JText::_('Plugin Unpublished');
			} else {
				$msg = $this->_db->getErrorMsg();
			}
		} else {
			$msg = JText::_('Unable to publish plugin - component not installed!');
		}
		$this->setRedirect('index.php?option=com_feedgator&task=plugins', $msg);
	}

	public function getPluginParams()
	{
		$cid = JFactory::getApplication()->input->get->get('cid','','INT');

		$model = FGFactory::getPluginModel();
		echo $model->renderPluginParams($cid);
		jexit();
	}

	// tools

	public function syncImports()
	{
		$model = FGFactory::getToolsModel();
		$model->syncImports();
	}

	public function ignoreDuplicate()
	{
		JSession::checkToken('request') or die( 'Invalid Token' );

		$model = FGFactory::getToolsModel();
		$model->ignoreDuplicate();
	}
}