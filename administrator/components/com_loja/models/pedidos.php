<?php 

defined('_JEXEC') or die ('Acesso restrito');

jimport('joomla.application.component.modelist');

class LojaModelPedidos extends JModelList {
	
	protected function getListQuery() {
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		/*
		$query->select('p.*');
		$query->from('#__popstil_pedidos p, #__popstil_usuarios user ');
		$query->join('INNER', '#__users u on u.id = user.idusuario_joomla ');
		*/
		
		//Seleciona os campos
		$query->select('p.*, cliente.nome_completo');
		$query->from('#__loja_pedidos p ');
		$query->join('INNER', '#__loja_clientes cliente on cliente.id = p.id_cliente');
		$query->order($db->escape($this->getState('list.ordering', 'p.data_criacao')).' '.
		                $db->escape($this->getState('list.direction', 'ASC')));
		                             
		return $query;
	}
	
	protected function populateState($ordering = null, $direction = null) {
	        parent::populateState('p.data_criacao', 'ASC');
	}
	
	public function __construct($config = array())
	{   
	        $config['filter_fields'] = array(
	                'p.id',
	                'p.data_criacao',
	                'p.status',
	                'p.valor_pedido',
	                'p.valor_itens',
	                'p.id_cliente'
	        );
	        parent::__construct($config);
	}
}