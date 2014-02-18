<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');
require_once(dirname(__FILE__).'/helper.php');
JHtml::_('behavior.caption');

?>
<div class="blog_titulo">
	<div class="container">
    	<div class="span12 blog_titulo_corpo">
        	<img src="<?php echo JURI::base().'templates/'.JFactory::getApplication()->getTemplate();; ?>/html/com_content/images/topo_blog.png"/>
            <h1><?php echo $this->category->title ?></h1>
        </div>
    </div>    
</div>
    <div class="blog<?php echo $this->pageclass_sfx;?> container">
        <div class="span7">
			<?php if ($this->params->get('show_page_heading', 1)) : ?>
            <div class="page-header">
                <h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
            </div>
            <?php endif; ?>
            <?php if ($this->params->get('show_category_title', 1) or $this->params->get('page_subheading')) : ?>
            <h2> <?php echo $this->escape($this->params->get('page_subheading')); ?>
                <?php if ($this->params->get('show_category_title')) : ?>
                <span class="subheading-category"><?php echo $this->category->title;?></span>
                <?php endif; ?>
            </h2>
            <?php endif; ?>
        
           
                <?php $this->category->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
                <?php echo $this->category->tagLayout->render($this->category->tags->itemTags); ?>
            
        
            <?php if ($this->params->get('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
            <div class="category-desc clearfix">
                <?php if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
                    <img src="<?php echo $this->category->getParams()->get('image'); ?>"/>
                <?php endif; ?>
                <?php if ($this->params->get('show_description') && $this->category->description) : ?>
                    <?php echo JHtml::_('content.prepare', $this->category->description, '', 'com_content.category'); ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <?php $leadingcount = 0; ?>
            <?php if (!empty($this->lead_items)) : ?>
            <div class="items-leading clearfix">
                <?php foreach ($this->lead_items as &$item) : ?>
                <div class=" leading-<?php echo $leadingcount; ?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>">
                    <?php
                        $this->item = &$item;
                        echo $this->loadTemplate('item');
                    ?>
                </div>
                <?php
                    $leadingcount++;
                ?>
                <?php endforeach; ?>
            </div><!-- end items-leading -->
            <?php endif; ?>
            <?php
            $introcount = (count($this->intro_items));
            $counter = 0;
            ?>
            <?php if (!empty($this->intro_items)) : ?>
            <?php foreach ($this->intro_items as $key => &$item) : ?>
            <?php
                $key = ($key - $leadingcount) + 1;
                $rowcount = (((int) $key - 1) % (int) $this->columns) + 1;
                $row = $counter / $this->columns;
        
                if ($rowcount == 1) : ?>
                <div class="clearfix">
                <?php endif; ?>
                    <div class="">
                        <div class="item column-<?php echo $rowcount;?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>">
                            <?php
                            $this->item = &$item;
                            echo $this->loadTemplate('item');
                        ?>
                        </div><!-- end item -->
                        <?php $counter++; ?>
                    </div><!-- end span -->
                    <?php if (($rowcount == $this->columns) or ($counter == $introcount)) : ?>
                </div><!-- end row -->
                    <?php endif; ?>
            <?php endforeach; ?>
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
			</div>
			<?php
				//$module = &JModuleHelper::getModule('mod_lca');
				//$html = JModuleHelper::renderModule($module);
				//echo $html;
				$catid = $this->category->id;
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
	
			<jdoc:include type="modules" name="blog_arquivo" style="xhtml" />
	
		</div>
        <?php if (!empty($this->link_items)) : ?>
        <div class="items-more">
        <?php echo $this->loadTemplate('links'); ?>
        </div>
        <?php endif; ?>
        <?php if (!empty($this->children[$this->category->id])&& $this->maxLevel != 0) : ?>
        <div class="cat-children">
        <?php if ($this->params->get('show_category_heading_title_text', 1) == 1) : ?>
            <h3> <?php echo JTEXT::_('JGLOBAL_SUBCATEGORIES'); ?> </h3>
        <?php endif; ?>
            <?php echo $this->loadTemplate('children'); ?> </div>
        <?php endif; ?>
        <?php if (($this->params->def('show_pagination', 1) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
        <div class="row">
        	<div class="span7">
        	
        	
        
        <div class="pagination" style="text-align: center">
            <?php  if ($this->params->def('show_pagination_results', 1)) : ?>
            
            <?php endif; ?>
            <?php echo $this->pagination->getPagesLinks(); ?> </div>
	        <?php  endif; ?>
	    </div>
    	</div>
       </div>
