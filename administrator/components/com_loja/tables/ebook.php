<?php 

defined('_JEXEC') or die;

jimport('joomla.database.table');

class LojaTableEbook extends JTable {
	
	function __construct($db) {
		parent::__construct('#__loja_ebooks','id',$db);
	}
	
} 