<?php defined( '_JEXEC' ) or die; 

$user = JFactory::getUser();

include_once JPATH_THEMES.'/'.$this->template.'/logic.php'; // load logic.php

?><!doctype html>
<!--[if IEMobile]><html class="iemobile" lang="<?php echo $this->language; ?>"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8" lang="<?php echo $this->language; ?>"> <![endif]-->
<!--[if gt IE 8]><!-->  <html class="no-js" lang="<?php echo $this->language; ?>"> <!--<![endif]-->

<head>
  <jdoc:include type="head" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
  <link rel="apple-touch-icon-precomposed" href="<?php echo $tpath; ?>/images/apple-touch-icon-57x57-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $tpath; ?>/images/apple-touch-icon-72x72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $tpath; ?>/images/apple-touch-icon-114x114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $tpath; ?>/images/apple-touch-icon-144x144-precomposed.png">
  <!--[if lte IE 8]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <?php if ($pie==1) : ?>
      <style> 
        {behavior:url(<?php echo $tpath; ?>/js/PIE.htc);}
      </style>
    <?php endif; ?>
  <![endif]-->

	<script src="<?php echo $tpath; ?>/assets/js/jquery.min.1.7.1.js" type="text/javascript"></script>
    <script src="<?php echo $tpath; ?>/assets/js/jquery_ui.1.10.3.js" type="text/javascript"></script>
    <script src="<?php echo $tpath; ?>/assets/js/jquery.easing.1.3.js" type="text/javascript"></script>
	<script src="<?php echo $tpath; ?>/assets/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="<?php echo $tpath; ?>/assets/js/slider/jquery.jcontent.0.8.js" type="text/javascript"></script>
    
	<link rel="stylesheet" href="<?php echo $tpath; ?>/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo $tpath; ?>/assets/css/bootstrap-responsive.min.css">
	<link rel="stylesheet" href="<?php echo $tpath; ?>/assets/css/estilos.css">
    
</head>
  
<body class="<?php /*echo (($menu->getActive() == $menu->getDefault()) ? ('front') : ('page')).' '.$active->alias.' '.$pageclass; */?>">
  	<!-- Cabeçalho -->
	<?php if($menu->getActive() != $menu->getDefault()) { ?>
        <div class="header">
        </div>
        
        <div class="menu_superior">
            <div class="logo">
                <a href="<?php echo JURI::base() ?>"><img src="<?php echo $tpath; ?>/assets/images/logo.png"/></a>
            </div>
            <div class="menu">
                 <jdoc:include type="modules" name="menu_superior" />
                <!--<ul>
                    <li><a href="#">O palestrante</a></li>
                    <li class="active"><a href="#">Oficinas</a></li>
                    <li><a href="#">Coaching</a></li>
                </ul>-->
                <?php if( $user->id) { ?> 
                <ul class="nav menu">
                    <li><a href="/vendas/index.php/component/users/?task=user.logout">Sair</a></li>
                </ul>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
	<!-- Fim do cabeçalho -->
    
    <!-- pagina inicial -->
    <style>
		
	</style>
    
    <script type="text/javascript">
		var velocidade1 = 1000;
		var velocidade2 = 2000;
		$(document).ready(function(){
			//Botao subir
			$('#principal_subir').click(function() {
				$("html, body").animate({ scrollTop: 0 }, 2000);
			  	return false;
			});
			
			
			$('.principal_botao_azul').mouseenter(function() {
				alvo = $(this).data('target');
				$('.principal_'+alvo).fadeIn(velocidade2);
			});
			$('.principal_botao_azul').mouseleave(function() {
				alvo = $(this).data('target');
				if(!$('.principal_'+alvo).is(':hover')) {
					$('.principal_'+alvo).fadeOut(velocidade2);
				}
			});
			$('.ocultar').mouseleave(function() {
				$(this).fadeOut(velocidade2);				
			});
			
			
			$(window).scroll(function(){
				var scrollTop = $(window).scrollTop();
				
		  		if(scrollTop > 300){
					$('.menu_superior_principal').fadeIn(500);
				}else {
					$('.menu_superior_principal').fadeOut(500);
				}
				
				if(scrollTop < 500) {
					$('#mensagem_inicial div').fadeIn(500);
				}else {
					$('#mensagem_inicial div').fadeOut(500);
				}
				
			})
			
			$("div#sliders_blog").jContent({
				orientation: 'horizontal', 
				easing: 'easeOutCirc', 
				duration: 800,
				auto: true,
				direction: 'next',
				pause: 4000,
				pause_on_hover: false,
				height: 340,
				width: 420
			}); 
		});
		$(function() {
			$(window).bind('mousewheel', function(event, delta) {
				a = Math.floor((Math.random()*2)+1);
				//alert(a % 2);
				if(a % 2 == 0) {
					//alert('entrou');
					//return false;	
					
				}
			});
		
			$(window).bind('scroll', function(event) {
				a = Math.random();
				if(a % 2 == 0) {
					return false;	
				}
			});
		});
	
	</script>
    <style>
		#mensagem_inicial {
			width: 350px;
			height: 250px;
			display: inline-block;
			margin-top: 200px;
		}
		#mensagem_inicial img{
			margin-bottom: 10px;	
		}
		#mensagem_inicial h4 {
			font: normal 15px/1.5em "Aller_regular", Tahoma;
			color:#838383;
		}
		#mensagem_inicial h4 b{
			font-weight:normal;
			font-family: "aller_bold";
		}
		
		
		
		


