<?php

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
?>

<div class="container loja_oficinas">
	<div class="row">
        <div class="span12 tab-galeria">
            <ul class="galeria_menu">
            	<li><a href="#tab-fotos">Fotos</a></li>
            	<li><a href="#tab-videos">Videos</a></li>
            </ul>
            <div id="tab-fotos">
            	<div class="">
	            	<div class="jcarousel">
					    <ul>
					    	<?php foreach ($this->categorias as $key => $value) { 
					    		if($value->tipo == 'FOT') { ?>
								 <li>
						        	<div class="item"> 
						        		<img src="<?php echo JURI::root().$value->url_capa_categoria ?>" alt="" data-id="<?php echo $value->id; ?>" />
						        		<p><?php echo $value->nome ?></p>
						        	</div>				        	
						        </li>
							<?php }
							 } ?>
					        
					    </ul>
					</div>
					<a class="jcarousel-prev" href="#"></a>
	    			<a class="jcarousel-next" href="#"></a>
    				<hr class="linha_azul" style="margin-top: 0px"/>
    				
    				
    				
    				<div class="slider-fotos">
    					<div id="slideshow"></div>
    					<div id="caption"></div>
    					<div class="nav-controls"></div>
    					<div id="controls" class="controls"></div>
    					<div id="corpo_slider">
	    					<div class="span3 album_titulo">
	    						<?php
									$flag = $this->categorias[0]->id;
									foreach ($this->categorias as $value) { ?>
									<div class="<?php echo ($value->id !== $flag ? 'item-ocultar' : '') ;?>"
										data-cat="<?php echo $value->id ?>">
			    						<h2><?php echo $value->nome ?></h2>
			    						<p><?php echo $value->descricao ?></p>
			    					</div>						
	    						<?php } ?>
	    					</div>
	    					<div class="span8">
	    						<div id="thumbs">
									<ul class="thumbs noscript">
										<?php
											$flag = $this->categorias[0]->id;
											foreach ($this->categorias as $pai) {
												if($pai->tipo == 'FOT') { ?>
												<?php foreach ($pai->imagens as $key => $value) { ?>
													<li class="item-slider <?php echo ($value->id_categoria !== $flag ? 'item-ocultar' : '') ;?>"
														data-cat="<?php echo $value->id_categoria ?>">
														<a class="thumb" name="optionalCustomIdentifier"
											        	href="<?php echo JURI::root().$value->url ?>" title="<?php echo $value->titulo ?>">
											            <img src="<?php echo JURI::root().$value->url ?>" 
											            alt="<?php echo $value->titulo ?>" />
											        </a>
													</li>
													
											<?php } 
											 } 
										}?>
									</ul>
								</div>
	    						
	    					</div>
    					</div>
    				</div>    				
    			</div>
            </div>
            <div id="tab-videos">
            	<div class="jcarousel">
				    <ul>
				    	<?php foreach ($this->categoriasvideos as $key => $value) { 
				    		if($value->tipo == 'VID') { ?>
							 <li>
					        	<div class="item"> 
					        		<img src="<?php echo JURI::root().$value->url_capa_categoria ?>" alt="" data-id="<?php echo $value->id; ?>" />
					        		<p><?php echo $value->nome ?></p>
					        	</div>				        	
					        </li>
						<?php }
						 } ?>
				        
				    </ul>
				</div>
				<a class="jcarousel-prev" href="#"><</a>
    			<a class="jcarousel-next" href="#">></a>
				<hr class="linha_azul" style="margin-top: 0px"/>
				
				<div class="container_video">
					<div class="video">
						<iframe width="900" height="480"
							src="#"
							frameborder="0" allowfullscreen>
						</iframe>

						
					</div>
					<div id="corpo_video">
	    					<div class="span3 album_titulo">
	    						<?php
									$flag = $this->categoriasvideos[0]->id;
									foreach ($this->categoriasvideos as $value) { ?>
									<div class="<?php echo ($value->id !== $flag ? 'item-ocultar' : '') ;?>"
										data-cat="<?php echo $value->id ?>">
			    						<h2><?php echo $value->nome ?></h2>
			    						<p><?php echo $value->descricao ?></p>
			    					</div>						
	    						<?php } ?>
	    					</div>
	    					<div class="span8">
	    						<div id="thumbs">
									<ul class="thumbs noscript">
										<?php
											$flag = $this->categoriasvideos[0]->id;
											foreach ($this->categoriasvideos as $pai) {
												if($pai->tipo == 'VID') { ?>
												<?php foreach ($pai->imagens as $key => $value) { ?>
													<li class="item-slider-video item-slider <?php echo ($value->id_categoria !== $flag ? 'item-ocultar' : '') ;?>"
														data-cat="<?php echo $value->id_categoria ?>">
														<a class="thumb" name="optionalCustomIdentifier"
											        	href="http://www.youtube.com/embed/<?php echo $value->url ?>" title="<?php echo $value->titulo ?>">
											            <img src="http://img.youtube.com/vi/<?php echo $value->url ?>/hqdefault.jpg" 
											            alt="<?php echo $value->titulo ?>" />
											        </a>
													</li>
													
											<?php } 
											 } 
										}?>
										<!--<li class="item-slider item-slider-video">
											<a class="thumb" name="optionalCustomIdentifier"
								        	href="http://www.youtube.com/embed/atM3ZhF8MVs" title="">
								           		<img src="http://img.youtube.com/vi/atM3ZhF8MVs/default.jpg" alt="" />
								        	</a>
										</li>
										<li class="item-slider item-slider-video">
											<a class="thumb" name="optionalCustomIdentifier"
								        	href="http://www.youtube.com/embed/jiwuQ6UHMQg" title="">
								           		<img src="" alt="" />
								        	</a>
										</li>
										-->
									</ul>
								</div>
	    						
	    					</div>
    					</div>					
				</div>
				            	
            </div>
        </div>
    </div>
   
</div>