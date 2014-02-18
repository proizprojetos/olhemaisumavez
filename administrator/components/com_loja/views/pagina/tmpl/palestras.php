<?php 

jimport('joomla.form.helper');

defined('_JEXEC') or die('Acesso restrito');

JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
?>

<form id="adminForm" action="<?php JRoute::_('index.php?option=com_loja&task=pagina.cancelar') ?>" method="post" enctype="multipart/form-data">	
	<fieldset>
		<?php echo JHtml::_('bootstrap.startTabSet','myTab', array('active' => 'details')); ?>
		
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', JText::_('Dados da Pagina Palestras',true)); ?>
			
			<?php foreach ($this->form->getFieldset('detalhes_palestras') as $field) { ?>
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
		
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'tabvendas', JText::_('Dados do Item Vendas',true)); ?>
			<?php foreach ($this->form->getFieldset('detalhes_palestras_vendas') as $field) { ?>
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
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'tabsuperacao', JText::_('Dados do Item Superação e motivação',true)); ?>
			<?php foreach ($this->form->getFieldset('detalhes_palestras_superacao') as $field) { ?>
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
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'tabemp', JText::_('Dados do Item Empreendedorismo',true)); ?>
			<?php foreach ($this->form->getFieldset('detalhes_palestras_emp') as $field) { ?>
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
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'tabinc', JText::_('Dados do Item Inclusão e diversidade',true)); ?>
			<?php foreach ($this->form->getFieldset('detalhes_palestras_inclusao') as $field) { ?>
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
		
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'tabsipat', JText::_('Dados do Item SIPAT',true)); ?>
			<?php foreach ($this->form->getFieldset('detalhes_palestras_sipat') as $field) { ?>
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
<input type="hidden" name="task" value="">
</form>