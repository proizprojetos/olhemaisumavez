<?php 

defined('_JEXEC') or die('Acesos restrito');

jimport('joomla.application.component.modeladmin');

class LojaModelEbook extends JModelAdmin {

	protected $data;
	
	public function getTable($type="Ebook", $prefix="LojaTable", $config=array() ) {
		return JTable::getInstance($type, $prefix, $config);	
	}
	
	public function getForm($data = array(), $loadData = true) {
	
		$form = $this->loadForm('com_loja.ebook', 'ebook', array('control' => 'jform', 'load_data' =>$loadData));
		
		if(empty($form)) {
			return false;
		}
		return $form;		
	}
	
	/**
	*	Método responsavel por pegas os dados que serão injetados no formulario
	*/
	protected function loadFormData() 
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_loja.edit.ebook.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
		}
		return $data;
	}
	
	public function getListaAutores() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$item = $this->getItem();

		$sql = '';
		$isNew = ($item->id == 0);
		
		if(!$isNew) {
			$sql = 'SELECT distinct autor.*, la.id_ebook as "checked"
				FROM #__loja_autores autor left join omuz_loja_ebooks_autores la on la.id_autor = autor.id and la.id_ebook = '.$item->id;
		}else {
			$sql = 'SELECT distinct autor.*, 0 as "checked"
				FROM #__loja_autores autor';
		}
		
		$db->setQuery($sql);
		
		$autores = $db->loadObjectList();

		return $autores;
	}
	
	public function save($data)
	{		

		$dispatcher = JEventDispatcher::getInstance();
		$input      = JFactory::getApplication()->input;
		$table		= $this->getTable();
		
		// Bind the data.
		if (!$table->bind($data))
		{
			$this->setError($table->getError());
			return false;
		}

		// Prepare the row for saving
		$this->prepareTable($table);

		// Check the data.
		if (!$table->check())
		{
			$this->setError($table->getError());
			return false;
		}
	

		// Store the data.
		if (!$table->store())
		{
			$this->setError($table->getError());
			return false;
		}
		
		//Processo para salvar os autores 
		//Pega os ids dos autores selecionados
		$autores = isset($data['autor']) ? $data['autor'] : 0;
		
		//Agora apaga o relacionamento da tabela livros_autores relacionado a esse livro
		$db    = $this->getDbo();
		$query = $db->getQuery(true)
			->delete('#__loja_ebooks_autores')
			->where('id_ebook = ' . (int) $table->id);
		$db->setQuery($query);
		
		try
		{
			$db->execute();
		}
		catch (RuntimeException $e)
		{
			$this->setError($e->getMessage());
			return false;
		}
		
		if(!empty($autores)) {
			//E agora salva o novo relaciomento entre os livros e autores
			foreach ($autores as &$pk) {
				$tuples[] = '(' . (int) $table->id . ',' . (int) $pk . ')';
			}
			$this->_db->setQuery(
				'INSERT INTO #__loja_ebooks_autores (id_ebook, id_autor) VALUES ' .
				implode(',', $tuples)
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
			
		}
		return true;
	}
//	JFactory::getApplication()->enqueueMessage('entrou');
//	return false;
	public function delete(&$pks)
	{
		$dispatcher = JEventDispatcher::getInstance();
		$pks = (array) $pks;
		$table = $this->getTable();

		// Include the content plugins for the on delete events.
		JPluginHelper::importPlugin('content');

		// Iterate the items to delete each one.
		foreach ($pks as $i => $pk)
		{
			if ($table->load($pk))
			{

				if ($this->canDelete($table))
				{

					$context = $this->option . '.' . $this->name;
					
					//Apaga primeiro da tabela #__loja_livros_autores
					
					$db = JFactory::getDBO();
					$query = $db->getQuery(true)	
					->delete('#__loja_ebooks_autores')
					->where('id_ebook = ' . (int) $pk);
					$db->setQuery($query);
					try
					{
						$db->execute();
					}
					catch (RuntimeException $e)
					{
						$this->setError($e->getMessage());
						return false;
					}
					// Trigger the onContentBeforeDelete event.
					$result = $dispatcher->trigger($this->event_before_delete, array($context, $table));
					if (in_array(false, $result, true))
					{
						$this->setError($table->getError());
						return false;
					}

					if (!$table->delete($pk))
					{
						$this->setError($table->getError());
						return false;
					}

					// Trigger the onContentAfterDelete event.
					$dispatcher->trigger($this->event_after_delete, array($context, $table));

				}
				else
				{

					// Prune items that you can't change.
					unset($pks[$i]);
					$error = $this->getError();
					if ($error)
					{
						JLog::add($error, JLog::WARNING, 'jerror');
						return false;
					}
					else
					{
						JLog::add(JText::_('JLIB_APPLICATION_ERROR_DELETE_NOT_PERMITTED'), JLog::WARNING, 'jerror');
						return false;
					}
				}

			}
			else
			{
				$this->setError($table->getError());
				return false;
			}
		}

		// Clear the component's cache
		$this->cleanCache();

		return true;
	}
	
//	
//	public function delete()
//	{	
//		$cid = JFactory::getApplication()->input->get('cid', array(), 'array');
//		
//		foreach ($cid as $key => $value) {
//			JFactory::getApplication()->enqueueMessage('['.$key.']='.$value);
//		}
//		
		//$this->setError('a'.$cid);
		//return false;
//		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
//
		// Get items to remove from the request.
//		$cid = JFactory::getApplication()->input->get('cid', array(), 'array');
//
//		if (!is_array($cid) || count($cid) < 1)
//		{
//			JLog::add(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), JLog::WARNING, 'jerror');
//		}
//		else
//		{	
//			
			//Apaga primeiro da tabela #__loja_livros_autores
//			$db    = $this->getDbo();
//			$query = $db->getQuery(true)
//				->delete('#__loja_livros_autores')
//				->where('id_livro = ' . (int) $cid);
//			$db->setQuery($query);
//			
			// Get the model.
//			$model = $this->getModel();
//
			// Make sure the item ids are integers
//			jimport('joomla.utilities.arrayhelper');
//			JArrayHelper::toInteger($cid);
//
			// Remove the items.
//			if ($model->delete($cid))
//			{
//				$this->setMessage(JText::plural($this->text_prefix . '_N_ITEMS_DELETED', count($cid)));
//			}
//			else
//			{
//				$this->setMessage($model->getError());
//			}
//		}
		// Invoke the postDelete method to allow for the child class to access the model.
//		$this->postDeleteHook($model, $cid);
//
//		$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false));
//	}
	
}