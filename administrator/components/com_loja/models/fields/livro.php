<?php

defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');


class JFormFieldLivro extends JFormFieldList
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'Livro';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array		An array of JHtml options.
	 */
	protected function getOptions() 
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id, titulo');
		$query->from('#__loja_livros');
		$db->setQuery((string)$query);
		$messages = $db->loadObjectList();
		$options = array();
		$options[] = JHtml::_('select.option','0', 'Nenhum');
		if ($messages)
		{
			foreach($messages as $message) 
			{
				$options[] = JHtml::_('select.option', $message->id, $message->titulo);
			}
		}
		$options = array_merge(parent::getOptions(), $options);
		return $options;
	}
}