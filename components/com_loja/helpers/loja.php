<?php

defined('_JEXEC') or die ('Acesso Restrito');
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_loja'.DS.'tables');
abstract class LojaHelper {
	
	public static function gravaLog($mensagem) {
		
		$tableLog = JTable::getInstance('log', 'LojaTable');
		
		$dados['mensagem']  = $mensagem;
		$dados['data']		= strftime('%Y-%m-%d %H:%M:%S',time());
		$dados['ip']		= $_SERVER["REMOTE_ADDR"];
		
		if(!$tableLog->bind($dados)) {
			$this->setError('Erro ao bindar log, tente novamente.');
			return false;
		}
		if(!$tableLog->store()) {
			$this->setError('Erro ao gravar pedido, tente novamente.');
			return false;
		}
	}
	
}