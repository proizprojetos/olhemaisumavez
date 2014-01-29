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

/**
 * Renders the base url
 *
 * @package 	Joomla.Framework
 * @subpackage	Parameter
 * @since		1.5
 */

class JFormFieldFGBase extends JFormField
{
	protected	$type = 'FGBase';

	public function getInput() {
		$base = substr(JURI::base(),0,strpos(JURI::base(),'administrator/'));

		return '<input type="text" name="'.$this->name.'" id="'.$this->name.'" value="'.$base.'" class="text_area" size="50">';
	}
}