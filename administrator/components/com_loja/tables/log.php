<?php 

defined('_JEXEC') or die;

jimport('joomla.database.table');

class LojaTableLog extends JTable {
	  
	function __construct($db) {
		parent::__construct('#__loja_logs','id',$db);
	}
	
} 