<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/
namespace GCore\Libs;
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
class Env {

	public static function redirect($url){
		header('Location: '.str_replace('&amp;', '&', $url));
		ob_end_flush();
		exit();
	}
	
	public static function e404(){
		header('HTTP/1.0 404 Not Found');
	}
}