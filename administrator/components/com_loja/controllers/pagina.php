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
		
		$dados = JRequest::getVar('data', array(), 'post', 'array');
		
		$i = 1;
		print_r(($_FILES['imagens']));
		foreach ($_FILES['imagens']['name'] as $key => $value) {
			//echo 'entrou'.$key.'<br/>';
			//print_r($value);
			if(!empty($value)) {
				$extensao 		= pathinfo($_FILES['imagens']['name']['imagem'.$i.'_maisinformacoes'], PATHINFO_EXTENSION);
				$nomearquivo 	= 'Imagem_palestrante_'.$i.'.'.$extensao; 
				$caminho 		= JPATH_SITE.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$nomearquivo;
				$arquivo		= $_FILES['imagens']['tmp_name']['imagem'.$i.'_maisinformacoes'];
				$imagens['imagem_'.$i] = $caminho;
				if(!JFile::upload($arquivo, $caminho)) 
				{
						print_r($_FILES['imagens']['error']);
						return;
				}
				$i= $i+1;
			}
		}
		//print_r($imagens);
		
		//print_r($imagens);
		//echo '<br/><br/>';
		$dados['imagens_maisinformacoes'] = json_encode($imagens);
		//print_r($dados);
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
