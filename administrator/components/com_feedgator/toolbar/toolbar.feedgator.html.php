<?php

/**
* FeedGator - Aggregate RSS newsfeed content into a Joomla! database
* @version 3.0a1
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

class TOOLBAR_feedgator
{
	static function _EDIT()
	{
		$cid = JRequest::getVar('cid',array(),'get','array');
		$edit =  (int)@$cid[0];

		$text = ( $edit ? JText::_( 'Edit' ) : JText::_( 'New' ) );
		JToolBarHelper::title( JText::_( 'Feed' ).': [ '. $text.' ]', 'addedit.png' );

		if($edit) {
			$bar = JToolBar::getInstance('toolbar');
			$bar->appendButton( 'FGPreview' );
		}
		$edit ? JToolBarHelper::apply() : JToolBarHelper::apply( 'apply', JText::_( 'Save and Add Another' ));
		JToolBarHelper::save();
		$edit ? JToolBarHelper::cancel( 'cancel', 'Close' ) : JToolBarHelper::cancel();
		JToolBarHelper::help( 'screen.content.edit' );
		JToolBarHelper::custom( 'editdefault', 'edit.png', 'edit_f2.png', 'Edit Defaults', false );
		self::_CPanel();
	}

	static function _FEED_DEFAULT()
	{
		JToolBarHelper::title( JText::_( 'Edit Default Feed Settings' ) , 'addedit.png' );
		JToolBarHelper::apply('applyDefault');
		JToolBarHelper::save('saveDefault');
		JToolBarHelper::cancel( 'cancel', 'Close' );
		JToolBarHelper::help( 'screen.content.edit' );
		self::_CPanel();
	}

	static function _SETTINGS()
	{
		JToolBarHelper::title( JText::_( 'FeedGator Global Settings' ), 'config.png' );
		JToolBarHelper::apply('applySettings');
		JToolBarHelper::save('saveSettings');
		JToolBarHelper::cancel();
		JToolBarHelper::custom( 'editdefault', 'edit.png', 'edit_f2.png', 'Edit Defaults', false );
		self::_CPanel();
	}

	static function _PLUGINS()
	{
		JToolBarHelper::title( JText::_( 'FeedGator Plugins' ), 'plugin' );
		self::_CPanel();
	}

	static function _TOOLS()
	{
		JToolBarHelper::title( JText::_( 'FeedGator Tools' ), 'cpanel' );
		self::_CPanel();
	}

	static function _IMPORTS()
	{
		global $filter_state;

		JToolBarHelper::title( JText::_( 'FeedGator Import History' ), 'article' );
// The functions below cannot yet be supported for each content type and cause confusion for users
// The eventual aim will be to manipulate content from within FG

//		if ($filter_state == 'A' || $filter_state == NULL) {
//			JToolBarHelper::unarchiveList();
//		}
//		if ($filter_state != 'A') {
//			JToolBarHelper::archiveList();
//		}
//		JToolBarHelper::publishList();
//		JToolBarHelper::unpublishList();
//		JToolBarHelper::customX( 'movesect', 'move.png', 'move_f2.png', 'Move' );
//		JToolBarHelper::customX( 'copy', 'copy.png', 'copy_f2.png', 'Copy' );
//		JToolBarHelper::trash();
//		JToolBarHelper::editListX();
//		JToolBarHelper::addNewX();

		self::_CPanel();
	}

	static function _ABOUT()
	{
		JToolBarHelper::title( JText::_( 'About FeedGator' ), 'systeminfo.png' );
		self::_CPanel();
	}

	static function _SUPPORT()
	{
		JToolBarHelper::title( JText::_( 'FeedGator Help and Support' ), 'help_header.png' );
		self::_CPanel();
	}

	static function _FEEDS()
	{
		//fix for missing default style for refresh button
		$app = JFactory::getApplication();
		$templateDir = JURI::base() . 'templates/' . $app->getTemplate();
		$doc = JFactory::getDocument();
		$doc->addStyleDeclaration('.icon-32-refresh { background-image: url('.$templateDir.'/images/toolbar/icon-32-refresh.png); }');

		JToolBarHelper::title( JText::_( 'Manage RSS Feeds' ), 'article' );

		// button hack

		$bar = JToolBar::getInstance('toolbar');
		$bar->appendButton( 'FGPreview' );
		$bar->appendButton( 'FGImportAll' );
		$bar->appendButton( 'FGImport' );
		$bar->appendButton( 'Separator' );
		$bar->appendButton( 'Separator' );
		//

		JToolBarHelper::publishList('publish', 'Enable');
		JToolBarHelper::unpublishList('unpublish', 'Disable');
		JToolBarHelper::addNew();
		JToolBarHelper::custom( 'copy', 'copy.png', 'copy_f2.png', 'Copy', false );
		JToolBarHelper::editList();
		$bar->appendButton( 'FGDelete' );
	//	JToolBarHelper::deleteList();
		JToolBarHelper::custom( 'support', 'help.png', 'help_f2.png', 'Help', false );
		$bar->appendButton( 'Separator' );
		JToolBarHelper::custom( 'editdefault', 'edit.png', 'edit_f2.png', 'Edit Defaults', false );
		$bar->appendButton( 'Separator' );
		self::_CPanel();
	}

	static function _DEFAULT()
	{
		JToolBarHelper::title( JText::_( 'FeedGator the RSS Feed Import Component' ), 'config.png' );
	}

	static function _CPanel()
	{
		$bar = JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Link', 'options', 'Control Panel', 'index.php?option=com_feedgator&task=cpanel', true, false);
	}
}