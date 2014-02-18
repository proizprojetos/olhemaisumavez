<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controllerform');


class LojaControllerPagina extends JControllerForm
{
	
	public function cancelar() {
		$this->setRedirect(JRoute::_('index.php?option=com_loja&view=pagina&layout=coaching', false));
		$this->setMessage('As alterações foram canceladas.', 'warning');
	}
	
	public function salvarCoaching() {
		
		$dados = JRequest::getVar('data', array(), 'post', 'array');
		
		$model = $this->getModel('pagina', 'LojaModel');
		
		$returno = $model->salvar($dados,'coaching','COA');
		
		if($retorno == '') {
			$this->setRedirect(JRoute::_('index.php?option=com_loja&view=pagina&layout=coaching', false));
			$this->setMessage('As alterações foram realizadas com sucesso.', 'warning');
		}else {
			$this->setRedirect(JRoute::_('index.php?option=com_loja&view=pagina&layout=coaching', false));
			$this->setMessage('Ouve um erro ao realizar as alterações, tente novamente.', 'warning');
		}
	}
	
	public function salvarPalestras() {
		
		$dados = JRequest::getVar('jform', array(), 'post', 'array');
		
		$model = $this->getModel('pagina', 'LojaModel');
		
		$retorno = $model->salvar($dados,'palestras','PAL');
		
		if($retorno == '') {
			$this->setRedirect(JRoute::_('index.php?option=com_loja&view=pagina&layout=palestras', false));
			$this->setMessage('As alterações foram realizadas com sucesso.', 'warning');
		}else {
			$this->setRedirect(JRoute::_('index.php?option=com_loja&view=pagina&layout=palestras', false));
			$this->setMessage('Ouve um erro ao realizar as alterações, tente novamente.', 'warning');
		}
	}	
}
