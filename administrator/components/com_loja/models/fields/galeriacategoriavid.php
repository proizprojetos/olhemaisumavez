<?php

defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');


class JFormFieldGaleriacategoriavid extends JFormFieldList
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'galeriacategoriavid';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array		An array of JHtml options.
	 */
	protected function getOptions() 
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id, nome');
		$query->from('#__loja_galeria_categoria cat')
				->where('cat.status = 1 and cat.tipo = \'VID\'');
		$db->setQuery((string)$query);
		$messages = $db->loadObjectList();
		$options = array();
		if ($messages)
		{
			foreach($messages as $message) 
			{
				$options[] = JHtml::_('select.option', $message->id, $message->nome);
			}
		}
		$options = array_merge(parent::getOptions(), $options);
		return $options;
	}
}