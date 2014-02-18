<?php

/**
* @Copyright Copyright (C) 2012 - JoniJnm.es
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');

require_once(dirname(__FILE__).'/helper.php');

$item = modSlideromuz::getArtigos($params);

require(JModuleHelper::getLayoutPath('mod_slideromuz'));
