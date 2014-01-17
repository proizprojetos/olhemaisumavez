<?php

defined('_JEXEC') or die ('Acesso Restrito');
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_loja'.DS.'tables');
abstract class LojaHelper {
	
	//Configura a barra de menus lateral
	public static function addSubmenu($submenu) {
		
		$view = JRequest::getVar('view');
		
		JSubMenuHelper::addEntry(JText::_('COM_LOJA_SUBMENU_DASHBOARD'), 'index.php?option=com_loja', $view == 'Dashboard');
		JSubMenuHelper::addEntry(JText::_('COM_LOJA_SUBMENU_CLIENTES'), 'index.php?option=com_loja&view=clientes', $view == 'clientes' || $view == 'cliente');
		JSubMenuHelper::addEntry(JText::_('COM_LOJA_SUBMENU_PEDIDOS'), 'index.php?option=com_loja&view=pedidos', $view == 'pedidos' || $view == 'pedido');
		JSubMenuHelper::addEntry(JText::_('COM_LOJA_SUBMENU_EDITORAS'), 'index.php?option=com_loja&view=editoras', $view == 'editoras' || $view == 'editora');
		JSubMenuHelper::addEntry(JText::_('COM_LOJA_SUBMENU_AUTORES'), 'index.php?option=com_loja&view=autors', $view == 'autors' || $view == 'autor');
		JSubMenuHelper::addEntry(JText::_('COM_LOJA_SUBMENU_LIVROS'), 'index.php?option=com_loja&view=livros', $view == 'livros' || $view == 'livro');
		JSubMenuHelper::addEntry(JText::_('COM_LOJA_SUBMENU_EBOOKS'), 'index.php?option=com_loja&view=ebooks', $view == 'ebooks' || $view == 'ebook');
		JSubMenuHelper::addEntry(JText::_('COM_LOJA_SUBMENU_OFICINAS'), 'index.php?option=com_loja&view=oficinas', $view == 'oficinas' || $view == 'oficina');
		JSubMenuHelper::addEntry('Gerenciar Comentários', 'index.php?option=com_loja&view=comentarios', $view == 'comentarios' || $view == 'comentarios');
	}
	
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