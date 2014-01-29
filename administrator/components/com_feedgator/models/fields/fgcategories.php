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

// Check to ensure this file is within the rest of the framework
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Renders a select list of content_type/sects/cats
 *
 * @package 	Joomla.Framework
 * @subpackage	Parameter
 * @since		1.5
 */

class JFormFieldFGCategories extends JFormFieldList
{
	protected	$type = 'FGCategories';

	public function getInput() {
		$app			= JFactory::getApplication();
		$user			= JFactory::getUser();
		$db				= JFactory::getDBO();
		$feedModel		= FGFactory::getFeedModel();
		$task			= $app->input->get('task','','WORD');
		$sectionid		= 0;
		$cid 			= $app->input->get('cid',array(),'ARRAY');
		$cid 			= (empty($cid) ? 0 : (int)$cid[0]);
		$default		= ($cid == -2);

		//node attributes var -> category/section
		$type = $this->element['var'];
		if (!$this->class) {
			$this->class = "inputbox";
		}

		$fgParams = $feedModel->getParams();
		$fgdefParams = $feedModel->getDefaultParams(true);
		if(!$fgParams->getValue('content_type')) $fgParams->setValue('content_type',null,$fgdefParams->getValue('content_type',null,'com_content'));

		$plugin = $feedModel->getPlugin(); // use feed model to allow access to feed data/params

		if($type == 'section') {
			$javascript = ' onchange="changeDynaList( \'datacatid\', sectioncategories, document.adminForm.datasectionid.options[document.adminForm.datasectionid.selectedIndex].value, 0, 0);"';
			$title = $default ? '- '.JText::_('Select Section').' -' : JText::_('Use Default') ;
			$options = ($cid OR $fgParams->getValue('default_type')) ? $plugin->getSectionList($fgParams,$default) : array(JHTML::_('select.option', '', $title, 'id', 'title'));
		} elseif($type == 'category') {
			$javascript = '';
			$title = ($cid == -2) ? '- '.JText::_('Select Category').' -' : JText::_('Use Default') ;
			$options = ($cid OR $fgParams->getValue('default_type')) ? $plugin->getCategoryList($fgParams,$default) : array(JHTML::_('select.option', '', $title, 'id', 'title'));
		}

		//return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', $attributes, 'value', 'text', $value, $control_name.$name);
		//return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']'.$multipleArray, $attributes, $key, $val, $value, $control_name.$name);
		return JHTML::_('select.genericlist',  $options, $this->name, 'class="'.$this->class.'"'.$javascript, 'id', 'title', $this->value, $this->name);
	}
}