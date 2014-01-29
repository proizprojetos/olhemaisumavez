<?php 

defined('_JEXEC') or die;

jimport('joomla.database.table');

class LojaTableGaleriacategoria extends JTable {
	
	function __construct($db) {
		parent::__construct('#__loja_galeria_categoria','id',$db);
	}
	
} 