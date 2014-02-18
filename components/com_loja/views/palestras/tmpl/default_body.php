<?php

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
?>
<div class="container palestras">
	<div class="row">
		   <div class="span12">
           		<div class="palestra_mensagem">
                    <p>"<?php echo $this->item->mensagem_inicial; ?>"</p>
                    <h5><?php echo $this->item->autor_msg_inicial; ?></h5>
                </div>
                 <hr class="linha_azul2"/>
           </div>
    </div>
    
    <div class="row corpo_palestras">
    	<div class="span6">
        	<div class="grupos_palestras">
            	
                <div class="grupo">
                		<img src="<?php echo JURI::root() ?>components/com_loja/views/palestras/images/01.png" alt="" />
                		<h2>Vendas</h2>
                    <a href="#grupo_info-1">SAIBA MAIS</a>
                </div>
                
                <div class="grupo_info" id="grupo_info-1">
                	<div class="grupo_info_texto">
	                	<h2><?php echo $this->item->vendas_titulo; ?></h2>
						<?php echo $this->item->vendas_texto; ?>
						<?php if(!empty($this->item->vendas_link) || $this->item->vendas_link != '') { ?>
							<div class="bt_mais">
	                    		<a target="_blank" href="<?php echo $this->item->vendas_link; ?>">MAIS INFORMAÇÕES</a>
	                    	</div>
						<?php } ?>
	                	<div class="bt_voltar_palestras">
	                    	<a href="#voltar">VOLTAR</a>
	                    </div>
	                 </div>
                </div>
                
                
                <div class="grupo">
                	<img src="<?php echo JURI::root() ?>components/com_loja/views/palestras/images/02.png" alt="" />
                    <h2>Superação e motivação</h2>
                    <a href="#grupo_info-2">SAIBA MAIS</a>
                </div>  
                <div class="grupo_info" id="grupo_info-2">
                	<div class="grupo_info_texto">
	                	<h2><?php echo $this->item->superacao_titulo; ?></h2>
							<?php echo $this->item->superacao_texto; ?>
							<?php if(!empty($this->item->superacao_link) || $this->item->superacao_link != '') { ?>
								<div class="bt_mais">
		                    		<a target="_blank" href="<?php echo $this->item->superacao_link; ?>">MAIS INFORMAÇÕES</a>
		                    	</div>
							<?php } ?>
	                	<div class="bt_voltar_palestras">
	                    	<a href="#voltar">VOLTAR</a>
	                    </div>
                    </div>
                </div>
                
                <div class="grupo">
                	<img src="<?php echo JURI::root() ?>components/com_loja/views/palestras/images/03.png" alt="" />
                    <h2>Empreenderorismo</h2>
                    <a href="#grupo_info-3">SAIBA MAIS</a>
                </div>  
                <div class="grupo_info" id="grupo_info-3">
                	<div class="grupo_info_texto">
	                	<h2><?php echo $this->item->empreendedorismo_titulo; ?></h2>
							<?php echo $this->item->empreendedorismo_texto; ?>
							<?php if(!empty($this->item->empreendedorismo_link) || $this->item->empreendedorismo_link != '') { ?>
								<div class="bt_mais">
		                    		<a target="_blank" href="<?php echo $this->item->empreendedorismo_link; ?>">MAIS INFORMAÇÕES</a>
		                    	</div>
							<?php } ?>
	                	<div class="bt_voltar_palestras">
	                    	<a href="#voltar">VOLTAR</a>
	                    </div>
                    </div>
                </div>
                
                
                   
                <div class="grupo">
                	<img src="<?php echo JURI::root() ?>components/com_loja/views/palestras/images/04.png" alt="" />
                    <h2>Inclusão e diversidade</h2>
                    <a href="#grupo_info-4">SAIBA MAIS</a>
                </div>
                <div class="grupo_info" id="grupo_info-4">
                	<div class="grupo_info_texto">
	                	<h2><?php echo $this->item->inclusao_titulo; ?></h2>
							<?php echo $this->item->inclusao_texto; ?>
							<?php if(!empty($this->item->inclusao_link) || $this->item->inclusao_link != '') { ?>
								<div class="bt_mais">
		                    		<a target="_blank" href="<?php echo $this->item->inclusao_link; ?>">MAIS INFORMAÇÕES</a>
		                    	</div>
							<?php } ?>
	                	<div class="bt_voltar_palestras">
	                    	<a href="#voltar">VOLTAR</a>
	                    </div>
	                 </div>
                </div>
                
                
                <div class="grupo">
                	<img src="<?php echo JURI::root() ?>components/com_loja/views/palestras/images/05.png" alt="" />
                    <h2>SIPAT</h2>
                    <a href="#grupo_info-5">SAIBA MAIS</a>
                </div>  
                <div class="grupo_info" id="grupo_info-5">
                	<div class="grupo_info_texto">
	                	<h2><?php echo $this->item->sipat_titulo; ?></h2>
							<?php echo $this->item->sipat_texto; ?>
							<?php if(!empty($this->item->sipat_link) || $this->item->sipat_link != '') { ?>
								<div class="bt_mais">
		                    		<a target="_blank" href="<?php echo $this->item->sipat_link; ?>">MAIS INFORMAÇÕES</a>
		                    	</div>
							<?php } ?>
	                	<div class="bt_voltar_palestras">
	                    	<a href="#voltar">VOLTAR</a>
	                    </div>
                    </div>
                </div>
                
                
                <div class="grupo">
                	<h3>Tem interesse em alguma das palestras?</h3>
                    
                    <a href="<?php echo JURI::root() ?>index.php/contato">Entre em contato</a>
                </div>      	
            </div>
        </div>
        
        <div class="span6">
        	<div class="palestrante">
           		<div class="palestrante_info">
                    <h2>O Palestrante</h2>
                    <h3>Moacir Jorge Rauber</h3>
                    <p><?php echo $this->item->texto_palestrante; ?></p>
                    <button id="palestrante_info">Mais informações</button>
                </div>
                <div id="dialog" class="dialog">
	                <div class="fotos_palestrante">
	                	<img src="<?php echo JURI::root() ?>components/com_loja/views/palestras/images/palestrante_foto1.png" alt="" />
	                  	<img src="<?php echo JURI::root() ?>components/com_loja/views/palestras/images/palestrante_foto2.png" alt="" />
	                    <img src="<?php echo JURI::root() ?>components/com_loja/views/palestras/images/palestrante_foto3.png" alt="" />                
	                </div>
	                <div class="info_palestras">
	                    <?php echo $this->item->texto_maisinformacoes; ?>
	                </div> 
            	</div>
                <div class="palestrante_foto">
                	 <img src="<?php echo JURI::root() ?>components/com_loja/views/palestras/images/palestrante_foto.png" alt=""/>
                </div>
            </div> 
            <div class="formato_palestras">
            	<h2>Formato das Palestras</h2>
                <p>Duração: De 1 a 2 horas</p>
                <h4>As palestras podem ser dirigidas a diferentes públicos, dependendo de contato prévio para ajustar o formato as suas necessidades.
                </h4>
                <div class="grupo_palestras">
                	<span>
                    	<h3>Grupo de trabalho</h3>
                        <p>Dirigido para um público de até 30 pessoas, adotando uma dinâmica de interação e participação.</p>
                    </span>
                    <span>
                    	<h3>Palestra</h3>
                        <p>
                        	Dirigido para um público sem limite de número, constituindo-se como um evento ágil e dinâmico em que a interação com os participantes é uma constante.
                        </p>
                    </span>
                </div>
            </div>
        </div>
     </div>
     <div class="row">
     	<div class="span12 palestra_mensagemfinal" style="text-align:center">
         	<img src="<?php echo JURI::root() ?>components/com_loja/views/palestras/images/palestras_competencias.png" />
         	<br/><br/>
         	<p>As palestras dão ênfase nas competências individuais que são as atitudes postas a serviço de um propósito.</p>
        </div>
    </div>
    <?php if(!empty($this->comentarios)) { ?>
	    <div class="row">
	    	<hr class="linha_azul2">
		   <div class="slider-texto">
				<h2> Comentários de quem já participou</h2>	
				<div class="liquid-slider" id="slider-id">
					<?php foreach ($this->comentarios as $key => $value) { ?>
						<div>
							<span class="msg-slider">
								<?php echo $value->comentario ?>
							</span>
							<p><?php echo $value->autor ?></p> 
						</div>
					<?php } ?>	
				</div>
			</div>
			<hr class="linha_azul2">
	    </div>
    <?php } ?>
        
</div>