.icone {
    display: inline-block;
    font-size: 0px;
    cursor: pointer;
    /*margin: 15px 30px;
    width: 90px;
    height: 90px;*/
    border-radius: 50%;
    text-align: center;
    position: relative;
    z-index: 10;
    color: #fff;
	background: #449fe5;
}

.icone:after {
    pointer-events: none;
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    -webkit-box-sizing: content-box;
    -moz-box-sizing: content-box;
    box-sizing: content-box;
}

.icone:before {
    background-color: #cccccc;
}

/* Sonar Effect */
.icone-effect .icone {
    background: #449fe5;
    -webkit-transition: -webkit-transform ease-out 0.1s, background .5s;
    -moz-transition: -moz-transform ease-out 0.1s, background .5s;
    transition: transform ease-out 0.1s, background .5s;
}

.icone-effect .icone:after {
    top: 0;
    left: 0;
    padding: 0;
    z-index: -1;
    box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.1);
    opacity: 0;
    /*-webkit-transform: scale(0.9);
    -moz-transform: scale(0.9);
    -ms-transform: scale(0.9);
    transform: scale(0.9);*/
}


/*.icone-effect .icone:hover {
	background: #449fe5;
	/*-webkit-transform: scale(0.93);
	-moz-transform: scale(0.93);
	-ms-transform: scale(0.93);
	transform: scale(0.93);
	color: #fff;
}

.icone-effect .icone:hover {
    -webkit-animation: sonarEffect 2s ease-out 75ms;
    -moz-animation: sonarEffect 2s ease-out 75ms;
    animation: sonarEffect 2s ease-out 75ms;
}


/* Chrome, mobile browser support  */
@-webkit-keyframes sonarEffect {
    0% {
        
    }
    50% {
        box-shadow: 0px 0px 0px 2px rgba(255, 255, 255, 0.1), 0px 0px 5px 16px #c2def4, 0px 0px 10px 15px rgba(255, 255, 255, 0.5);
    }
    100% {
        box-shadow: 0px 0 0 2px rgba(255, 255, 255, 0.1), 0 0 15px 0px #c2def4, 0 0 0 0px rgba(255, 255, 255, 0.5);
        /*-webkit-transform: scale(1.5);*/ 
    }
}

/* Mozilla browser support*/
@-moz-keyframes sonarEffect {
    0% {
        opacity: 0.3;
    }
    40% {
        opacity: 0.5;
        box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.1), 0 0 10px 10px #c2def4, 0 0 0 10px rgba(255, 255, 255, 0.5);
    }
    100% {
        box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.1), 0 0 10px 10px #c2def4, 0 0 0 10px rgba(255, 255, 255, 0.5);
        -moz-transform: scale(1.5);
        opacity: 0;
    }
}


@keyframes sonarEffect {
    0% {
        opacity: 0.3;
    }
    40% {
        opacity: 0.5;
        box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.1), 0 0 10px 10px #c2def4, 0 0 0 10px rgba(255, 255, 255, 0.5);
    }
    100% {
        box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.1), 0 0 10px 10px #c2def4, 0 0 0 10px rgba(255, 255, 255, 0.5);
        transform: scale(1.5);
        opacity: 0;
    }
}

