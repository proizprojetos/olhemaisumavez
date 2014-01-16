<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_tags
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');

JHtml::_('behavior.framework');

// Get the user object.
$user = JFactory::getUser();

// Check if user is allowed to add/edit based on tags permissions.
// Do we really have to make it so people can see unpublished tags???
$canEdit = $user->authorise('core.edit', 'com_tags');
$canCreate = $user->authorise('core.create', 'com_tags');
$canEditState = $user->authorise('core.edit.state', 'com_tags');
$items = $this->items;
$n = count($this->items);



?>
<style>
.indice_resultado .titulo_resultado {
	border-bottom: 2px solid #a2a2a2;
	margin-top:50px;
	padding-bottom: 30px;
}
.indice_resultado .titulo_resultado a{
	margin-right: 10px;
	text-decoration: none;
	color: #4e4b48;
	font: normal 1em/1.5em "aller_regular", Tahoma;
}
.indice_resultado .titulo_resultado h4 {
	display: inline-block;
	color: #fdbf57;
	font: normal 2em/2em "aller_regular", Tahoma;
	text-transform: uppercase;
	letter-spacing: 2px;
}
.indice_resultado .titulo_resultado h4 span{
	color: #4e4b48;
}

.indice_resultado .resultado_materia_titulo p {
	display: inline-block;
	text-transform: uppercase;
	font: bold 1.1em/1.5em "aller_regular", Tahoma;
	margin: 0px 5px 0px 0px;
}

.indice_resultado .resultado_materia_titulo h3 {
	display: inline-block;
	text-transform: uppercase;
	font: normal 28px/1.5em "aller_bold", tahoma;
	color: #4aa0dc;
	margin: 0px;
}

.indice_resultado .resultado_materia {
	margin: 30px 0px;
	padding-bottom: 20px;
	border-bottom: 2px solid #a2a2a2;
}
.indice_resultado .resultado_materia_resumo {
	font: normal 1.1em/1.5em "aller_regular", Tahoma;
	color: #4e4b48;
}
.indice_resultado .resultado_materia_resumo a{
	text-decoration: none;
	font: bold 14px/1.5em "aller_regular", Tahoma;
	color: #41c4dd;
	margin: 10px 0px 5px 0px;
	display: inline-block;
}
.indice_resultado .resultado_materia_resumo p{
	font: normal 1em/1.5em "aller_regular", Tahoma;
	color: #4e4b48;
}
	
</style>

	<?php if ($this->items == false || $n == 0) : ?>
		<p> <?php echo JText::_('COM_TAGS_NO_ITEMS'); ?></p>
	<?php else : ?>

    <div class="corpo">
		<div class="corpo_indice coluna indice_resultado">
				<div class="col12 titulo_resultado">
                  <h4>ARQUIVO / BLOG / <?php echo $this->item[0]->title ?></h4>
                </div>
            
                <div class="col12 resultado">
					<?php foreach ($items as $i => $item) : ?>
                    <div class="resultado_materia">
                        <div class="resultado_materia_titulo">
                            <h3><?php echo $item->core_title; ?>.</h3>
                        </div>
                        <div class="resultado_materia_resumo">
                            <span><?php echo substr(strip_tags($item->core_body), 0,300); ?>...<br/></span>
                            
                           <a href="<?php echo JRoute::_(TagsHelperRoute::getItemRoute($item->content_item_id, $item->core_alias, $item->core_catid, $item->core_language, $item->type_alias, $item->router)); ?>">
						Continuar lendo
					</a>

                        </div>				
                    </div>
                   <?php endforeach; ?>
                                
                </div>
            
    	</div>
    </div>

	<?php if ($this->params->get('show_pagination')) : ?>
	 <div class="pagination">
		<?php if ($this->params->get('show_pagination_results', 1)) : ?>
			<p class="counter">
				<?php echo $this->pagination->getPagesCounter(); ?>
			</p>
		<?php endif; ?>
			<?php echo $this->pagination->getPagesLinks(); ?>
		</div>
		<br/>
	<?php endif; ?>


<?php endif; ?>
