<?php

defined('_JEXEC') or die('Acesso restrito');

?>

<div class="container livros">
	<div class="row">
		<div class="span12">
			<img src="<?php echo JURI::root() ?>/components/com_loja/assets/img/carrinho.png" alt="" />
			<h2>LOJA</h2><jdoc:include type="menu_interno" />
			<hr />
			<jdoc:include type="finalizar_pedido" />
		</div>
	</div>
</div>