.icone_1 {
    -webkit-animation: sonarEffect 1.8s ease-out 150ms;
    -webkit-animation-iteration-count: infinite;
    -moz-animation: sonarEffect 1.8s ease-out 75ms;
    -moz-animation-iteration-count: infinite;
    -o-animation: sonarEffect 1.8s ease-out 75ms;
    -o-animation-iteration-count: infinite;
}
.icone_0 {
    -webkit-animation: sonarEffect 2.2s ease-out 150ms;
    -webkit-animation-iteration-count: infinite;
    -moz-animation: sonarEffect 2.2s ease-out 75ms;
    -moz-animation-iteration-count: infinite;
    -o-animation: sonarEffect 2.2s ease-out 75ms;
    -o-animation-iteration-count: infinite;
}

		
		
		
		
		
		
	</style>
	
    
    <?php if($menu->getActive() == $menu->getDefault()) { ?>
    	<div class="menu_superior_principal">
            <div class="menu">
                 <jdoc:include type="modules" name="menu_superior" />
                <!--<ul>
                    <li><a href="#">O palestrante</a></li>
                    <li class="active"><a href="#">Oficinas</a></li>
                    <li><a href="#">Coaching</a></li>
                </ul>-->
                <div class="menu_superior_principal" style="display: block;">
            <div class="menu">
                 <ul class="nav menu">
                 	<li class="item-121"><a href="/vendas/index.php/palestras">Palestras</a></li>
                 	<li class="item-119"><a href="/vendas/index.php/oficinas">Oficinas</a></li>
                    <li class="item-120"><a href="/vendas/index.php/coaching">Coaching</a></li>
                    <li class="item-120"><a href="/vendas/index.php/livros">Loja</a></li>
                    <li class="item-120"><a href="/vendas/index.php/contato">Contato</a></li>
                    <li class="item-122 deeper parent"><a href="/vendas/index.php/blogs">Blogs</a>
                    	<ul class="nav-child unstyled small">
                        	<li class="item-123"><a href="/vendas/index.php/blogs/facetas">Facetas</a></li>
                            <li class="item-124"><a href="/vendas/index.php/blogs/olhe-mais-uma-vez">Olhe mais uma vez</a></li>
                        </ul>
                    </li>
                 </ul>

                <!--<ul>
                    <li><a href="#">O palestrante</a></li>
                    <li class="active"><a href="#">Oficinas</a></li>
                    <li><a href="#">Coaching</a></li>
                </ul>-->
                            </div>
        </div>
                <?php if( $user->id) { ?> 
                <ul class="znav menu">
                    <li><a href="/vendas/index.php/component/users/?task=user.logout">Sair</a></li>
                </ul>
                <?php } ?>
            </div>
        </div>
    	<div class="inicial">
            <div class="inicial_cabecalho">
                <h3>Para descobrir este site, é preciso que você</h3>
                <img src="<?php echo $tpath; ?>/assets/images/logo2.png"/>  
            </div>
            <div id="mensagem_inicial">
                <div>
                    <img src="<?php echo $tpath; ?>/assets/images/seta_baixo.png"/>
                    <hr class="linha_cinza" />
                    <h4>Siga para baixo para conhecer <br/> o site do palestrante e escritor <b>Moacir Rauber.</b></h4>                    
                </div>
            </div>
            
            <div class="inicial_principal">
            	<div class="principal_div1">
                	<div class="container_loja ">
                        <div class="principal_botao_azul icone-wrap icone-effect" data-target="loja">
                            <img class="icone" src="<?php echo $tpath; ?>/assets/images/principal_circulo_cinza.png"/>
                        </div>
                        <div class="principal_loja ocultar">
                            
                            <a href="<?php echo JURI::base() ?>livros"><img src="<?php echo $tpath; ?>/assets/images/principal_loja.png"/></a>
                            <a href="<?php echo JURI::base() ?>livros"><h2>Loja</h2></a>
                            <span>Aquira livros, e-books e audio-books.</span>
                        </div>
                    </div>
                    
                    <div class="container_loja">
                        <div class="principal_botao_azul" data-target="palestras">
                            <img style="width:50px;" src="<?php echo $tpath; ?>/assets/images/principal_circulo_cinza.png"/>
                        </div>
                        <div class="principal_palestras ocultar">
                             <a href="<?php echo JURI::base() ?>palestras"><h2>Palestras</h2></a>
                            <span>Motivação, vendas e gestão  de pessoas. Estes são apenas alguns dos temas abordados por Moacir Rauber em palestras estruturadas para atender a demanda de grupos diferentes.</span>
                        </div>
                    </div>
                </div>
               
            	<div class="principal_div2">
                    <jdoc:include type="modules" name="principal_slider_blog" /> 
                </div>
               
               
                <div class="principal_div3">
                	<div class="container_coaching">
                        <div class="principal_botao_azul" data-target="coaching">
                            <img src="<?php echo $tpath; ?>/assets/images/principal_circulo_cinza.png"/>
                        </div>
                        <div class="principal_coaching ocultar">
                             <a href="<?php echo JURI::base() ?>coaching"><h2>Coaching</h2></a>
                            <span>Um caminho para transformar potencial em talento!</span>
                        </div>
                    </div>
                    
                    <div class="container_coaching">
                        <div class="principal_botao_azul" data-target="oficinas">
                            <img src="<?php echo $tpath; ?>/assets/images/principal_circulo_cinza.png"/>
                        </div>
                        <div class="principal_oficinas ocultar">
                             <a href="<?php echo JURI::base() ?>oficinas"><h2>Oficinas</h2></a>
                            <span>Excelentes ferramentas para o treinamento e preparação de equipes e grupos de trabalho.</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="principal_rodape">
            	<a href="#" id="principal_subir"><img src="<?php echo $tpath; ?>/assets/images/seta_up.png"/></a>
            </div>
            <div class="desenvolvido">
            	<p>Desenvolvido por <img src="<?php echo $tpath; ?>/assets/images/logo_proiz.png" /></p>
            </div>
            
        </div>
        <script>
			$(document).ready(function() {
				$(window).bind('scroll',function(e){
					parallaxScroll();
				});
				 
				function parallaxScroll(){
					var scrolled = $(window).scrollTop();
					$('#paralax-bg1').css('top',(0-(scrolled*.25))+'px');
					$('#paralax-bg2').css('top',(0-(scrolled*.5))+'px');
					//$('#parallax-bg3').css('top',(0-(scrolled*.75))+'px');
				}	
			})
		
		</script>
        
        <div id="paralax-bg1"> 
        </div>        
        <div id="paralax-bg2">
        </div>
    <?php }else { ?>
    
    <!-- fim da pagina inicial -->
    
	<div id="corpo">
		<div class="container">
			<div class="row">
			<jdoc:include type="message" />
			</div>
		</div>
		<jdoc:include type="component" />
	</div>
    
    <?php } ?>
	<!-- Rodape -->
	<footer class="rodape_fundo"> 
		<div class="container" />
			<div class="row rodape">
				<div class="span3 newsletter">
					<h2>Receba a newsletter do Olhe mais uma vez!</h2>
					<jdoc:include type="modules" name="newsletter" />
				</div>
				<div class="span2">
					 <jdoc:include type="modules" name="menu_inferior1" />
					<ul>
						<li><a href="/index.php">Pagina inicial</a></li>
						<!--<li>O Palestrante</li>
						<li>Oficinas</li>
						<li>Coaching</li>
						<li>Blogs</li>
						<li class="subitem">Facetas</li>
						<li class="subitem">Remar é preciso</li>	-->						
					</ul>
				</div>
				<div class="span2">
					<jdoc:include type="modules" name="menu_inferior2" />
					<ul>
						<li>Loja</li>
						<li class="subitem"><a href="index.php/livros">Livros</a></li>
					</ul>
					
				</div>
				<div class="span2">
					 <jdoc:include type="modules" name="redes_sociais" />
					<h2>Nas redes sociais</h2>
				</div>
				<div class="span3">
					<p class="proiz">Desenvolvido por <a href="http://www.proiz.com.br" target="_blank">PROIZ</a></p>
				</div>
			</div>
		</div>
	</footer>
	<!-- Fim do rodape -->
	
  <jdoc:include type="modules" name="debug" /> 
  
</body>

</html>

