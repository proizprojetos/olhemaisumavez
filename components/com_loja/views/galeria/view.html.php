<?php
//defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class LojaViewGaleria extends JViewLegacy {
	
	protected $data;
	
	//Primeiro método a ser chamado ao iniciar o carregamento da pagina.
	function display($tpl = null) {
	
		//Pega os dados da sessao
		$this->categorias 			= $this->get('listacategorias');
		$this->categoriasvideos 	= $this->get('listacategoriasvideos');
		
		$this->prepareDocument();	
		
		parent::display($tpl);
	}
	
	protected function prepareDocument() {
	
		$app	= JFactory::getApplication();
		
		$document = JFactory::getDocument();
		
		//Importa o arquivo css criado para as modificações do layout
		$document->addStyleSheet(JURI::root() . "/components/com_loja/assets/css/loja.css");
		$document->addStyleSheet(JURI::root() . "/components/com_loja/views/galeria/css/galeria.css");
		
		$document->addScript(JURI::root() . "components/com_loja/assets/js/jquery-ui1.10.2.js",$defer = false, $async = true);
		$document->addScript(JURI::root() . "components/com_loja/views/galeria/js/slider/jquery.jcarousel.min.js",$defer = false, $async = true);
		$document->addScript(JURI::root() . "components/com_loja/views/galeria/js/slider/jquery.galleriffic.js",$defer = false, $async = true);
		$document->addScript(JURI::root() . "components/com_loja/views/galeria/js/galeria.js",$defer = false, $async = true);
		
		
		/*$document->addStyleSheet(JURI::root() . "/components/com_loja/assets/css/loja.css");
		$document->addStyleSheet(JURI::root() . "/components/com_loja/views/oficinas/css/oficinas.css");
		$document->addScript(JURI::root() . "components/com_loja/assets/js/jquery-ui1.10.2.js",$defer = false, $async = true);
		$document->addScript(JURI::root() . "components/com_loja/views/oficinas/js/oficinas.js",$defer = false, $async = true);
		//$document->addScript(JURI::root() . "components/com_loja/views/cadastro/js/cadastro.js",$defer = false, $async = true);
		 */
	}
	
}

?>