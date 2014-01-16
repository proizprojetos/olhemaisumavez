<?php 

defined('_JEXEC') or die;

jimport('joomla.database.table');

class LojaTableEditora extends JTable {
	
	function __construct($db) {
		parent::__construct('#__loja_editoras','id',$db);
	}
	
} 