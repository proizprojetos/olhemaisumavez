<?php

defined('_JEXEC') or die('Restricted access');

//jimport('joomla.application.component.modelitem');
jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_loja'.DS.'tables');

class LojaModelCarrinho extends JModelForm {
	
	//protected $msg;
	protected $data;
	
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_livros.carrinho', 'carrinho', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}
	
	private function getCarrinhoCookie() {
		//setcookie('carrinho', null);
		$carrinho = array();
		if(isset($_COOKIE['carrinho'])) {
			$carrinho = unserialize($_COOKIE['carrinho']);
		}
		return $carrinho;
	}
	
	private function setCarrinhoCookie($itemgravar) {
		$carrinho = serialize($itemgravar);
		setcookie('carrinho', $carrinho, time() + 60*60*24*7);
	}
	
	public function getCarrinho() {
		
		$carrinho = $this->getCarrinhoCookie();
		if(!isset($carrinho)) {
			return false;
		}
		
		$this->carrinho_tela = array();
		
		foreach ($carrinho as $chave => $item) {
			
			$livro = array();
			
			$livro = $this->getLivro($item['id']);
			
			$livro->quantidade = $item['quantidade'];
			
			array_push($this->carrinho_tela, $livro);
			
		}
		
		return $this->carrinho_tela;
	
	}
	
	public function aumentarQuantidade($id) {
		$carrinho = $this->getCarrinhoCookie();
		
		$idLivro = $id;
		
		foreach ($carrinho as $key => &$value) {
			if($value['id'] == $idLivro) {
				$value['quantidade'] += 1;
				break;
			}
		}
		$this->setCarrinhoCookie($carrinho);
		
		return true;
	}
	
	public function diminuirQuantidade($id) {
		$carrinho = $this->getCarrinhoCookie();
		
		$idLivro = $id;
		
		foreach ($carrinho as $key => &$value) {
			if($value['id'] == $idLivro) {
				$value['quantidade'] -= 1;
				if($value['quantidade'] <= 0) {
					unset($carrinho[$key]);
				}
				break;
			}
		}
		$this->setCarrinhoCookie($carrinho);
		
		return true;
	}
	
	public function removerItem($id) {
		$carrinho = $this->getCarrinhoCookie();
		
		$idLivro = $id;
		
		foreach ($carrinho as $key => &$value) {
			if($value['id'] == $idLivro) {
				unset($carrinho[$key]);
				break;
			}
		}
		$this->setCarrinhoCookie($carrinho);
		
		return true;
	}
	
	private function getLivro($id) {
		
		
		$db = JFactory::getDBO();
		
		$query = $db->getQuery(true);
		
		$query->select('livro.id, livro.titulo, livro.valor, livro.imagem_capa, livro.fretegratis');
		$query->from('#__loja_livros livro');
		$query->where(' livro.id = '.$id);
		$db->setQuery((String) $query);
		$livro = $db->loadObject();
		
		$query->clear();
		$query->select('autor.*');
		$query->from('#__loja_autores autor');
		$query->JOIN('INNER', '#__loja_livros_autores la on la.id_autor = autor.id and la.id_livro = '.$livro->id);
		$db->setQuery((String) $query);
		$autores = $db->loadObjectList();
		$livro->autores = $autores; 
		
		return $livro;
		
	}
	
	public function getValorFrete() {
		$this->data = new stdClass();
		$app 		= JFactory::getApplication();
		
		//Pega o array de quadros enviados da tela de customização
		$this->valorFrete = (float)$app->getUserState('com_popstil.carrinho.valorFrete');
		//$app->setUserState('com_popstil.carrinho.valorFrete', '');
		
		return $this->valorFrete;
			
	}
	
	public function getTotalPedido() {
		
		$valortotal = 0;
		$valortotal += $this->getValorFrete();
		
		$carrinho = $this->getCarrinhoCookie();
		
		foreach ($carrinho as $key => $value) {
				$livro = $this->getLivro($value['id']);
				$valortotal += $livro->valor * $value['quantidade'];
		}
		
		
		return $valortotal;
		
	}
	
	public function getDadosCliente() {
	
		$user = JFactory::getUser();
			
		$db = JFactory::getDBO();
	
		$query = $db->getQuery(true);
		$query->select('cliente.*');
		$query->from('#__loja_clientes cliente ');
		$query->where('cliente.id_joomla = '.$user->id);
		$db->setQuery((String) $query);
		$cliente = $db->loadObject();
		
		return $cliente;
		
	}
	
	public function getDadosPedidoAndamento() {
		
		//$cliente = $this->getDadosCliente();
		
		$dadosentrega = (array)JFactory::getApplication()->getUserState('com_loja.carrinho.dadosentrega', array());

		$pedido;
		
		$carrinho = $this->getCarrinho();
		
		$valoritens = 0;
		
		foreach ($carrinho as $key => $value) {
			$valoritens = $value->valor * $value->quantidade;
		}
		
		
		$pedido->valorsubtotal = $valoritens;
		$cep = 0;
		if($dadosentrega != null && !empty($dadosentrega)) {
			$cep = $dadosentrega['cep'];
		}else {
			//$cep = $cliente->cep;
			
		}
		
		$pedido->valorfrete->pac = $this->calculaFreteCorreios('41106','88090100',$cep);
		$pedido->valorfrete->sedex = $this->calculaFreteCorreios('40010','88090100',$cep);
		
		//Data de criação do pedido
		$pedido->datacriacao = strftime('%Y-%m-%d %H:%M:%S',time());
		
		//$app = JFactory::getApplication();
		//$app->setUserState('com_loja.carrinho.dadospedidoandamento', $pedido);
		return $pedido;
	}
	
