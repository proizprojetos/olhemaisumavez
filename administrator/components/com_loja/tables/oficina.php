<?php 

defined('_JEXEC') or die;

jimport('joomla.database.table');

class LojaTableOficina extends JTable {
	
	function __construct($db) {
		parent::__construct('#__loja_oficinas','id',$db);
	}
	
} 