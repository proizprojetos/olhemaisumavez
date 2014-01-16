<?php

defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT.'/controller.php';
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_loja'.DS.'PagSeguroLibrary'.DS.'PagSeguroLibrary.php');

class LojaControllerPainelControle extends LojaController {
	
	
		
	public function editar() {
		
		JSession::checkToken() or $this->setRedirect(JRoute::_('index.php?option=com_loja&view=painelcontrole&layout=editar', false));
		
		$user = JFactory::getUser();
		
		$app	= JFactory::getApplication();
		$model	= $this->getModel('painelcontrole', 'LojaModel');
		
		//Pega os dados da tela digitados pelo usuario.
		$requestData = $this->input->post->get('cadastro', array(), 'array');
		$idcliente	 = $requestData['id'];
		$dados = new stdClass();
		foreach ($requestData as $k => $v)
		{
			$dados->$k = $v;
		}
		
		$dados->id_cliente = $idcliente;
		//Verifica se as senhas digitadas são iguais
		//JError::raiseError(500, 'Senhas devem ser identicas');
		
		//Coloca o array com as informações digitadas no sessao.
		$app->setUserState('com_loja.painelcontrole.editar', $requestData);
		
		if($dados->senha != $dados->senha2) {
			$this->setMessage(JText::_('Senhas devem ser idênticas'));
			$this->setRedirect(JRoute::_('index.php?option=com_loja&view=painelcontrole&layout=editar', false));
			return false;
		}
		
		$retorno = $model->editar($dados);
				
		if($retorno === false) {

			// Redirect back to the edit screen.
			$this->setMessage($model->getError(), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_loja&view=painelcontrole&layout=editar', false));
			return false;
		}
		
		//Limpa os dados da sessao
		$app->setUserState('com_loja.painelcontrole.editar', null);
		
		//Redireciona para o painel de controle, com a mensagem
		$this->setMessage('Edição realizada com sucesso!');
		$this->setRedirect(JRoute::_('index.php?option=com_loja&view=painelcontrole&layout=dadosconta', false));

	}
	
	public function realizarpagamento() {
		
		//Pega o id do pedido passado por parametro
		$idpedido = JRequest::getVar('idpedido');
		
		$modelCarrinho = $this->getModel('carrinho');
		
		$dadoscliente = $modelCarrinho->getDadosCliente();
		
		$modelPainel = $this->getModel('painelcontrole');
		
		$dadospedido = $modelPainel->getDadosPedido($idpedido);
		
		$itenspedido = $modelPainel->getItensPedido($idpedido);
		
		echo '<br/>';
		
		$paymentRequest = new PagSeguroPaymentRequest(); 
		
		//Adiciona os itens do pedido 
		foreach ($itenspedido as $key => $value) {
			$paymentRequest->addItem($value->id, $value->produto_nome,$value->produto_quantidade, $value->produto_preco); 
		}

		$paymentRequest->setSender(  
		    parse_str($dadoscliente->nome_completo), 
		    parse_str($dadoscliente->email), 
		    parse_str(substr($dadoscliente->telefone, 2, 2)), 
		    parse_str(str_replace('-','',substr($dadoscliente->telefone, 6, 10))) 
		);
		
		$paymentRequest->setReference($dadospedido->id);
		
		$paymentRequest->setShippingAddress(  
		   	(str_replace('-', '', $dadospedido->cep_entrega)),   
		    ($dadospedido->endereco_entrega),       
		    ($dadospedido->numero_entrega),       
		    '', 
		    ($dadospedido->bairro_entrega),      
		    ($dadospedido->cidade_entrega),
		    ($dadospedido->estado_entrega),     
		    'BRA'     
		);
		
		$paymentRequest->setShippingCost($dadospedido->valor_frete);
		$paymentRequest->setShippingType(3);
		$paymentRequest->setCurrency("BRL");
		
		$credentials = new PagSeguroAccountCredentials(  
		    'moacir@olhemaisumavez.com.br',   
		    '277A1CCFD84E4870A844649AE3D72FC5'  
		);  
		
		// fazendo a requisição a API do PagSeguro pra obter a URL de pagamento  
		$url = $paymentRequest->register($credentials); 
		
		header("Location: $url");
		
		//$this->setRedirect(JRoute::_('index.php?option=com_loja&view=painelcontrole', false));
	}
		
}