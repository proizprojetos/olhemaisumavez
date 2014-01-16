<?php

defined('_JEXEC') or die ('Acesso restrito');

jimport('joomla.application.component.view');

class LojaViewLivro extends JViewLegacy {

	public function display($tpl = null) {
		
		$form = $this->get('Form');
		$item = $this->get('Item');
		
		$autores = $this->get('listaAutores');
		
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		$this->form = $form;
		$this->item = $item;
		
		$this->autores = $autores;
		
		$this->addToolBar();
		
		parent::display($tpl);
		
	}
	
	protected function addToolBar() {
		JRequest::setVar('hidemainmenu', true);
		
		$isNew = ($this->item->id == 0);
		JToolBarHelper::title($isNew ? JText::_('Novo') : JText::_('Editar'), 'livro');
		JToolBarHelper::save('livro.save');
		JToolBarHelper::cancel('livro.cancel');
		
	}

}