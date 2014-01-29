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

class JFormFieldFGAuthors extends JFormFieldList
{
	protected	$type = 'FGAuthors';

	public function getInput() {
		$db		= JFactory::getDbo();
		$cid 	= JFactory::getApplication()->input->get('cid',array(),'ARRAY');
		$cid 	= (empty($cid) ? 0 : (int)$cid[0]);

		if (!$this->class) {
			$this->class = "inputbox";
		}

		// get list of Authors for dropdown filter
		$query = 'SELECT u.id AS id, u.name AS text' .
				' FROM #__users AS u' .
				' INNER JOIN #__user_usergroup_map AS um ON um.user_id = u.id' .
				' WHERE u.block = 0' .
				' AND um.group_id != 2' .  // above registered
				' GROUP BY u.name' .
				' ORDER BY u.name';
		$db->setQuery($query);
		$authors[] = JHTML::_('select.option', '', ( ($cid == -2) ? JText::_('Select Author') : JText::_('Use Default') ), 'id', 'text');
		$authors = array_merge($authors, $db->loadObjectList());

		$javascript = '';

		//return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', $attributes, 'value', 'text', $value, $control_name.$name);
		//return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']'.$multipleArray, $attributes, $key, $val, $value, $control_name.$name);
		return JHTML::_('select.genericlist',  $authors, $this->name, 'class="'.$this->class.'"'.$javascript, 'id', 'text', $this->value, $this->name);
	}
}