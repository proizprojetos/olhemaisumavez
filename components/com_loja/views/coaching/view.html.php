<?php
//defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class LojaViewCoaching extends JViewLegacy {
	
	protected $data;
	
	//Primeiro método a ser chamado ao iniciar o carregamento da pagina.
	function display($tpl = null) {
	
		//Pega os dados da sessao
		$this->item		= $this->get('item');
		
		$this->prepareDocument();	
		
		parent::display($tpl);
	}
	
	protected function prepareDocument() {
	
		$app	= JFactory::getApplication();
		
		$document = JFactory::getDocument();
		//Importa o arquivo css criado para as modificações do layout
		$document->addStyleSheet(JURI::root() . "/components/com_loja/assets/css/loja.css");
		$document->addStyleSheet(JURI::root() . "/components/com_loja/views/coaching/css/coaching.css");
		$document->addScript(JURI::root() . "components/com_loja/assets/js/jquery-ui1.10.2.js",$defer = false, $async = true);
		$document->addScript(JURI::root() . "components/com_loja/views/coaching/js/coaching.js",$defer = false, $async = true);
	}
	
}

?>