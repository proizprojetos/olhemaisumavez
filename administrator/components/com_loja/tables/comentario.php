<?php 

defined('_JEXEC') or die;

jimport('joomla.database.table');

class LojaTableComentario extends JTable {
	
	function __construct($db) {
		parent::__construct('#__loja_comentarios','id',$db);
	}
	
} 