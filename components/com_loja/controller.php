<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

JLoader::register('LojaHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'loja.php');

require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_loja'.DS.'PagSeguroLibrary'.DS.'PagSeguroLibrary.php');

class LojaController extends JControllerLegacy {

	function display($cachable = false, $urlparams = false) {

		$vName	 = JRequest::getCmd('view');
		
		if($vName == 'painelcontrole') {
			$user = JFactory::getUser();
			if( $user->get('guest') == 1) {
				//Caso o usuario não esteja logado redireciona para a tela de login
				$this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));
				return;
			}
		}else if($vName =='cadastro') {
			$user = JFactory::getUser();
			if( $user->get('guest') != 1) {
				//Caso o usuario esteja logado redireciona para o painel de controle dele
				$this->setRedirect(JRoute::_('index.php?option=com_loja&view=painelcontrole', false));
				return;
			}
		}
		
		parent::display();
	}
	
	public function pagseguro() {
		
		$mensagem ='Iniciou<br/>';
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			if(isset($_POST['notificationType']) && $_POST['notificationType'] == 'transaction'){
	
				$email = 'moacir@olhemaisumavez.com.br'; 
				$token = '277A1CCFD84E4870A844649AE3D72FC5';
				
				$mensagem .= 'Codigo de notificacao: '. $_POST['notificationCode'].'<br/>';
				
				$tipoNotificacao = 	$_POST['notificationType'];
				$codigoNotificacao = $_POST['notificationCode'];
				if($tipoNotificacao == 'transaction') {
					
					$credencial = new PagSeguroAccountCredentials($email, $token);
								
					//Verifica as informações da transação, e retorna 
			        //o objeto Transaction com todas as informações
			        $transacao = PagSeguroNotificationService::checkTransaction($credencial, $codigoNotificacao);
					
					//Retorna o objeto TransactionStatus, que vamos resgatar o valor do status
			        $status    = $transacao->getStatus(); 
			        
			        if($status->getValue() == 3) {
						
						$idPedido = $transacao->getReference();
						
						//Atualiza a Tabela no banco
						$db = JFactory::getDBO();
						
						$db->setQuery(
							'UPDATE #__loja_pedidos SET status =\'APR\' WHERE ID = '.$idPedido
						);
						
						try
						{
							$db->execute();
						}
						catch (RuntimeException $e)
						{
							$mensagem .= 'Ocorreu o erro: '.$e->getMessage().'</br>';
							//return false;
						}
						
						/*$db = JFactory::getDBO();
						
						$db->setQuery(
							'UPDATE #__loja_livros SET estoque = (estoque - 1) WHERE id = '.$idPedido
						);
						
						try
						{
							$db->execute();
						}
						catch (RuntimeException $e)
						{
							$mensagem .= 'Ocorreu o erro: '.$e->getMessage().'</br>';
							//return false;
						}*/
						
						$mensagem .= 'Atualizaou no banco com sucesso<br/>';
					}
				}		   
			   
			}else {
				$mensagem .= 'Dados não são uma transaction<br/>';
			}
		}else {
			$mensagem .= 'Dados não vieram de POST<br/>';
		}		
		$mensagem .='Fim';
		
		LojaHelper::gravaLog($mensagem);

	}
	
	/*
	public function enviarEmail() {
		if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ){
		  if (isset($_POST['nome']) AND isset($_POST['email']) AND isset($_POST['telefone']) AND isset($_POST['mensagem'])) {
			$to = 'your@mail.id';
		
			$name = filter_var($_POST['nome'], FILTER_SANITIZE_STRING);
			$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
			$subject = filter_var($_POST['telefone'], FILTER_SANITIZE_STRING);
			$message = filter_var($_POST['mensagem'], FILTER_SANITIZE_STRING);
		
			$sent = email($to, $email, $name, $subject, $message);
			if ($sent) {
			  echo 'Message enviada!';
			} else {
			  echo 'Message nao pode ser enviada!';
			}
		  }
		  else {
			echo 'All Fields are required';
		  }
		  return;
		}
			
	}
	*/
	public function enviar_email() {
		//Pega os parametros passados da solicitação
		$paramentro = JRequest::getVar("param");
		$params = array();
		//Como eles estao serializados, joga eles dentro de uma array
		parse_str($paramentro, $params);
//		$nomeremetente	= $_POST['sugestao_nome'];
//		$email			= $_POST['sugestao_email'];
//		$mensagem		= $_POST['sugestao_mensagem'];
//		
		$mailer = JFactory::getMailer();
		        
        $config = JFactory::getConfig();
        $sender = array( 
            $config->get( 'config.mailfrom' ),
            $config->get( 'config.fromname' ) );
         
        $mailer->setSender('moacir@olhemaisumavez.com.br');
        //$mailer->setSender($sender);
		
        $mailer->addRecipient('moacir@olhemaisumavez.com.br');
      
        $titulo = 'Email de Contato!';
        
        $horaenviado = date("d/m/y H:i");
        
        $mensagemHTML = '<p>Email de contato<p>
        <p>Nome: '.$params['nome'].'</b>
        <p>Email: '.$params['email'].'</b>
        <p>Telefone: '.$params['telefone'].'</b>
		<p>Assunto: '.$params['assunto'].'</b>
		<p>Mensagem: '.$params['mensagem'].'</b>
        <hr>';
		
		if(!empty($params['oficina'])) {
		$mensagemHTML = '<p>Oficinas: '.$params['oficina'].'</b>
        <hr>';	
		}
		

      	
        $mailer->setSubject($titulo);
//        Quando o email tem tags html é necessario dizer ao mail que é um HTML
        $mailer->isHTML(true);
        $mailer->Encoding = 'base64';
        $mailer->setBody($mensagemHTML);
        
        $send = $mailer->Send();
        if ( $send === true ) {
            echo json_encode("certo");
        } else {
            echo json_encode('erro');
        }
	}
	
	
	function email($to, $from_mail, $from_name, $subject, $message){
		$header = array();
		$header[] = "MIME-Version: 1.0";
		$header[] = "From: {$from_name}<{$from_mail}>";
		/* Set message content type HTML*/
		$header[] = "Content-type:text/html; charset=iso-8859-1";
		$header[] = "Content-Transfer-Encoding: 7bit";
		if( mail($to, $subject, $message, implode("\r\n", $header)) ) return true; 
	}

}

?>