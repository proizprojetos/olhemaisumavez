<?php

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
?>
<div class="container coaching">
	<div class="row">
		
        <div class="span7">
        	<div class="topoesquerda_coaching">
            	<h2>Movendo pessoas e organizações</h2>
                <img src="<?php echo JURI::root() ?>components/com_loja/views/coaching/images/topo_coaching.png" alt="" />
                <h3>Um caminho para transformar potencial em talento</h3>
            </div>
        </div>
        
		<div class="span4">
        	<div class="topodireita_coaching">
            	<h3>Quem pode ser seu Coach?</h3>
            	<p><b>Moacir Rauber</b>, formação internacional em Coaching Executivo Organizacional com ênfase na Metodologia Ontológica Transformacional reconhecido pela FIACE (Federación Ibero Americana de Coaching Ejecutivo). O trabalho se desenvolve tendo em mente o uso de técnicas que proporcionem ao coachee a possibilidade de redesenhar as formas de intervenção no seu ambiente, promovendo e implementando uma nova realidade. 
                </p>
            </div>
        </div>
        <div class="span12">
        	<hr class="linha_azul2" />
        </div>
    </div>
    
    <div class="row">
     	<div class="span7 corpoesquerda_coaching">
        	<h2>O Coaching pode ajudar!</h2>
            <div class="texto_coaching">
                <p>
                    Muitos de nós estamos num determinado ponto da vida acreditando que poderíamos ou mereceríamos estar em outro. O primeiro é o nosso Potencial, pois representa tudo o que nós cremos que podemos ser. O segundo pode ser o nosso Talento, que é transformar tudo isso em realidade. Colocar o potencial a serviço da comunidade é exibir o nosso talento. 
                    <span>
                        <h4>Pergunte-se</h4>
                        <p>Você quer transformar o seu potencial em talento?</p>
                    </span>
                    <span> 
                        <h4>O coaching pode ajudá-lo a encontrar o caminho!</h4>
                    </span>
                </p>
             </div>
             <button id="opener" class="bt_padrao bt_saibamais_coaching">SAIBA MAIS</button>
        </div>        
        <div id="dialog">
       		<div class="info_coaching">
            	<h1><?php echo $this->item->titulo ?></h1>
                <?php echo $this->item->texto ?>
            </div> 
        </div>
        <div class="span4">
        	 <div class="formulario_coaching">
                <div class="formulario">
                    <h3>Quer conhecer mais sobre coaching? Mantenha contato.</h3>
                    <form action="#" method="post" id="formulario_coaching" >
                    	<input type="hidden" name="assunto" value="Coaching" />
                        <div class="formulario_entrada texto">
                            <input type="text" name="nome" placeholder="Seu nome" style="width:75%"/>
                        </div>
                        
                        <div class="formulario_entrada">
                            <input type="text" name="email" placeholder="Seu email" style="width:75%"/>
                        </div>
                        
                        <div class="formulario_entrada">
                            <input type="text" name="telefone" placeholder="Telefone"/>
                        </div>
                        <div class="formulario_entrada">
                            <textarea name="mensagem" placeholder="Mensagem" rows="4" ></textarea>
                        </div>
                        <div class="formuario_enviar">
                            <input type="submit" value="ENVIAR" class="bt_padrao" id="submit"/>
                        </div>
                    </form>
                </div>
            </div>
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