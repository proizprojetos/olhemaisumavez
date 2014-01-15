<?php 

defined('_JEXEC') or die;

jimport('joomla.database.table');

class LojaTableLivro extends JTable {
	
	function __construct($db) {
		parent::__construct('#__loja_livros','id',$db);
	}
	
} 