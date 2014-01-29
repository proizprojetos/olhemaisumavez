<?php

defined('_JEXEC') or die('Restricted access');

//jimport('joomla.application.component.modelitem');
jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');

class LojaModelLivros extends JModelForm {
	
	//protected $msg;
	protected $data;
	
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_livros.livro', 'livro', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	public function getListalivros() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$date = JFactory::getDate();
		
		$nowDate = $db->quote($date->toSql());
		
		$query->select('livro.*, edi.nome as editora')
		->from('#__loja_livros livro')
		->JOIN('INNER', '#__loja_editoras as edi on edi.id = livro.id_editora')
		->where('(livro.inicio_publicacao <= '.$nowDate.')') 
		->where('(livro.fim_publicacao >= '.$nowDate.')'); 
		$db->setQuery((String) $query);
		$livros = $db->loadObjectList();
		
		foreach ($livros as $key => &$value) {
			
			$query->clear();
			$query->select('autor.*');
			$query->from('#__loja_autores autor');
			$query->JOIN('INNER', '#__loja_livros_autores la on la.id_autor = autor.id and la.id_livro = '.$value->id);
			$db->setQuery((String) $query);
			$autores = $db->loadObjectList();
			$value->autores = $autores; 
		}
		
		return $livros;
	}
	
	public function getListaebooks() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$date = JFactory::getDate();
		
		$nowDate = $db->quote($date->toSql());
		
		$query->select('ebook.*, edi.nome as editora')
		->from('#__loja_ebooks ebook')
		->JOIN('INNER', '#__loja_editoras as edi on edi.id = ebook.id_editora')
		->where('(ebook.inicio_publicacao <= '.$nowDate.')') 
		->where('(ebook.fim_publicacao >= '.$nowDate.')'); 
		$db->setQuery((String) $query);
		$livros = $db->loadObjectList();
		
		foreach ($livros as $key => &$value) {
			
			$query->clear();
			$query->select('autor.*');
			$query->from('#__loja_autores autor');
			$query->JOIN('INNER', '#__loja_ebooks_autores la on la.id_autor = autor.id and la.id_ebook = '.$value->id);
			$db->setQuery((String) $query);
			$autores = $db->loadObjectList();
			$value->autores = $autores; 
		}
		
		return $livros;
	}
	
	public function getLivroDetalhe() {
		
		$idlivro 	= JRequest::getVar('idlivro');
			
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query->select('livro.* ')
		->from('#__loja_livros livro')
		->where('(livro.id >= '.$idlivro.')'); 
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
	
	public function getLivroDetalheEbook() {
		
		$idlivro 	= JRequest::getVar('idlivro');
			
		$livro = $this->getLivro($idlivro);
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->clear();
		$query->select('autor.*');
		$query->from('#__loja_autores autor');
		$query->JOIN('INNER', '#__loja_livros_autores la on la.id_autor = autor.id and la.id_livro = '.$livro->id);
		$db->setQuery((String) $query);
		$autores = $db->loadObjectList();
		$livro->autores = $autores; 
		
		return $livro;
	}
	
	public function getLivro($id) {

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query->select('ebook.* ')
		->from('#__loja_ebooks ebook')
		->where('(ebook.id >= '.$id.')'); 
		$db->setQuery((String) $query);
		$livro = $db->loadObject();
		
		return $livro;
	}
	
	public function gravaLivroBaixado($dados) {
		
		$id = $dados['id_user'];
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query->select('cliente.* ')
		->from('#__loja_clientes cliente')
		->where('(cliente.id_joomla >= '.$id.')'); 
		$db->setQuery((String) $query);
		$cliente = $db->loadObject();
		
		
		$dadosgravar['id_ebook']				= $dados['id_ebook'];
		$dadosgravar['id_cliente']				= $cliente->id;
		$dadosgravar['data_download']	= '\''.strftime('%Y-%m-%d %H:%M:%S',time()).'\'';
		
		
		$this->_db->setQuery(
			'INSERT INTO #__loja_ebooks_clientes_downloads (id_ebook, id_cliente,data_download) VALUES (' .
			implode(',', $dadosgravar).')'
		);
		
		try
		{
			$db->execute();
		}
		catch (RuntimeException $e)
		{
			$this->setError($e->getMessage());
			return false;
		}
		
		return true;
	
	}
	

}

?>