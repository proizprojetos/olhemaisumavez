<?php

defined('_JEXEC') or die ('Acesso restrito');

jimport('joomla.application.component.view');

class LojaViewDashboard extends JViewLegacy {

	public function display($tpl = null) {
	
		$this->setDocument();	
		
		parent::display($tpl);
		
	}
	
	protected function setDocument() 
	{
		//$isNew = ($this->item->id < 1);
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_LOJA_PEDIDO_DETALHE'));
		
		//Adiciona o CSS
		//$document->addStyleSheet(JURI::root() . "/administrator/components/com_popstil/assets/css/pedidos.css");
		$document->addScript(JURI::root() . 'administrator/components/com_loja/libs/jquery/jquery.min.1.7.1.js');
		//$document->addScript(JURI::root() . 'administrator/components/com_loja/libs/highcharts/highcharts.js');
		
	}

}