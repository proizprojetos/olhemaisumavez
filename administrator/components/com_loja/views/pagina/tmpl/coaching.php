<?php 

jimport('joomla.form.helper');

defined('_JEXEC') or die('Acesso restrito');
//$atributos = json_decode($this->item->atributos);
//echo 'a';
//print_r($this->item->atributos);
//print_r($atributos);

JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
?>
<form id="adminForm" action="<?php JRoute::_('index.php?option=com_loja&task=pagina.cancelar') ?>" method="post" enctype="multipart/form-data">	
	<fieldset>
		<?php echo JHtml::_('bootstrap.startTabSet','myTab', array('active' => 'details')); ?>
		
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', JText::_('Dados do campo \'Saiba mais\'',true)); ?>
			
				<div class="control-group">
					<div class="control-label">
						<label id="jform_comentario-lbl" for="jform_comentario" 
						class="hasTooltip required" title="" 
						data-original-title="&lt;strong&gt;Comentario&lt;/strong&gt;&lt;br /&gt;Comentario que ira aparecer">
						Titulo<span class="star">&nbsp;*</span></label>					
					</div>
					<div class="controls">
						<input type="text" name="data[titulo]" 
							   id="jform_comentario" value="<?php echo $this->item->titulo ?>"
							   class="inputbox required" size="255" required="required" aria-required="true">					
					</div>
				</div>
			
				
				<div class="controls">
					<div class="control-label">
						<label id="jform_comentario-lbl" for="jform_comentario" 
						class="hasTooltip required" title="">
						Texto<span class="star">&nbsp;*</span></label>					
					</div>
					<div class="controls">
						<?php 
							$value = $this->item->texto;  
							$editor = JFactory::getEditor();
							echo $editor->display('data[texto]', $value, '100%', '400', '20', false)
						
						?>
					</div>
				</div>
			
		<?php echo JHtml::_('bootstrap.endTab'); ?>		
		
	</fieldset>		
<input type="hidden" name="task" value="">
</form>