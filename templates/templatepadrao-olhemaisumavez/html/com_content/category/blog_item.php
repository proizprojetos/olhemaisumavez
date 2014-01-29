<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;?>
<?php
// Create a shortcut for params.
$params = $this->item->params;
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
$canEdit = $this->item->params->get('access-edit');
JHtml::_('behavior.framework');
//print_r($this->item->tags->itemTags);
?>
    <div class="blog_item_omuv ">
        <div class="blog_item_publicacao">
            <h3><?php echo date( "d", strtotime($this->item->displayDate)) ?></h3>
            <h4><?php echo (JText::_(date( "F", strtotime($this->item->displayDate)).'_SHORT')); ?></h4>
        </div>
        <?php 
			$url = ContentHelperRoute::getArticleRoute($this->item->id.":".$this->item->alias, $this->item->catid.":".$this->item->category_alias);
			$link = JRoute::_($url);
			
		?>
        <div class="blog_item_compartilhar">
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
                    <div class="fb-like" 
                    	data-href="<?php echo 'http://www.olhemaisumavez.com.br'.$link ?>" data-colorscheme="light" data-layout="button_count" data-action="like" data-show-faces="false" data-send="false">
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
        
		
		<?php if ($this->item->state == 0) : ?>
            <span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span>
        <?php endif; ?>
      	<div class="blog_item_titulo">
            <hr class="linha_cinza" />
            <h2><?php echo $this->item->title; ?></h2>
            <hr class="linha_cinza" />
        </div>
        <?php // Todo Not that elegant would be nice to group the params ?>
        <?php $useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date')
            || $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author') ); ?>
        
        <?php echo JLayoutHelper::render('joomla.content.intro_image', $this->item); ?>
        
        
        <?php if (!$params->get('show_intro')) : ?>
            <?php echo $this->item->event->afterDisplayTitle; ?>
        <?php endif; ?>
        <?php echo $this->item->event->beforeDisplayContent; ?> 
		<div class="blog_item_texto">
			<?php echo $this->item->introtext; ?>
        </div>
        <?php if (!empty($this->item->tags->itemTags)) { ?>
        <div class="blog_item_tags_artigo">
        	<h3 class="sub_titulo">Tags</h3>
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
        
        <?php if ($useDefList) : ?>
            <?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'below')); ?>
        <?php  endif; ?>
        
        <?php if ($params->get('show_readmore') && $this->item->readmore) :
            if ($params->get('access-view')) :
                $link = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
            else :
                $menu = JFactory::getApplication()->getMenu();
                $active = $menu->getActive();
                $itemId = $active->id;
                $link1 = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId);
                $returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
                $link = new JUri($link1);
                $link->setVar('return', base64_encode($returnURL));
            endif; ?>
        
            <p class="readmore"><a class="btn" href="<?php echo $link; ?>"> <span class="icon-chevron-right"></span>
        
            <?php if (!$params->get('access-view')) :
                echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
            elseif ($readmore = $this->item->alternative_readmore) :
                echo $readmore;
                if ($params->get('show_readmore_title', 0) != 0) :
                echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
                endif;
            elseif ($params->get('show_readmore_title', 0) == 0) :
                echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');
            else :
                echo JText::_('COM_CONTENT_READ_MORE');
                echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
            endif; ?>
        
            </a></p>
        
        <?php endif; ?>
        
        <?php echo $this->item->event->afterDisplayContent; ?>
    	<div class="blog_item_comentarios">
        	<h3 class="sub_titulo">Comentarios</h3>
            <div class="fb-comments" data-href="<?php echo 'http://www.olhemaisumavez.com.br'.$link ?>" data-numposts="10" data-width:"660">
			</div>
        </div>
    </div>
  