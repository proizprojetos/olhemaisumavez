<?php

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
?>
<div class="container loja_oficinas">
	<div class="row">
        <div class="span12 capa_oficinas">
            <img src="<?php echo JURI::root() ?>components/com_loja/views/oficinas/images/capa_oficinas.png" alt="" />
        </div>
    </div>
    <div class="row corpo_oficinas">
    	<div class="span6 tabs_oficinas">
        	
            <ul class="intro_oficina">
            	<?php foreach($this->oficinas as $oficina) {?>
                	<li><a href="#tab-<?php echo $oficina->id ?>"><?php echo $oficina->titulo ?></a></li>
                <?php } ?>
            </ul>
            
            <?php foreach($this->oficinas as $oficina) {?>
            	<div id="tab-<?php echo $oficina->id ?>" class="tab_oficina">
            		<div class="intro_oficina">
                        <p><?php echo $oficina->intro; ?></p>
                    </div>
                    <div class="texto_oficina">
                    	 <p><?php echo $oficina->texto; ?></p>   
                    </div>
                    <?php if(!empty($oficina->url_proposta)) { ?>
                    	<div class="proposta_oficina">
                            <hr class="linha_azul" />
                            <img src="<?php echo JURI::root() ?>components/com_loja/views/oficinas/images/seta_oficinas.png" alt="" />
                            <a href="<?php echo $oficina->url_proposta ?>" target="_new">Faça o download da proposta detalhada da oficina</a>
                            <hr class="linha_azul" />
                        </div>
                    <?php } ?>
                    
                     <div class="palestrantes_oficina">
                        <h4>Organizada por:</h4>
                        <h3><?php echo $oficina->organizada; ?></h3>
                    </div>
                    
                    <div class="duracao_oficina">
                        <h4>Duração:</h4>
                        <h3><?php echo $oficina->duracao; ?></h3>
                    </div>
                    <div class="livros_oficina">
                        <div class="livro_oficina">
                            <h4>Adquira o livro base desta oficina:</h4>                	
                        </div>            	
                    </div>
                </div>
            <?php } ?>         
        </div>    
        
        <div class="span6 formularios_oficinas">
        	<div class="formulario">
            	<h3>Se você tiver interesse em alguma das oficinas, entre em contato para mais informações.</h3>
                <h4>Selecione as oficinas que você gostaria de obter mais informações</h4>
                <form action="#" method="post" id="formulario_oficina" >
                	<input type="hidden" name="assunto" value="Oficinas" />
                    <div class="radio_oficinas">
                       <?php foreach($this->oficinas as $oficina) {?>
                       	 <div class="check_item">
                            <input type="checkbox" name="oficina" value="<?php echo $oficina->titulo ?>"> <?php echo $oficina->titulo ?><br>
                          </div>
                    <?php } ?>
                    </div>
                    
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