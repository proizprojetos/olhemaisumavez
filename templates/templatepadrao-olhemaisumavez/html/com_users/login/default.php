<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

if ($this->user->get('guest')):
	// The user is not logged in.
	echo $this->loadTemplate('head');
	echo $this->loadTemplate('login');
else:
	// The user is already logged in.
	//echo $this->loadTemplate('logout');
	header( 'Location: ' . 'index.php?option=com_loja&view=painelcontrole' );
endif;
