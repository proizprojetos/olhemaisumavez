<?php
//defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class LojaViewPalestras extends JViewLegacy {
	
	protected $data;
	
	//Primeiro método a ser chamado ao iniciar o carregamento da pagina.
	function display($tpl = null) {
	
		$this->item		= $this->get('item');
		//print_r($this->item);
		//Pega os dados da sessao
		$this->comentarios		= $this->get('listacomentarios');
		$this->prepareDocument();	
		
		parent::display($tpl);
	}
	
	protected function prepareDocument() {
	
		$app	= JFactory::getApplication();
		
		$document = JFactory::getDocument();
		//Importa o arquivo css criado para as modificações do layout
		$document->addStyleSheet(JURI::root() . "/components/com_loja/assets/css/loja.css");
		$document->addStyleSheet(JURI::root() . "/components/com_loja/views/palestras/css/palestras.css");
		$document->addStyleSheet(JURI::root() . "/components/com_loja/views/palestras/css/liquid-slider.css");
		$document->addScript(JURI::root() . "components/com_loja/assets/js/jquery-ui1.10.2.js",$defer = false, $async = true);
		$document->addScript(JURI::root() . "components/com_loja/views/palestras/js/jquery.flip.min.js",$defer = false, $async = true);
		$document->addScript(JURI::root() . "components/com_loja/views/palestras/js/palestras.js",$defer = false, $async = true);
		
		//Slider
		//$document->addScript(JURI::root() . "components/com_loja/views/palestras/js/jquery.easing.1.3.js",$defer = false, $async = true);
		$document->addScript(JURI::root() . "components/com_loja/views/palestras/js/jquery.touchSwipe.min.js",$defer = false, $async = true);
		$document->addScript(JURI::root() . "components/com_loja/views/palestras/js/jquery.liquid-slider.min.js",$defer = false, $async = true);
		
	}
	
}

?>