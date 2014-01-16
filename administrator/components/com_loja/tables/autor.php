<?php 

defined('_JEXEC') or die;

jimport('joomla.database.table');

class LojaTableAutor extends JTable {
	
	function __construct($db) {
		parent::__construct('#__loja_autores','id',$db);
	}
	
} 