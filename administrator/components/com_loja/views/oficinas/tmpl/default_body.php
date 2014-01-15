<?php 

defined('_JEXEC') or die('Acesso restrito');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');

JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

//$sortFields = $this->getSortFields();

?>

<form action="<?php echo JRoute::_('index.php?option=com_loja&view=oficinas'); ?>" method="post" name="adminForm" id="adminForm">

	<table class="table table-striped">
	
		<thead>
			<tr>
				<th width="1%">
					<?php echo JHtml::_('grid.checkall'); ?>
				</th>
				<th class="nowrap center" >
					ID
				</th>
				<th class="nowrap center" >
					Titulo da Oficina
				</th>
                <th class="nowrap center" >
					Intro
				</th>
				<th class="nowrap center" >
					Ativo?
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
						<a href="<?php echo JRoute::_('index.php?option=com_loja&task=oficina.edit&id='.(int) $item->id); ?>" title="">
							<?php echo '00'.$this->escape($item->id); ?> </a>					
					</td>
					<td class="center">
						<?php echo $item->titulo ?>					
					</td>
					<td class="center">
						<?php echo $item->intro?> 					
					</td>
					<td class="center"> 
						<?php echo ($this->escape($item->ativo) == 1 ? 'Sim' : 'Não') ; ?>					
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
	
		