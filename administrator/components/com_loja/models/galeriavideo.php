<?php 

defined('_JEXEC') or die('Acesos restrito');

jimport('joomla.application.component.modeladmin');

class LojaModelGaleriavideo extends JModelAdmin {
	
	public function getTable($type="Galeriavideo", $prefix="LojaTable", $config=array() ) {
		return JTable::getInstance($type, $prefix, $config);	
	}
	
	public function getForm($data = array(), $loadData = true) {
	
		$form = $this->loadForm('com_loja.galeriavideo', 'galeriavideo', array('control' => 'jform', 'load_data' =>$loadData));
		
		if(empty($form)) {
			return false;
		}
		return $form;		
	}
	
	/**
	*	Método responsavel por pegas os dados que serão injetados no formulario
	*/
	protected function loadFormData() 
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_loja.edit.galeriavideo.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
		}
		return $data;
	}
	
}