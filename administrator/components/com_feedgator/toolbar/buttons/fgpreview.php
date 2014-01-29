<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  Toolbar
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Renders a link button
 *
 * @package     Joomla.Libraries
 * @subpackage  Toolbar
 * @since       3.0
 */
class JToolbarButtonFGPreview extends JToolbarButton
{
	/**
	 * Button type
	 * @var    string
	 */
	protected $_name = 'FGPreview';

	/**
	 * Fetch the HTML for the button
	 *
	 * @param   string  $type  Unused string.
	 * @param   string  $name  Name to be used as apart of the id
	 * @param   string  $text  Button text
	 * @param   string  $url   The link url
	 *
	 * @return  string  HTML string for the button
	 *
	 * @since   3.0
	 */
	public function fetchButton($type = 'FGPreview', $name = 'preview', $text = 'Preview', $url = '#')
	{
		$text = JText::_($text);
		$class = $this->fetchIconClass($name);
		$doTask = $url;

		$html = "<button class=\"btn btn-small\" onclick=\"javascript: location.href='$doTask'; importFunc('preview'); \">\n";
		$html .= "<span class=\"icon-eye\">\n";
		$html .= "</span>\n";
		$html .= "$text\n";
		$html .= "</button>\n";

		return $html;
	}

	/**
	 * Get the button CSS Id
	 *
	 * @param   string  $type  The button type.
	 * @param   string  $name  The name of the button.
	 *
	 * @return  string  Button CSS Id
	 *
	 * @since   3.0
	 */
	public function fetchId($type = 'FGPreview', $name = '')
	{
		return $this->_parent->getName() . '-' . $name;
	}
}
