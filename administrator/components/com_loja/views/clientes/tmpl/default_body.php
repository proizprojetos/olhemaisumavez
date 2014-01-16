<?php 

defined('_JEXEC') or die('Acesso restrito');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');

JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

//$sortFields = $this->getSortFields();

?>

<form action="<?php echo JRoute::_('index.php?option=com_loja&view=clientes'); ?>" method="post" name="adminForm" id="adminForm">

	<table class="table table-striped">
	
		<thead>
			<tr>
				<th width="2%">
					<?php echo JHtml::_('grid.checkall'); ?>
				</th>
				<th class="center">
					<?php echo JHTML::_('grid.sort', 'COM_LOJA_CLIENTEID', 'p.id', $this->sortDirection, $this->sortColumn); ?>
				</th>
				<th class="nowrap center" width="30%"  >
					<?php echo JHtml::_('grid.sort', 'COM_LOJA_CLIENTENOME', 'p.nomecompleto', $this->sortDirection, $this->sortColumn); ?>
				</th>
				<th class="nowrap center" width="10%" >
					Email
				</th>
				<th class="nowrap center" width="15%" >
					Telefone
				</th>
				<th class="nowrap center" width="15%" >
					Nome de usuário
				</th>
				<th class="nowrap center" width="10%" >
					Data de registro
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
						<a href="<?php echo JRoute::_('index.php?option=com_loja&view=cliente&layout=visualizarcliente&id='.(int) $item->id); ?>" title="">
							<?php echo '00'.$this->escape($item->id); ?> </a>					
					</td>
					<td class="center">
						<?php echo $item->nome_completo ?>					
					</td>
					<td class="center">
						<?php echo $item->email ?> 					
					</td>
					<td class="center"> 
						<?php echo $item->telefone?> 					
					</td>	
					<td class="center"> 
						<?php echo $item->username?> 					
					</td>	
					<td class="center"> 
						<?php echo date("d/m/Y G:i:s", strtotime($this->escape($item->dataregistro))); ?>	 					
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
	
		