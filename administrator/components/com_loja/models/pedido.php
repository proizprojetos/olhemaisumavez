<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

/**
 * HelloWorld Model
 */
class LojaModelPedido extends JModelAdmin
{	

	public function getTable($type = 'Pedido', $prefix = 'LojaTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getForm($data = array(), $loadData = true) 
	{
		// Get the form.
		$form = $this->loadForm('com_loja.pedido', 'loja', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) 
		{
			return false;
		}
		return $form;
	}
	
	public function getpedido() {
		
		//Pega o id do pedido passado por parametro
		$idpedido 	= JRequest::getVar('idpedido');
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		//Seleciona os campos
		$query->select('p.*, cliente.*');
		$query->from('#__loja_pedidos p');
		$query->join('INNER', '#__loja_clientes cliente ON cliente.id = p.id_cliente');
		$query->where('p.id = '.$idpedido);
		$db->setQuery((String) $query);
		$pedido = $db->loadObject();
		
		return $pedido;
	}
	
	public function getitenspedido() {
		
		$idpedido 	= JRequest::getVar('idpedido');
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		//Seleciona os campos
		$query->select('item.*');
		$query->from('#__loja_itens_pedido item');
		$query->where('item.id_pedido = '.$idpedido);
		$db->setQuery((String) $query);
		$itenspedido = $db->loadObjectList();
		
		return $itenspedido;
	}

}