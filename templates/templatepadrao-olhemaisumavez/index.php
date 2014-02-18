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
	
	<!-- Slide fullscreen -->
	<script src="<?php echo $tpath; ?>/assets/js/supersized.3.2.7.min.js" type="text/javascript"></script>
	<script src="<?php echo $tpath; ?>/assets/js/supersized.shutter.js" type="text/javascript"></script>
	
	<link rel="stylesheet" href="<?php echo $tpath; ?>/assets/css/supersized.css">
	<!-- fim slide fullscreen -->
    
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
    <?php if($menu->getActive() == $menu->getDefault()) { ?>
	<script type="text/javascript">
		$(document).ready(function(){
			$.supersized({
				//Funcionalidades
				slide_intervale	: 2000,
				transition			: 1,
				transition_speed	: 700,
				
				//Componentes
				slide_links			: 'black',
				slides				: [ //Imagens do slideshow
											{image: '<?php echo $tpath; ?>/assets/images/01.jpg'},
											{image: '<?php echo $tpath; ?>/assets/images/02.jpg'},
											{image: '<?php echo $tpath; ?>/assets/images/03.jpg'},
											{image: '<?php echo $tpath; ?>/assets/images/04.jpg'}
										]
			});
		});
	</script>
	<?php } ?>
	
    
    <style>
		

		
		
		
		
		
		
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
                 	<li class="item-121"><a href="<?php echo JURI::base() ?>index.php/palestras">Palestras</a></li>
                 	<li class="item-119"><a href="<?php echo JURI::base() ?>index.php/oficinas">Oficinas</a></li>
                    <li class="item-120"><a href="<?php echo JURI::base() ?>index.php/coaching">Coaching</a></li>
                    <li class="item-120"><a href="<?php echo JURI::base() ?>index.php/loja">Loja</a></li>
                    <li class="item-120"><a href="<?php echo JURI::base() ?>index.php/contato">Contato</a></li>
                    <li class="item-122 deeper parent"><a href="<?php echo JURI::base() ?>index.php/blogs">Blogs</a>
                    	<ul class="nav-child unstyled small">
                        	<li class="item-123"><a href="<?php echo JURI::base() ?>index.php/blogs/facetas">Facetas</a></li>
                            <li class="item-124"><a href="<?php echo JURI::base() ?>index.php/blogs/olhe-mais-uma-vez">Olhe mais uma vez</a></li>
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
    		<div class="titulo_inicial container">
    			<h1>Olhe mais uma vez!</h1>
    			<h3>Website do palestrante Moacir Rauber</h3>
    		</div>
    		
    		<div class="ultimas_inicial container">
    			<h1>//Ultimas</h1>
    			<div class="row">
    				<div class="span3 materia_inicial">
    					<jdoc:include type="modules" name="principal_slider_blog_1" />
    				</div>
    				<div class="span3 materia_inicial">
    					<jdoc:include type="modules" name="principal_slider_blog_2" /> 
    				</div>
    				<div class="span3 palestras_inicial">
    					<a href="<?php echo JURI::base() ?>index.php/palestras"><h3>PALESTRAS</h3></a>
    					<p>Conheça os mais diversos temas abordados nas palestras de Moacir</p>
    				</div>
    				<div class="span3 livros_inicial">
    					<img src="<?php echo $tpath; ?>/assets/images/livro.png" />
    					<p>Adquira livros, e-books e áudio-books das palestras de Moacir Rauber.</p>
    					<a href="<?php echo JURI::base() ?>index.php/loja"" class="bt_padrao_b">VISITE A LOJA</a>
    				</div>
    				
    			</div>    			
    		</div>
    		
    		
           	
           	<a id="nextslide" class="load-item"></a>
            
            <div class="desenvolvido">
            	<p>Desenvolvido por <a href="http://www.proiz.com.br" target="_blank"><img src="<?php echo $tpath; ?>/assets/images/logo_proiz_b.png" /></a></p>
            </div>
            
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
    
    
	<!-- Rodape -->
	<div class="rodape_fundo"> 
		<div class="container" />
			<div class="row rodape">
				<div class="span3 newsletter">
					<h2>Receba a newsletter do Olhe mais uma vez!</h2>
					<jdoc:include type="modules" name="newsletter" />
				</div>
				<div class="span2">
					<jdoc:include type="modules" name="menu_inferior1" />
					<ul class="">
                 	<li class="item-121"><a href="<?php echo JURI::base() ?>index.php/palestras">Palestras</a></li>
                 	<li class="item-119"><a href="<?php echo JURI::base() ?>index.php/oficinas">Oficinas</a></li>
                    <li class="item-120"><a href="<?php echo JURI::base() ?>index.php/coaching">Coaching</a></li>
                    <li class="item-120"><a href="<?php echo JURI::base() ?>index.php/livros">Loja</a></li>
                    <li class="item-120"><a href="<?php echo JURI::base() ?>index.php/contato">Contato</a></li>
                    <li class=""><a href="<?php echo JURI::base() ?>index.php/blogs">Blogs</a></li>
                    	
                        	<li class="subitem"><a href="<?php echo JURI::base() ?>index.php/blogs/facetas">Facetas</a></li>
                            <li class="subitem"><a href="<?php echo JURI::base() ?>index.php/blogs/olhe-mais-uma-vez">Olhe mais uma vez</a></li>

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
					<p class="proiz">Desenvolvido por <a href="http://www.proiz.com.br" target="_blank"><img src="<?php echo $tpath; ?>/assets/images/logo_proiz_c.png" /></a></p>
				</div>
			</div>
		</div>
	</div>
	<!-- Fim do rodape -->
	<?php } ?>
  <jdoc:include type="modules" name="debug" /> 
  
</body>

</html>

