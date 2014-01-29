<?php

defined('_JEXEC') or die('Acesso restrito');

jimport('joomla.application.component.modellist');

class LojaModelGaleriacategorias extends JModelList {


	/**
	* Método para construir um query SQL para carregar a lista de dados
	*/
	protected function getListQuery() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		//Seleciona os campos desejados
		$query->select('p.*');
		
		//Da tabela
		$query->from('#__loja_galeria_categoria p');
		/*$query->order($db->escape($this->getState('list.ordering', 'p.nomecompleto')).' '.
		                $db->escape($this->getState('list.direction', 'ASC')));
		*/
		return $query;
	}
	
	protected function populateState($ordering = null, $direction = null) {
	        parent::populateState('p.nome', 'ASC');
	}
	
	public function __construct($config = array())
	{   
	        $config['filter_fields'] = array(
	                'p.id',
	                'p.nome'
	        );
	        parent::__construct($config);
	}

}