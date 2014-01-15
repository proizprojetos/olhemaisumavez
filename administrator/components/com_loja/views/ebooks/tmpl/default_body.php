<?php 

defined('_JEXEC') or die('Acesso restrito');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');

JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

//$sortFields = $this->getSortFields();

?>

<form action="<?php echo JRoute::_('index.php?option=com_loja&view=ebooks'); ?>" method="post" name="adminForm" id="adminForm">

	<table class="table table-striped">
	
		<thead>
			<tr>
				<th width="1%">
					<?php echo JHtml::_('grid.checkall'); ?>
				</th>
				<th class="center">
					<?php echo JHTML::_('grid.sort', 'COM_LOJA_LIVROID', 'p.id', $this->sortDirection, $this->sortColumn); ?>
				</th>
				<th class="nowrap center" >
					<?php echo JHtml::_('grid.sort', 'COM_LOJA_LIVROTITULO', 'p.titulo', $this->sortDirection, $this->sortColumn); ?>
				</th>
				<th class="nowrap center" >
					Ano
				</th>	
				<th class="nowrap center" >
					Edição
				</th>
				<th class="nowrap center" >
					Estoque
				</th>
				<th class="nowrap center" >
					Inicio da publicação
				</th>
				<th class="nowrap center" >
					Fim da publicação
				</th>
				
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="12">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($this->itens as $i => $item) { ?>
				<tr class="row<?php echo $i %2; ?>">
					<td class="center">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>
					<td class="center">
						<a href="<?php echo JRoute::_('index.php?option=com_loja&task=ebook.edit&id='.(int) $item->id); ?>" title="">
							<?php echo '00'.$this->escape($item->id); ?> </a>					
					</td>
					<td class="center">
						<?php echo $item->titulo ?>					
					</td>
					<td class="center">
						<?php echo $item->ano ?> 					
					</td>
					<td class="center"> 
						<?php echo $item->edicao?> 					
					</td>
					<td class="center"> 
						<?php echo $item->quantidade?> 					
					</td>
					<td class="center">
						<?php echo date("d/m/Y", strtotime($this->escape($item->inicio_publicacao))); ?> 					
					</td>
					<td class="center">
						<?php echo date("d/m/Y", strtotime($this->escape($item->fim_publicacao))); ?> 					
					</td>					
				</tr>
			<?php } ?>
		</tbody>
		
	</table>
		<input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>" />
		<input type="hidden" name="task" value="">
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
</form>
	
		