<?php 

defined('_JEXEC') or die('Acesso restrito');
?>

<fieldset>
	
	<?php echo JHtml::_('bootstrap.startTabSet','myTab', array('active' => 'details')); ?>
	
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', JText::_('Detalhe do cliente',true)); ?>
		<h3>Detalhe do cliente</h3>
		<div class="item">
			<p>Nome completo: <?php echo $this->escape($this->cliente->nome_completo); ?>	 </p>
		</div>
		<div class="item">
			<p>Email: <?php echo $this->escape($this->cliente->email); ?>	 </p>
		</div>
		<div class="item">
			<p>Nome de usuário: <?php echo $this->escape($this->cliente->username);  ?>	 </p>
		</div>
		<div class="item">
			<p>CPF: <?php echo $this->escape($this->cliente->cpf);  ?>	 </p>
		</div>
		<div class="item">
			<p>Data de registro: <?php echo date("d/m/Y G:i:s", strtotime($this->escape($this->cliente->dataregistro))); ?>	 </p>
		</div>
		<div class="item">
			<p>Telefone : <?php echo $this->cliente->telefone;  ?>	 </p>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>		
	
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'detailsend', JText::_('Detalhe do endereço',true)); ?>
		<h3>Endereço para entrega</h3>
		<div class="item">
			<p>Endereco: <?php echo $this->escape($this->cliente->endereco); ?> </p>
		</div>
		<div class="item">
			<p>Número: <?php echo $this->escape($this->cliente->numero); ?> </p>
		</div>
		<div class="item">
			<p>Complemento: <?php echo isset($this->cliente->complemento) ? $this->escape($this->cliente->complemento) : 'Nenhum'; ?> </p>
		</div>
		<div class="item">
			<p>CEP: <?php echo $this->escape($this->cliente->cep); ?> </p>
		</div>
		<div class="item">
			<p>Bairro: <?php echo $this->escape($this->cliente->bairro); ?> </p>
		</div>
		<div class="item">
			<p>Cidade: <?php echo $this->escape($this->cliente->cidade); ?> </p>
		</div>
		<div class="item">
			<p>Estado: <?php echo $this->escape($this->cliente->estado); ?> </p>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'detaisebook', JText::_('Ebooks baixados',true)); ?>
		
		<table class="table table-striped">
			<thead>
				<tr>
					<th class="nowrap center" width="10%" >
						Titulo ebook
					</th>
					<th class="nowrap center" width="15%" >
						Quantidade
					</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($this->listaebooks as $key => $value) {?>
					
					<tr class="row<?php echo $i %2; ?>">
						<td class="center">
							<?php echo $value->titulo ?> 					
						</td>
						<td class="center"> 
							<?php echo $value->quantidade?> 					
						</td>					
					</tr>
				<?php } ?>
			</tbody>
		</table>
		
		<?php foreach ($this->listaebooks as $key => $value) { ?>
			
		<? } ?>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
</fieldset>	