<div class="pedido_detalhe">
	<h3>Dados do pedido</h3>
	<div class="item">
		<p>Status do pedido: 
		<?php if($this->escape($this->pedido->status) === 'AGP' ) {
			echo 'Aguardando Pagamento';
		}else if($this->escape($this->pedido->status) === 'APR' ) {
			echo 'Pagamento Aprovado';
		}else if($this->escape($this->pedido->status) === 'ENV' ) {
			echo 'Enviado';
		}
		else if($this->escape($this->pedido->status) === 'CAN' ) {
			echo 'Cancelado';
		}
		?> 
		</p>
	</div>
	
	<div class="item">
		<p>Pedido realizado em <?php echo date("d/m/Y", strtotime($this->escape($this->pedido->data_criacao))); ?>	 </p>
	</div>
	
	<div class="item">
		<p>Valor dos itens: <?php echo 'R$'.money_format('%i', $this->escape($this->pedido->valor_itens))?> 	 </p>
	</div>
	
	<div class="item">
		<p>Valor do frete: <?php echo 'R$'.money_format('%i', $this->escape($this->pedido->valor_frete))?> 	 </p>
	</div>
	
	<div class="item">
		<p>Valor total: <?php echo 'R$'.money_format('%i',  $this->escape($this->pedido->valor_frete)+$this->escape($this->pedido->valor_itens))?> 	 </p>
	</div>
</div>
<div class="pedido_detalhe_entrega">
	<h3>Dados para entrega</h3>
	<div class="item">
		<p>CEP:<?php echo $this->escape($this->pedido->cep_entrega); ?></p>
	</div>
	<div class="item">
		<p>Endereco: <?php echo $this->escape($this->pedido->endereco_entrega); ?> </p>
	</div>
	<div class="item">
		<p>Número: <?php echo $this->escape($this->pedido->numero_entrega); ?> </p>
	</div>
	<div class="item">
		<p>Bairro: <?php echo $this->escape($this->pedido->bairro_entrega); ?> </p>
	</div>
	<div class="item">
		<p>Cidade: <?php echo $this->escape($this->pedido->cidade_entrega); ?> </p>
	</div>
	<div class="item">
		<p>Estado: <?php echo $this->escape($this->pedido->estado_entrega); ?> </p>
	</div>
</div>
<br/>
<div class="itens_pedido">
	<h3>Itens do pedido:</h3>
	<table class="table table-striped">
		<thead>
			<tr>
				<th class="nowrap center" >
					Código do produto
				</th>
				<th class="nowrap center" >
					Produto
				</th>	
				<th class="nowrap center" >
					Quantidade
				</th>
				<th class="nowrap center" >
					Valor unitario
				</th>
				<th class="nowrap center" >
					Total
				</th>

			</tr>
		</thead>
		<tbody>
		<?php foreach($this->itenspedido as $i => $item) { ?>
			<tr class="row<?php echo $i %2; ?>">
				<td class="center" width="3%">
					<?php echo '00'.$this->escape($item->produto_codigo); ?> 					
				</td>
				<td class="center" width="40%"> 
					<?php echo $this->escape($item->produto_nome)?> 					
				</td>
				<td class="center">
					<?php echo $this->escape($item->produto_quantidade)?> 					
				</td>
				<td class="center">
					<?php echo 'R$'. money_format('%i', $this->escape($item->produto_preco))?>					
				</td>
				<td class="center">
					<?php echo 'R$'. money_format('%i', ($this->escape($item->produto_quantidade*$item->produto_preco)))?>					
				</td>
				
			</tr>			
		<?php } ?>
			
		</tbody>
</div>