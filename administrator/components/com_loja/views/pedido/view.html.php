<?php

defined('_JEXEC') or die ('Acesso restrito');

jimport('joomla.application.component.view');

class LojaViewPedido extends JViewLegacy {

	public function display($tpl = null) {
		
		$this->pedido 		= $this->get('pedido');
		$this->itenspedido 	= $this->get('itenspedido');
		
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		$this->setDocument();
		
		parent::display($tpl);
		
	}
	
	protected function setDocument() 
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('Vizualizar Pedido'));
		
	}

}