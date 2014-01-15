<?php 

/**
* @Copyright Copyright (C) 2013 - JoniJnm.es
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined( '_JEXEC' ) or die( 'Restricted access' ); 

require_once (JPATH_SITE.'/components/com_content/helpers/route.php');

?>

<?php foreach($lista as $value => $item) { 
	$images  = json_decode($item->images);
	$url = ContentHelperRoute::getArticleRoute($item->id.":".$item->alias, $item->catid.":".$item->calias);
	$link = JRoute::_($url);
	//print_r($item->co);
	$date = JFactory::getDate();
	$now = method_exists($date, 'toMySQL') ? $date->toMySQL() : $date->toSql();
	echo $now- $item->co;
?>

<div class="container_blog blog_<?php echo $value % 2?>">    	
    <div class="principal_botao_azul icone-wrap icone-effect" data-target="blog_<?php echo $item->id ?>">
        <img class="icone icone_<?php echo $value % 2?>" src="<?php echo JURI::root();?>principal_circulo_cinza.png">
    </div>
    
    <div class="principal_blog_<?php echo $item->id ?> ocultar">
        <div class="">
            <div class="principal_capa_blog">
               <img src="<?php echo JURI::root().$images->image_intro ?>" />
            </div>
            <div class="principal_blog_texto">
                <a href="<?php echo $link; ?>"><h2><?php echo $item->title; ?></h2></a>
                <span><?php echo substr($item->introtext, 0, 100);  ?>. <a href="<?php echo $link; ?>">Ler mais.</a></span>
            </div>
       </div>
    </div>
</div><?php } ?>