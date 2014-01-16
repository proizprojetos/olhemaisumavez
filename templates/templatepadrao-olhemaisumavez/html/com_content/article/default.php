<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

// Create shortcuts to some parameters.
require_once(dirname(__FILE__).'/helper.php');
$params  = $this->item->params;
$images  = json_decode($this->item->images);
$urls    = json_decode($this->item->urls);
$canEdit = $params->get('access-edit');
$user    = JFactory::getUser();
$info    = $params->get('info_block_position', 0);
JHtml::_('behavior.caption');
?>
<div class="blog_titulo">
	<div class="container">
    	<div class="span12 blog_titulo_corpo">
        	<img src="<?php echo JURI::base().'templates/'.JFactory::getApplication()->getTemplate();; ?>/html/com_content/images/topo_blog.png"/>
            <h1><?php echo $this->item->category_title; ?></h1>
        </div>
    </div>    
</div>
<div class="container">
    <div class="item-page<?php echo $this->pageclass_sfx?> span7">
    	<div class="blog_item_omuv ">
                <div class="blog_item_publicacao">
                    <h3><?php  echo date( "d", strtotime($this->item->publish_up)) ?></h3>
                    <h4><?php echo (JText::_(date( "F", strtotime($this->item->publish_up)).'_SHORT')); ?></h4>
                </div>
                <div class="blog_item_compartilhar">
                 <?php 
					$url = ContentHelperRoute::getArticleRoute($this->item->id.":".$this->item->alias, $this->item->catid.":".$this->item->category_alias);
					$link = JRoute::_($url);
					
				?>
                <div class="topo_compartilhar">
                    <div class="bt">
                        <a href="<?php echo 'http://www.olhemaisumavez.com.br'.$link ?>" class="twitter-share-button">Tweet</a>
                        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                    </div>
                    <div class="bt">
                        <div id="fb-root"></div>
                        <script>(function(d, s, id) {
                          var js, fjs = d.getElementsByTagName(s)[0];
                          if (d.getElementById(id)) return;
                          js = d.createElement(s); js.id = id;
                          js.src = "//connect.facebook.net/pt_BR/all.js#xfbml=1";
                          fjs.parentNode.insertBefore(js, fjs);
                        }(document, 'script', 'facebook-jssdk'));</script>
                        <div class="fb-like" data-href="<?php echo 'http://www.olhemaisumavez.com.br'.$link ?>" data-colorscheme="light" data-layout="button_count" data-action="like" data-show-faces="false" data-send="false">
                        </div>
                    </div>
                    
                    <div class="bt">
                        <!-- Posicione esta tag onde você deseja que o botão +1 apareça. -->
                        <script type="text/javascript" src="http://apis.google.com/js/plusone.js"></script>
                        <!-- Coloque este código no lugar que voce quer que o +1 apareca -->
                        <g:plusone size='medium'>								</g:plusone>
                        
                        <!-- Posicione esta tag depois da última tag do botão +1. -->
                        <script type="text/javascript">
                          window.___gcfg = {lang: 'pt-BR'};
                        
                          (function() {
                            var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                            po.src = 'https://apis.google.com/js/plusone.js';
                            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                          })();
                        </script>
                    </div>
                </div>
            </div>
            
            <div class="blog_item_titulo">
                <hr class="linha_cinza" />
                <h2><?php echo $this->item->title; ?></h2>
                <hr class="linha_cinza" />
            </div>
            <div class="blog_item_texto">
				 <?php echo $this->item->text; ?>
            </div>
           
             <?php if (!empty($this->item->tags->itemTags)) { ?>
            <div class="blog_item_tags_artigo">
                <h3 class="sub_titulo">Tags</h3>
                <?php $this->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
                <?php $this->tagLayout->render($this->item->tags->itemTags); ?>
                <ul>
                    <?php foreach($this->item->tags->itemTags as $tag) { ?>
                            <li>
                            <a href="<?php echo JRoute::_(TagsHelperRoute::getTagRoute($tag->tag_id . ':' . $tag->alias)); ?>">
                                <?php echo htmlspecialchars($tag->title); ?></a>
                            </li>
                    <?php } ?>
                    
                </ul>
            </div>
            <?php } ?>
            <div class="blog_item_comentarios">
                <h3 class="sub_titulo">Comentarios</h3>
                <div class="fb-comments" data-href="<?php echo 'http://www.olhemaisumavez.com.br'.$link ?>" data-numposts="10" data-width:"660">
                </div>
            </div>
		</div>    
    	
    
       
       
        
    <?php $useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date')
        || $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author')); ?>
        
    
      
    
        <?php
    if (!empty($this->item->pagination) && $this->item->pagination && $this->item->paginationposition && !$this->item->paginationrelative):
        echo $this->item->pagination;
    ?>
        <?php endif; ?>
        
        
       
    </div>
    <div class="span4 blog_direita">
        <div class="blog_tags">
            <h3>Tags populares</h3>
            {module 92}
        </div>
        
        <div class="blog_anunciantes">
            {module 91}	
        </div>
        <div class="blog_arquivo">
            <h3>Arquivo</h3>
            <?php
				//$module = &JModuleHelper::getModule('mod_lca');
				//$html = JModuleHelper::renderModule($module);
				//echo $html;
				$catid =$this->item->catid;
				$helper = new modLcaHelper($catid);
				$data = $helper->getList();
				
				$iyear = 1;
				$imonth = 1;
				$img = 0;
				$collapse = $helper->getImg($img);
				$collapse = $collapse->collapse;
				$show_number = 1;
				$o_year = "desc";
				
				echo '<ul class="lca">';
					foreach ($data->articulos as $year=>$months) {
						echo '<li class="lca ano">';
							echo '<span onclick="lca.f(0,'.$iyear.')" class="lca"><h3>';
							//if ($img)
							//	echo '<img id="lca_0a_'.$iyear.'" class="lca" src="'.$collapse.'" alt="" />';
							//else 
							//	echo '<span id="lca_0a_'.$iyear.'">'.$collapse.'</span>';
							echo ' '.$year.'</h3></span>';
							//if ($show_number)
							//	echo ' ('.$data->years[$year].')';
							echo '<ul class="lca" id="lca_0_'.$iyear.'" style="display: none">';
							foreach ($months as $month=>$articles) {
								if (count($articles)) {
									echo '<li class="lca">';
										echo '<span onclick="lca.f(1,'.$imonth.')" class="lca mes">';
										//if ($img)
										//	echo '<img id="lca_1a_'.$imonth.'" class="lca" src="'.$collapse.'" alt="" />';
										//else
										//	echo '<span id="lca_1a_'.$imonth.'">'.$collapse.'</span>';
										echo ' '.$month.'</span>';
										if ($show_number)
											echo ' ('.$data->meses[$year][$month].')';
										echo '<ul class="lca materia" id="lca_1_'.$imonth.'" style="display: none">';
										foreach ($articles as $article)
											 echo '<li class="lca">'.$article.'</li>';
										echo '</ul>';
									echo '</li>';
									$imonth++;
								}
							}
							echo '</ul>';
						echo '</li>';
						$iyear++;
					}
					echo '</ul>';
					
					
				
			?>
        </div>
	
	</div>
</div>
