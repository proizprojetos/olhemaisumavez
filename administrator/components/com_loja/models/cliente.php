<?php 

defined('_JEXEC') or die('Acesos restrito');

jimport('joomla.application.component.modeladmin');

class LojaModelCliente extends JModelAdmin {
	
	public function getTable($type="Cliente", $prefix="LojaTable", $config=array() ) {
		return JTable::getInstance($type, $prefix, $config);	
	}
	
	public function getForm($data = array(), $loadData = true) {
	
		$form = $this->loadForm('com_loja.cliente', 'cliente', array('control' => 'jform', 'load_data' =>$loadData));
		
		if(empty($form)) {
			return false;
		}
		return $form;		
	}
	
	public function getcliente() {
			
		//Pega o id do pedido passado por parametro
		$id 	= JRequest::getVar('id');
				
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		//Seleciona os campos
		$query->select(' cliente.*');
		$query->from('#__loja_clientes cliente');
		//$query->join('INNER', '#__popstil_enderecos end ON end.popstil_usuario_idusuario = pessoa.id');
		$query->where('cliente.id = '.$id);
		$db->setQuery((String) $query);
		$cliente = $db->loadObject();
	
		return $cliente;
	}
	
	public function getEbooksBaixado() {
		/*select ebook.titulo, count(d.id_cliente) as 'quantidade'  
		from omuz_loja_ebooks_clientes_downloads d
		left join omuz_loja_clientes cliente on d.id_cliente = cliente.id 
		inner join omuz_loja_ebooks ebook on ebook.id = d.id_ebook
		where cliente.id = 6
		group by d.id_cliente, d.id_ebook*/
		
		$id 	= JRequest::getVar('id');
				
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		//Seleciona os campos
		$query->select(' ebook.titulo, count(d.id_cliente) as "quantidade" ');
		$query->from('#__loja_ebooks_clientes_downloads d');
		$query->join('left', '#__loja_clientes cliente on d.id_cliente = cliente.id ');
		$query->join('INNER', '#__loja_ebooks ebook on ebook.id = d.id_ebook');
		$query->where('cliente.id = '.$id);
		$query->group('d.id_cliente, d.id_ebook');
		$db->setQuery((String) $query);
		$ebooks = $db->loadObjectList();
		
		return $ebooks;
	}
	
	/**
	*	Método responsavel por pegas os dados que serão injetados no formulario
	*/
	protected function loadFormData() 
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_loja.edit.cliente.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
		}
		return $data;
	}
	
}