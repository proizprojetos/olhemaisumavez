<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

$params = $this->form->getFieldsets('params');

?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'livro.cancel' || document.formvalidator.isValid(document.id('livro-form')))
		{
			Joomla.submitform(task, document.getElementById('livro-form'));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_loja&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="livro-form" class="form-validate form-horizontal" enctype="multipart/form-data">
	
	<fieldset>
		
		<?php echo JHtml::_('bootstrap.startTabSet','myTab', array('active' => 'details')); ?>
		
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', JText::_('Dados da editora',true)); ?>
			
			<?php foreach ($this->form->getFieldset('detalhes_livro') as $field) { ?>
				<div class="control-group">
					<div class="control-label">
						<?php echo $field->label; ?>
					</div>
					<div class="controls">
						<?php echo $field->input; ?>
					</div>
				</div>
			<?php } ?>
		<?php echo JHtml::_('bootstrap.endTab'); ?>		
	
	</fieldset>
	Autores:
	<ul class="treeselect">
		<?php foreach ($this->autores as $key => $value) { ?>
			<ul class="treeselect-sub">
				<li><i class="pull-left icon-"></i>
					<div class="treeselect-item pull-left">
						<input type="checkbox" class="pull-left" name="jform[autor][]" id="<?php echo $value->id?>" value="<?php echo $value->id; ?>" 
						<?php echo $value->checked != 0 ? 'checked' : '' ?>/>
						<label for="<?php echo $value->id; ?>" class="pull-left"><?php echo $value->nomecompleto; ?> <span class="small"></span></label>
					</div><div class="clearfix"></div>
				</li>
			</ul>
		<?php } ?>
	</ul>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
	
</form>