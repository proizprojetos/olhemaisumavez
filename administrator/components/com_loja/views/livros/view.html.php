<?php

defined('_JEXEC') or die ('Acesso restrito');

jimport('joomla.application.component.view');

class LojaViewLivros extends JViewLegacy {

	public function display($tpl = null) {
		
		$itens 			= $this->get('Items');
		$pagination 	= $this->get('Pagination');
		$state	= $this->get('State');

		$this->sortDirection = $state->get('list.direction');
		$this->sortColumn = $state->get('list.ordering');
		
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		$this->itens = $itens;
		$this->pagination = $pagination;
		
		$this->setDocument();
		
		$this->addToolBar();
		
		parent::display($tpl);
		
	}
	
	protected function setDocument() 
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('Gerenciar Livros'));
		
	}
	
	protected function addToolBar() 
	{
		JToolBarHelper::title('Gerenciar Livros');
		JToolBarHelper::deleteList('', 'livros.delete');
		JToolBarHelper::editList('livro.edit');
		JToolBarHelper::addNew('livro.add');
	}

}