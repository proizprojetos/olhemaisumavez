<?php

defined('_JEXEC') or die('Acesso restrito');

$fundo = 0;

?>
	<div class="livro_fundo0 livro">
		<div class="container" style="position:relative ;">
			<div class="row">
				<?php foreach ($this->listalivros as $key => $value) { ?>
					<div class="span3">
						<div class="imagem_livro">
							<a href="<?php echo JRoute::_('index.php?option=com_loja&view=livros&layout=detalhe&idlivro='.$value->id); ?>">
								<img src="<?php echo $value->imagem_capa ?>" alt="" />
							</a>
						</div>
						<h2>Ficha técnica</h2>
						<hr />
						<?php if($value->combo) { ?>
							<span class="ld">Combo especial</span>
						<?php }
							if($value->fretegratis) { ?>
							<span class="ld">Frete gratis</span>
						<?php } ?>
						<div class="ficha_livro">
							<a href="<?php echo JRoute::_('index.php?option=com_loja&view=livros&layout=detalhe&idlivro='.$value->id); ?>">
								<h2><?php echo $value->titulo; ?></h2>
							</a>
						</div>					
						<?php if(isset($value->editora)) { ?>
						<div class="ficha_livro">
							<h3>Editora:</h3><h4><?php echo $value->editora; ?></h4>
						</div>
						<?php } ?>
						<?php if(isset($value->ano)&& $value->ano > 0) { ?>
						<div class="ficha_livro">
							<h3>Ano:</h3><h4><?php echo $value->ano; ?></h4>
						</div>
						<?php } ?>
						<?php if(isset($value->ano)&& !empty($value->isbn)) { ?>
						<div class="ficha_livro">
							<h3>ISBN:</h3><h4><?php echo $value->isbn; ?></h4>
						</div>
						<?php } ?>
						<?php if(isset($value->ano)&& $value->paginas > 1) { ?>
						<div class="ficha_livro">
							<h3>Páginas:</h3><h4><?php echo $value->paginas; ?></h4>
						</div>
						<?php } ?>
						<?php if(isset($value->ano)&& !empty($value->edicao)) { ?>
						<div class="ficha_livro">
							<h3>Edição:</h3><h4><?php echo $value->edicao; ?></h4>
						</div>
						<?php } ?>
						
					</div>
				<?php } ?>
				
				
				<?php foreach ($this->listaebooks as $key => $value) { ?>
					<div class="span3">
						<div class="imagem_livro">
							<a href="<?php echo JRoute::_('index.php?option=com_loja&view=livros&layout=detalheebook&idlivro='.$value->id); ?>">	
								<img src="<?php echo $value->imagem_capa ?>" alt="" />
							</a>
						</div>
						<h2>Ficha técnica</h2>
						<hr />
						<span class="ld">Livro digital</span>
						<?php if($value->gratis) { ?>
							<span class="ld">gratis</span>
						<?php } ?>
						<div class="ficha_livro">
							<h2><?php echo $value->titulo; ?></h2>
						</div>
						<?php if(isset($value->editora)) { ?>
						<div class="ficha_livro">
							<h3>Editora:</h3><h4><?php echo $value->editora; ?></h4>
						</div>
						<?php } ?>
						<?php if(isset($value->ano)&& $value->ano > 0) { ?>
						<div class="ficha_livro">
							<h3>Ano:</h3><h4><?php echo $value->ano; ?></h4>
						</div>
						<?php } ?>
						<?php if(isset($value->ano)&& !empty($value->isbn)) { ?>
						<div class="ficha_livro">
							<h3>ISBN:</h3><h4><?php echo $value->isbn; ?></h4>
						</div>
						<?php } ?>
						<?php if(isset($value->ano)&& $value->paginas > 1) { ?>
						<div class="ficha_livro">
							<h3>Páginas:</h3><h4><?php echo $value->paginas; ?></h4>
						</div>
						<?php } ?>
						<?php if(isset($value->ano)&& !empty($value->edicao)) { ?>
						<div class="ficha_livro"> 
							<h3>Edição:</h3><h4><?php echo $value->edicao; ?></h4>
						</div>
						<?php } ?>
						
					</div>
				<?php } ?>
			</div>
		</div>
	</div>