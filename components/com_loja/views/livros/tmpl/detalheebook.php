<?php

defined('_JEXEC') or die('Acesso restrito');
?>

<div class="livro_fundo0 livro detalhe">
	<div class="container" style="position:relative ;">
		<div class="span3">
			<div class="imagem_livro">
				<img src="<?php echo $this->livro->imagem_capa ?>" alt="" />
			</div>
			<h2>Ficha técnica</h2>
			<hr />
			<?php if(isset($this->livro->editora)) { ?>
			<div class="ficha_livro">
				<h3>Editora:</h3><h4><?php echo $this->livro->editora; ?></h4>
			</div>
			<?php } ?>
			<?php if(isset($this->livro->ano)&& $this->livro->ano > 0) { ?>
			<div class="ficha_livro">
				<h3>Ano:</h3><h4><?php echo $this->livro->ano; ?></h4>
			</div>
			<?php } ?>
			<?php if(isset($this->livro->ano)&& !empty($this->livro->isbn)) { ?>
			<div class="ficha_livro">
				<h3>ISBN:</h3><h4><?php echo $this->livro->isbn; ?></h4>
			</div>
			<?php } ?>
			<?php if(isset($this->livro->ano)&& $this->livro->paginas > 1) { ?>
			<div class="ficha_livro">
				<h3>Páginas:</h3><h4><?php echo $this->livro->paginas; ?></h4>
			</div>
			<?php } ?>
			<?php if(isset($this->livro->ano)&& !empty($this->livro->edicao)) { ?>
			<div class="ficha_livro">
				<h3>Edição:</h3><h4><?php echo $this->livro->edicao; ?></h4>
			</div>
			<?php } ?>
			
		</div>
		<div class="span5 descricao">
			<span class="ld">Livro digital</span>
			<?php if($this->livro->gratis) { ?>
				<span class="ld">gratis</span>
			<?php } ?>
			<h1><?php echo $this->livro->titulo; ?></h1>
			<?php foreach ($this->livro->autores as $k => $v) {?>
				<h4><?php echo $v->nomecompleto ?></h4>
				<?php if(end($this->livro->autores) != $v){ 
					echo '<h4>,</h4>';
				 } ?>
			<?php } ?>				
			<p>SINOPSE</p>
			<hr />
			<p><?php echo $this->livro->sinopse; ?></p>
		</div>
		<div class="span3">
			<div class="preco_livro">
				<?php if($this->livro->gratis) { ?>
					<p>Grátis</p>
					<form method="post" action="<?php echo JRoute::_('index.php?option=com_loja&task=livros.baixarLivroGratis'); ?>">
						<input type="hidden" value="<?php echo $this->livro->id; ?>" name="livro[id]" />
						<input type="submit" value="Baixar livro" class="bt_padrao" />
					</form>
				<? }else { ?>
				<p>R$ <?php echo number_format($this->livro->valor, 2); ?></p>
				<form method="post" action="<?php echo JRoute::_('index.php?option=com_loja&task=livros.adicionarlivro'); ?>">
					<input type="hidden" value="<?php echo $this->livro->id; ?>" name="livro[id]" />
					<input type="submit" value="Adicionar ao carrinho" class="bt_padrao" />
				</form>
				<?php } ?>
			</div>
		</div>
	</div>
</div>