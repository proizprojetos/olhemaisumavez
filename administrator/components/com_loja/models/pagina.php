<?php 

defined('_JEXEC') or die('Acesos restrito');

jimport('joomla.application.component.modeladmin');

class LojaModelPagina extends JModelAdmin {

	protected $data;
	
	public function getTable($type="Pagina", $prefix="LojaTable", $config=array() ) {
		return JTable::getInstance($type, $prefix, $config);	
	}
	
	public function getForm($data = array(), $loadData = true) {
	
		$form = $this->loadForm('com_loja.pagina', 'pagina', array('control' => 'jform', 'load_data' =>$loadData));
		
		if(empty($form)) {
			return false;
		}
		return $form;		
	}
	
	/**
	*	Método responsavel por pegas os dados que serão injetados no formulario
	
	protected function loadFormData() 
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_loja.edit.pagina.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
		}
		return $data;
	}
	*/
	public function salvar($data,$pagina,$pagina_ref)
	{
		
		$db = JFactory::getDbo();
		
		/*$query = $db->getQuery(true);
		
		$fields = array(
			$db->quoteName('atributos').json_encode($data)
		);
		
		$conditions = array (
			$db->quoteName('cod_pagina') . '=\''.$pagina.'\''
		);
		
		$query->update($db->quoteName('#__loja_paginas'))->set($fields)->where($conditions);*/
		
		$att = '';
		
		$numItems = count($data);
		$i = 0;
		foreach ($data as $key => $value) {
			$att.= $key.' =\''.$value.'\' ';
			if(++$i !== $numItems) {
			    $att.= ',';
			}
		}
		
		$query = "UPDATE #__loja_pagina_".$pagina." 
				 	set ".$att."
				 	WHERE pagina_ref = '".$pagina_ref."'
		";
		
		
		$db->setQuery($query);
		
		$result = $db->query();
		
	}
	
	
	
	public function getItemCoaching() {
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		//Seleciona os campos
		$query->select('p.*');
		$query->from('#__loja_pagina_coaching p');
		$query->where('p.pagina_ref = \'COA\'');
		$db->setQuery((String) $query);
		$pagina = $db->loadObject();
		
		return $pagina;
	}
	
	public function getItemPalestras() {
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		//Seleciona os campos
		$query->select('p.*');
		$query->from('#__loja_pagina_palestras p');
		$query->where('p.pagina_ref = \'PAL\'');
		$db->setQuery((String) $query);
		$pagina = $db->loadObject();
		
		return $pagina;
	}
	
}