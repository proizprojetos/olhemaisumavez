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
 * Renders a select list of authors
 *
 * @package 	Joomla.Framework
 * @subpackage	Parameter
 * @since		1.5
 */

class JFormFieldFGAccess extends JFormFieldList
{
	protected	$type = 'FGAccess';

	public function getInput() {
		$db		= JFactory::getDBO();
		$cid 	= JFactory::getApplication()->input->get('cid',array(),'ARRAY');
		$cid 	= (empty($cid) ? 0 : (int)$cid[0]);

		if (!$this->class) {
			$this->class = "inputbox";
		}

		// get list of Authors for dropdown filter
		$query	= 'SELECT a.id AS value, a.title AS text'
				. ' FROM #__viewlevels AS a'
				. ' GROUP BY a.id'
				. ' ORDER BY a.ordering ASC, `title` ASC';
		$db->setQuery( $query );
		$groups[] = JHTML::_('select.option', '', ( ($cid == -2) ? JText::_('Select Group') : JText::_('Use Default') ), 'value', 'text');
		$groups = array_merge($groups, $db->loadObjectList());

		$javascript = '';

		//return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', $attributes, 'value', 'text', $value, $control_name.$name);
		//return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']'.$multipleArray, $attributes, $key, $val, $value, $control_name.$name);
		return JHTML::_('select.genericlist',  $groups, $this->name, 'class="'.$this->class.'"'.$javascript, 'value', 'text', $this->value, $this->name);
	}
}