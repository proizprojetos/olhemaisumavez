<?php

defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT.'/controller.php';

class LojaControllerLivros extends LojaController {
		
	public function adicionarlivro() {
	
		$app = JFactory::getApplication();
		
		$livro = JRequest::getVar('livro', array(), 'post', 'array');		
		
		$app->setUserState('com_popstil.carrinho.valorFrete', '');
		//print_r($livro);
		
		//Apagar o cookie
		//setcookie('carrinho', "", time() - 3600);
		$carrinho = unserialize($_COOKIE['carrinho']);
		//Pega o carrinho do cookie do navegador
		if(isset($carrinho) && !empty($carrinho)) {
			//Existe carrinho no cookie;
			//echo 'entrou no carrinho';
			
			print_r(($carrinho));
			$achou = false;
			//Verifica se o carrinho já possui o livro e caso sim, adiciona mais um a quantidade.
			foreach ($carrinho as $key => &$value) {
				if($value['id'] == $livro['id']) {
					
					//Adiciona mais 1 ao carrinho.
					$value['quantidade'] += 1;
					$achou = true;
					break;	
				}			
			}
			if(!$achou) {
				$adicionar['id'] = $livro['id'];
				$adicionar['quantidade'] = 1;
				
				array_push($carrinho, $adicionar);
			}
			$carrinho = serialize($carrinho);			
			setcookie('carrinho', $carrinho, time() + 60*60*24*7);				
			
		}else {
			echo 'nao entrou no carrinho';
			//Não existe carrinho no cookie;
			//Coloca o livro no item 0 do carrinho
			$c[0]['id'] = $livro['id'];
			$c[0]['quantidade'] = 1;
			$c = serialize($c);
			setcookie('carrinho', $c, time() + 60*60*24*7);
		}
		
		$this->setRedirect(JRoute::_('index.php?option=com_loja&view=carrinho', false));
	}
	
	public function baixarLivroGratis() {
		
		JSession::checkToken() or $this->setRedirect(JRoute::_('index.php?option=com_loja&view=livros', false));
		
		$livro = JRequest::getVar('livro', array(), 'post', 'array');
		
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		//Caso o usuario não esteja logado ele redireciona para a tela de login
		if(!$user->id) {
			$app->setUserState('com_loja.pedidoandamento', '1');
			$this->setMessage('É necessario estar logado para baixar o livro!');
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));
		}else {
			
			$model = $this->getModel('livros');
			
			$dados['id_user']	= $user->id;
			$dados['id_ebook']		= $livro['id'];

			$a = $model->gravaLivroBaixado($dados);
			if($a === false) {
				$this->setMessage($model->getError(), 'warning');
				$this->setRedirect(JRoute::_('index.php?option=com_loja&view=livros', false));
				return false;
			}
			$l = $model->getLivro($livro['id']);
			
			$filename =$l->url_link_download;
			header("Cache-Control: public");
			header("Content-Description: File Transfer");
			header('Content-disposition: attachment; filename='.basename($filename));
			header("Content-Type: application/pdf");
			header("Content-Transfer-Encoding: binary");
			header('Content-Length: '. filesize($filename));
			readfile($filename);
			exit;
		}
	}

}