	public function finalizarPedido($temp) {
		
		$pedidogravar;
		
		$dadosPedido = $this->getDadosPedidoAndamento();
		
		$cliente = $this->getDadosCliente();
		
		$app = JFactory::getApplication();
		
		$fretegratis				= $app->getUserState('com_loja.carrinho.fretegratis');
		
		if($fretegratis == '0') {
			if($temp['tipo_frete'] == 0) {
				$pedidogravar['valor_frete'] =(double) str_replace(',','.', $dadosPedido->valorfrete->pac);
			}else {
				$pedidogravar['valor_frete'] = (double) str_replace(',','.', $dadosPedido->valorfrete->sedex);
			}
		}else {
			$pedidogravar['valor_frete'] = 0;
			$temp['tipo_frete'] = 2;
		}
		
		$carrinho = $this->getCarrinho();
		
		$valorTotal = 0;
		
		foreach ($carrinho as $key => $value) {
			$valorTotal += $value->valor*$value->quantidade;
		}
		
		
		$tablePedido = JTable::getInstance('pedido', 'LojaTable');
		
		$dadosentrega = (array)JFactory::getApplication()->getUserState('com_loja.carrinho.dadosentrega', array());
		
		$pedidogravar['data_criacao']	= strftime('%Y-%m-%d %H:%M:%S',time());
		$pedidogravar['valor_pedido'] 	= $valorTotal+$pedidogravar['valor_frete'];
		$pedidogravar['status'] 			= 'AGP';
		$pedidogravar['valor_itens']		= $valorTotal;
		$pedidogravar['cep_entrega']		= $dadosentrega['cep'];
		$pedidogravar['endereco_entrega']	= $dadosentrega['endereco'];
		$pedidogravar['numero_entrega']		= $dadosentrega['numero'];
		$pedidogravar['bairro_entrega']		= $dadosentrega['bairro'];
		$pedidogravar['cidade_entrega']		= $dadosentrega['cidade'];
		$pedidogravar['estado_entrega']		= $dadosentrega['estado'];
		$pedidogravar['tipo_frete']			= $temp['tipo_frete'];
		$pedidogravar['id_cliente']			= $cliente->id;
		
		if(!$tablePedido->bind($pedidogravar)) {
			//$this->setError('Erro ao finalizar pedido, tente novamente.');
			//return false;
		}
		if(!$tablePedido->store()) {
			//$this->setError('Erro ao finalizar pedido, tente novamente.');
			//return false;
		}
		$idpedido = $tablePedido->id;
		
		$pedidogravar['id'] = $idpedido;
		
		
		foreach ($carrinho as $key => $value) {
			
			$tableItem 	 	= JTable::getInstance('itenspedido', 'LojaTable');
			
			$itensPedido['produto_quantidade'] = $value->quantidade;
			$itensPedido['produto_codigo'] = $value->id;
			$itensPedido['produto_preco'] = $value->valor;
			$itensPedido['produto_nome'] = $value->titulo;
			$itensPedido['id_pedido'] = $idpedido;
			
			if(!$tableItem->bind($itensPedido)) {
			//			$this->setError('Erro ao finalizar pedido, tente novamente.');
			//			return false;
			}
			if(!$tableItem->store()) {
			//			$this->setError('Erro ao finalizar pedido, tente novamente.');
			//			return false;
			}
		}
		
		JFactory::getApplication()->setUserState('com_loja.carrinho.dadospedidofinalizado',$pedidogravar);
		
		return true;
		
	}
	
	public function calculaFreteCorreios($cod_servico, $cep_origem, $cep_destino, $peso='0.300', $valor_declarado='20.00', $altura = '10', 
		$largura = '15', $comprimento='16') {		
		
		
		//Verifica se o pedido tem itens com frete gratis
		$carrinho = $this->getCarrinho();
		$frete_gratis = false;
		foreach ($carrinho as $key => $value) {
			if($value->fretegratis) {
				$frete_gratis = true;
			}else {
				$frete_gratis = false;
			}
		}
		
		if($frete_gratis) {
			JFactory::getApplication()->setUserState('com_loja.carrinho.fretegratis','1');
			return false;
		}else {
			JFactory::getApplication()->setUserState('com_loja.carrinho.fretegratis','0');
		}
		$data = array();  
		
		$data['sCepOrigem'] 	= $cep_origem;
		$data['sCepDestino'] 	= $cep_destino;
		$data['nVlPeso']		= $peso;
		$data['nCdFormato']		= '3';
		$data['nVlComprimento'] =  $comprimento;
		$data['nVlAltura'] = $altura;
		$data['nVlLargura'] = $largura;
		$data['nVlDiametro'] = '15';
		$data['sCdMaoPropria'] = 'N';
		$data['nVlValorDeclarado'] = '0';
		$data['sCdAvisoRecebimento'] = 'N';
		$data['StrRetorno'] = 'xml';
		$data['nCdServico'] = $cod_servico;
		
		$data = http_build_query($data);
		
		$url = 'http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx';
		
		$curl = curl_init($url . '?' .  $data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		$result = curl_exec($curl);
		
		$result = simplexml_load_string($result);

		if($result->cServico->Erro == '0') {
			return $result->cServico->Valor;
		}
		else {
			$this->setError('CEP informado é inválido!');
			return false;	
		}
	}

}

?>