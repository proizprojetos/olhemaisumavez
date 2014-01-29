<?php

defined('_JEXEC') or die('Restricted access');

//jimport('joomla.application.component.modelitem');
jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_loja'.DS.'tables');


class LojaModelCoaching extends JModelForm {
	
	//protected $msg;
	protected $data;
	
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_loja.coaching', 'coaching', array('control' => 'coaching', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}
	
	
	public function getItem() {
		
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
	
	public function getListacomentarios() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$date = JFactory::getDate();
		
		$nowDate = $db->quote($date->toSql());
		
		$query->select('comentario.*')
		->from('#__loja_comentarios comentario')
			->where('comentario.pagina_ref = \'C\'');
		$db->setQuery((String) $query);
		$comentarios = $db->loadObjectList();
		
		return $comentarios;
	}
}