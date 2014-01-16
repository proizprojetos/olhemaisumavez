<?php
defined('_JEXEC') or die('Acesso restrito');
?>
<?php echo $this->loadTemplate('head'); ?>
<script type="text/javascript">
$(document).ready(function () {
	$('.pedidos_andamento_principal h4').click(function() {
	  	var pai = $(this);
	  	$(this).parent().find('.detalhePedido').slideToggle(500, 'linear', function() {});					
	});
});
</script>
<div class="container painel">
	<div class="row">
		<?php echo $this->loadTemplate('menu'); ?>
		<div class="span8 painel_principal">
		<h3>Últimos pedidos </h3>	
			<div class="pedidos_andamento_principal">
				<?php foreach ($this->ultimosPedidos as $key => $value) { ?>
					<div class="pedido">
						<h2>COD <?php echo '00'.$value->id ?> - Realizado em <?php echo date('d-m-Y',strtotime($value->data_criacao)); ?> - 
						<span><?php if($value->status == 'AGP') { echo 'Aguardando pagamento';?></span></h2>
						<h3>
							<a href="<?php echo JRoute::_('index.php?option=com_loja&task=painelcontrole.realizarpagamento&idpedido='.$value->id); ?>">Realizar pagamento</a>
						</h3>
							<?php }else if($value->status == 'ENV') { ?>
								<span>Pedido enviado</span></h2>
							<? } ?>
						<h4>Detalhe do pedido</h4>
						<div class="detalhePedido" style="display:none ;">
							<h5>Código do pedido:<span> 00<?php echo $value->id; ?></span></h5>
							<h5>Valor do pedido:<span> <?php echo money_format('R$%i', $value->valor_itens); ?></span></h5>
							<h5>Valor do frete:<span> <?php echo money_format('R$%i', $value->valor_frete); ?></span></h5>
							<h5>Valor do frete:<span> <?php echo date("d/m/Y", strtotime($this->escape($value->data_criacao))); ?></span></h5>
							<br/>
							<h4>Itens do pedido</h4>
							<div class="itens_pedido">
								<table class="">
									<thead>
										<tr>
											<th class="center" width="15%">
												Código do item
											</th>
											<th class="nowrap center" width="60%">
												Descrição 
											</th>
											<th class="nowrap center" width="12%">
												Quantidade
											</th>	
											<th class="nowrap center" width="13%">
												Preço unitário
											</th>
										</tr>
									</thead>
									<tbody>
								<?php foreach ($value->itens as $k => $v) { ?>
									<tr class="row<?php echo $i %2; ?>">
										<td class="center" style="text-align:center ;">
											<?php echo $v->produto_codigo; ?>
										</td>
										<td class="center" style="text-align:center ;">
											<?php echo $v->produto_nome; ?>
										</td>
										<td class="center" style="text-align:center ;">
											<?php echo $v->produto_quantidade; ?>
										</td>
										<td class="center" style="text-align:center ;">
											<?php echo $v->produto_preco; ?>
										</td>
									</tr>
								<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				<?php 
					if(end($this->ultimosPedidos) != $value){ ?>
						<hr class="linha_branca" />
					<? } 
				
				} ?>
				
			</div>
		</div>
	</div>
</div>