<?php

defined('_JEXEC') or die ('Acesso restrito');

jimport('joomla.application.component.view');

class LojaViewCliente extends JViewLegacy {

	public function display($tpl = null) {

		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		if($this->getLayout() === 'visualizarcliente') {
			$cliente 		= $this->get('cliente');
			$listaebooks    = $this->get('ebooksBaixado');
			
			$this->cliente = $cliente;
			$this->listaebooks = $listaebooks;
			
			
			
		}
		
		parent::display($tpl);
		
	}
	
	protected function addToolBar() {
		JRequest::setVar('hidemainmenu', true);
		
		$isNew = ($this->item->id == 0);
		JToolBarHelper::title($isNew ? JText::_('Novo') : JText::_('Editar'), 'autor');
		JToolBarHelper::save('autor.save');
		JToolBarHelper::cancel('autor.cancel');
		
	}

}