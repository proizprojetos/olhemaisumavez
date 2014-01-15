<?php 

defined('_JEXEC') or die('Acesso restrito');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');

JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

//$sortFields = $this->getSortFields();

?>

<form action="<?php echo JRoute::_('index.php?option=com_loja&view=pedidos'); ?>" method="post" name="adminForm" id="adminForm">

	<table class="table table-striped">
	
		<thead>
			<tr>
				<th width="1%">
					<?php echo JHtml::_('grid.checkall'); ?>
				</th>
				<th class="center">
					<?php echo JHTML::_('grid.sort', 'COM_LOJA_PEDIDOID', 'p.id', $this->sortDirection, $this->sortColumn); ?>
				</th>
				<th class="nowrap center" >
					<?php echo JHtml::_('grid.sort', 'COM_LOJA_PEDIDOSTATUS', 'p.status', $this->sortDirection, $this->sortColumn); ?>
				</th>
				<th class="nowrap center" >
					Cliente
				</th>	
				<th class="nowrap center" >
					<?php echo JHtml::_('grid.sort', 'COM_LOJA_VALORPEDIDO', 'p.valor_itens', $this->sortDirection, $this->sortColumn); ?>
				</th>
				<th class="nowrap center" >
					Valor do frete
				</th>
				<th class="nowrap center" >
					Total
				</th>
				<th class="nowrap center" >
					<?php echo JHtml::_('grid.sort', 'COM_LOJA_DATAPEDIDO', 'p.data_criacao', $this->sortDirection, $this->sortColumn); ?>
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
						<a href="<?php echo JRoute::_('index.php?option=com_loja&view=pedido&layout=visualizarpedido&idpedido='.(int) $item->id); ?>" title="">
							<?php echo '00'.$this->escape($item->id); ?> </a>					
					</td>
					<td class="center">
						<a href="<?php echo JRoute::_('index.php?option=com_loja&view=pedido&layout=visualizarpedido&idpedido='.(int) $item->id); ?>" title="">
							<?php if($this->escape($item->status) === 'AGP' ) {
								echo 'Aguardando Pagamento';
							}else if($this->escape($item->status) === 'APR' ) {
								echo 'Pagamento Aprovado';
							}else if($this->escape($item->status) === 'ENV' ) {
								echo 'Enviado';
							}
							else if($this->escape($item->status) === 'CAN' ) {
								echo 'Cancelado';
							}
							?> 
						</a>					
					</td>
					<td class="center">
						<?php echo '00'.$this->escape($item->id_cliente).' - '.$this->escape($item->nome_completo); ?> 					
					</td>
					<td class="center"> 
						<?php echo 'R$'. money_format('%i', $this->escape($item->valor_itens))?> 					
					</td>
					<td class="center">
						<?php echo 'R$'. money_format('%i', $this->escape($item->valor_frete))?> 					
					</td>
					<td class="center">
						<?php echo 'R$'.( money_format('%i',$this->escape($item->valor_itens)+$this->escape($item->valor_frete)))?>					
					</td>
					<td class="center">
						<?php echo date("d/m/Y", strtotime($this->escape($item->data_criacao))); ?>					
					</td>
				</tr>
			<?php } ?>
		</tbody>
		
	</table>
		<input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>" />
		<input type="hidden" name="task" value="">
	
</form>
	
		