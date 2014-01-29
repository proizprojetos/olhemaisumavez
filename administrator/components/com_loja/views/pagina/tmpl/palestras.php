<?php 

jimport('joomla.form.helper');

defined('_JEXEC') or die('Acesso restrito');
$imagens = json_decode($this->item->imagens_maisinformacoes);
//echo 'a';
//print_r($this->item->atributos);
//print_r($imagens->imagem_1);

JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
?>
<form id="adminForm" action="<?php JRoute::_('index.php?option=com_loja&task=pagina.cancelar') ?>" method="post" enctype="multipart/form-data">	
	<fieldset>
		<?php echo JHtml::_('bootstrap.startTabSet','myTab', array('active' => 'details')); ?>
		
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', JText::_('Dados da Pagina Palestras',true)); ?>
			
				<div class="control-group">
					<div class="control-label">
						<label id="jform_comentario-lbl" for="jform_comentario" 
						class="hasTooltip required" title="" >
						Mensagem Inicial<span class="star">&nbsp;*</span></label>					
					</div>
					<div class="controls">
						<input type="text" name="data[mensagem_inicial]" style="width: 100%"
							   id="jform_comentario" value="<?php echo $this->item->mensagem_inicial ?>"
							   class="inputbox required" size="255" required="required" aria-required="true">					
					</div>
				</div>
				
				<div class="control-group">
					<div class="control-label">
						<label id="jform_comentario-lbl" for="jform_comentario" 
						class="hasTooltip required" title="" >
						Autor da Mensagem Inicial<span class="star">&nbsp;*</span></label>					
					</div>
					<div class="controls">
						<input type="text" name="data[autor_msg_inicial]"
							   id="jform_comentario" value="<?php echo $this->item->autor_msg_inicial ?>"
							   class="inputbox required" size="255" required="required" aria-required="true">					
					</div>
				</div>
				
				<div class="control-group">
					<div class="control-label">
						<label id="jform_comentario-lbl" for="jform_comentario" 
						class="hasTooltip required" title="" >
						Texto resumido do palestrante<span class="star">&nbsp;*</span></label>					
					</div>
					<div class="controls">
						<textarea name="data[texto_palestrante]" style="width: 450px;height: 100px;resize: none;">
							<?php echo $this->item->texto_palestrante ?>
						</textarea>	
					</div>
				</div>
			
				
				<div class="controls">
					<div class="control-label">
						<label id="jform_comentario-lbl" for="jform_comentario" 
						class="hasTooltip required" title="">
						Texto no link "mais informações"<span class="star">&nbsp;*</span></label>					
					</div>
					<div class="controls">
						<?php 
							$value = $this->item->texto_maisinformacoes;  
							$editor = JFactory::getEditor();
							echo $editor->display('data[texto_maisinformacoes]', $value, '100%', '400', '20', false)
						
						?>
					</div>
				</div>
				
				<div class="control-group">
					<div class="control-label">
						<label id="jform_comentario-lbl" for="jform_comentario" 
						class="hasTooltip required" title="" >
						Imagem 1 no link "mais informacoes"<span class="star">&nbsp;*</span></label>					
					</div>
					<div class="controls">
						<input type="file" name="imagens[imagem1_maisinformacoes]" value="0" onchange="alert(this.value)" />
					</div>
				</div>
				
				<div class="control-group">
					<div class="control-label">
						<label id="jform_comentario-lbl" for="jform_comentario" 
						class="hasTooltip required" title="" >
						Imagem 2 no link "mais informacoes"<span class="star">&nbsp;*</span></label>					
					</div>
					<div class="controls">
						<input type="file" name="imagens[imagem2_maisinformacoes]" value="<?php echo $imagens->imagem_2 ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<div class="control-label">
						<label id="jform_comentario-lbl" for="jform_comentario" 
						class="hasTooltip required" title="" >
						Imagem 3 no link "mais informacoes"<span class="star">&nbsp;*</span></label>					
					</div>
					<div class="controls">
						<input type="file" name="imagens[imagem3_maisinformacoes]" value="<?php echo $imagens->imagem_3 ?>" />
					</div>
				</div>
				
				
			
		<?php echo JHtml::_('bootstrap.endTab'); ?>		
		
	</fieldset>		
<input type="hidden" name="task" value="">
</form>