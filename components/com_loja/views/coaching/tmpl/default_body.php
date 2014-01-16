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
            	<h1>COACHING</h1>
                <h2>Um caminho para transformar potencial em talento!</h2>
                <p>
                	Muitos de nós continuamos a nos debater em meio a um turbilhão de desejos, anseios, necessidades e oportunidades. Muitas vezes, não conseguimos distinguir um do outro. Aparece a ansiedade. Pode ser o medo... Entre tantos caminhos qual seguir? Essas emoções se revelam contraproducentes e, às vezes, paralisantes. Muitas pessoas terminam por não seguir caminho nenhum.<br /><br />

A situação é muito mais comum do que se pensa. Atinge as pessoas independentemente de seu grau de escolaridade, de sua posição hierárquica numa organização ou outra condição qualquer que possa diferenciar as pessoas. É nesse ponto que o coaching pode fazer a diferença.<br /><br />


O Coaching pode ser entendido como um processo que auxilia as pessoas a se deslocarem do Ponto A ao Ponto B. No processo as pessoas podem descobrir onde estão, aonde querem ir e o caminho a ser percorrido. Com isso, movem-se as pessoas e as organizações.
                </p>
               <h3> Qual é o meu Ponto A? Onde estou?  Quais as minhas verdadeiras capacidades? </h3>
               <p>
					Podem parecer perguntas simples, mas lamentavelmente a grande maioria das pessoas terminam os seus dias sem jamais tê-las respondido.
				</p>
                <h3>Mais difícil ainda...<br />Qual é o meu Ponto B? Para onde quero ir? </h3>
                <p>Posso transformar tudo aquilo que acredito que posso ser em realidade? 
                Tenho disposição para pagar o preço para chegar ao meu Ponto B? 
                É importante saber se tenho disposição em fazer o que deve ser feito para ser aquilo 
                que se pretendo ser...
                
                Muitos de nós, não respondemos a essas perguntas. Não sabemos para onde queremos ir e queremos ir. Pior, não sabemos para onde estamos indo... Apenas vamos.
                
                São indagações que podem ajudar a cada um a descobrir quais os verdadeiros potenciais e aqueles que realmente pretende transformar em talento. Assim, durante o processo as pessoas as aprendem a fazer as distinções adequadas que permitem separar os desejos, os anseios, as necessidades e as oportunidades que podem conduzi-las a se desenvolver de forma a  que possam estar onde sempre quiseram.
                </p>
                <h3>E você: <br />Onde você está? Aonde você quer chegar?</h3>
                <p>Você quer encontrar um caminho para transformar o seu potencial em talento?
                    Você realmente acredita em tudo aquilo que pode ser ou que já poderia ter sido e ainda não foi?
				</p>
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
    
</div>