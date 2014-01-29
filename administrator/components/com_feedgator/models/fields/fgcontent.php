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

/// Check to ensure this file is within the rest of the framework
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

class JFormFieldFGContent extends JFormFieldList
{
	protected	$type = 'FGContent';

	public function getInput() {
		$user			= JFactory::getUser();
		$db				= JFactory::getDBO();
		$feedModel		= FGFactory::getFeedModel();
		$pluginModel	= FGFactory::getPluginModel();
		$task			= JFactory::getApplication()->input->get('task','','WORD');
		$cid 			= JFactory::getApplication()->input->get('cid',array(),'ARRAY');
		$cid 			= (empty($cid) ? 0 : (int)$cid[0]);

		//node attributes var -> dyna
		$type = $this->element['var'];
		if (!$this->class) {
			$this->class = "inputbox";
		}

		$fgParams = $feedModel->getParams();
		if(!$this->value) $this->value = $fgParams->getValue('default_type');

		$plugins = $pluginModel->loadInstalledPlugins();

		$options = array();
// needs fail safe to make sure a default is loaded or the whole thing breaks!
//		if($cid != -2) {
//			$options[] = JHTML::_('select.option', '', JText::_('Use Default'), 'id', 'title');
//		} else {
			$options[] = JHTML::_('select.option', -1, $type ? '- '.JText::_('FG_CHOOSE_CONTENT').' -' : '- '.JText::_('FG_DEFAULT_CONTENT').' -', 'id', 'title');
//		}
		foreach($plugins as $plugin) {
			if($plugin->published) {
				$options[] = JHTML::_('select.option', $plugin->extension, $plugin->name, 'id', 'title');
			}
		}

		$type ? $javascript = ' onchange="changeDynaList( \'paramssectionid\', contentsections, document.adminForm.paramscontent_type.options[document.adminForm.paramscontent_type.selectedIndex].value, 0, 0); changeDynaList( \'paramscatid\', sectioncategories, document.adminForm.paramssectionid.options[document.adminForm.paramssectionid.selectedIndex].value, 0, 0);"' : $javascript = '';

		//return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', $attributes, 'value', 'text', $value, $control_name.$name);
		//return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']'.$multipleArray, $attributes, $key, $val, $value, $control_name.$name);
		return JHTML::_('select.genericlist',  $options, $this->name, 'class="'.$this->class.'"'.$javascript, 'id', 'title', $this->value, $this->name);
	}
}