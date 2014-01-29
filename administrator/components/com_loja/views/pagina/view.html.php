<?php

defined('_JEXEC') or die ('Acesso restrito');

jimport('joomla.application.component.view');

class LojaViewPagina extends JViewLegacy {

	public function display($tpl = null) {
		
		$form = $this->get('Form');
		//print_r($form);
		
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		if($this->getLayout() === 'coaching') {
			$item = $this->get('ItemCoaching');
			$this->item = $item;			
		}
		
		if($this->getLayout() === 'palestras') {
			$item = $this->get('ItemPalestras');
			$this->item = $item;		
		}
		
		$this->form = $form;
		//$this->item = $item;
		//echo 'eee:'.$this->item.'<br/>';
		
		$this->addToolBar();
		
		$this->setDocument();
		
		parent::display($tpl);
		
	}
	protected function setDocument() 
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('Gerenciar Página de Coaching'));
		
	}
	
	protected function addToolBar() {
		//JRequest::setVar('hidemainmenu', true);
		if($this->getLayout() === 'coaching') {
			JToolBarHelper::custom('pagina.salvarCoaching','save','save','Salvar alterações', false);
			JToolBarHelper::custom('pagina.cancelar', 'cancel','cancel','Cancelar',false);
		}
		
		if($this->getLayout() === 'palestras') {
			JToolBarHelper::custom('pagina.salvarPalestras','save','save','Salvar alterações', false);
			JToolBarHelper::custom('pagina.cancelar', 'cancel','cancel','Cancelar',false);
		}
		//$isNew = ($this->item->id == 0);
		//JToolBarHelper::title($isNew ? JText::_('Novo') : JText::_('Editar'), 'comentario');
		//JToolBarHelper::save('pagina.save');
		//JToolBarHelper::cancel('comentario.cancel');
		
	}

}