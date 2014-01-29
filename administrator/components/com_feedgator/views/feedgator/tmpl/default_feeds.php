<?php

/**
* FeedGator - Aggregate RSS newsfeed content into a Joomla! database
* @version 3.0a2
* @package FeedGator
* @author Original author Stephen Simmons
* @now continued and modified by Matt Faulds, Remco Boom & Stephane Koenig and others
* @email mattfaulds@gmail.com
* @Joomla 1.5 Version by J. Kapusciarz (mrjozo)
* @copyright (C) 2005 by Stephen Simmons - All rights reserved
* @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
*
**/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.utilities.date');

JHtml::_('behavior.framework');
JHtml::_('behavior.tooltip');

$ordering = ($this->lists['order'] == 'section_name' || $this->lists['order'] == 'cat_name');

$app = JFactory::getApplication();
$user = JFactory::getUser();
$admin_img = JURI::base(true).'/templates/'.$app->getTemplate().'/images/admin/';

foreach ($this->plugins as &$row) {
	if (!$row->pub_count AND !isset($warning)) { ?>
		<div class="warning"><?php echo JText::_('FG_NO_PLGS_PUBLISHED'); ?></div>
		<?php $warning = 1;
	}
} ?>

<div id="fgmsgarea"></div>

<form action="index.php" method="post" name="adminForm" id="adminForm">
	<table>
		<tr>
			<td width="100%">
				<?php echo JText::_( 'Filter' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" title="<?php echo JText::_( 'Filter by title or enter article ID' );?>"/>
				<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_sectionid').value='-1';this.form.getElementById('catid').value='0';this.form.getElementById('filter_authorid').value='0';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
			</td>
		</tr>
	</table>
   	<table class="adminlist" cellspacing="1">
		<thead>
			<tr>
    			<th width="5"><?php echo JText::_( 'Num' ); ?></th>
       			<th width="5"><input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this);" /></th>
       			<th class="title"><?php echo JHTML::_('grid.sort',   'Title', 'title', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
				<th width="1%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',   'Enabled', 'published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
				<th width="1%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',   'Featured', 'front_page', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
       			<th width="20%" nowrap="nowrap" class="title"><?php echo JHTML::_('grid.sort',   'Feed', 'feed', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
       			<th width="8%" nowrap="nowrap" class="title"><?php echo JHTML::_('grid.sort',   'Content Type', 'content_type', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
				<th width="8%" nowrap="nowrap" class="title"><?php echo JHTML::_('grid.sort',   'Category', 'cat_name', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
       			<th width="10" align="center"><?php echo JHTML::_('grid.sort',   'Last Run', 'created', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
				<th width="1%" class="title"><?php echo JHTML::_('grid.sort',   'ID', 'id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
			</tr>
		</thead>
		<tfoot>
		<tr>
			<td colspan="11"><?php echo $this->page->getListFooter(); ?></td>
		</tr>
		</tfoot>
		<tbody>
			<?php

			$k = 0;

			for ($i=0, $n=count( $this->rows ); $i < $n; $i++) {
				$row = &$this->rows[$i];

				$link = 'index.php?option=com_feedgator&task=edit&cid[]='.$row->id;
				//hack to remove strict errors although $row should probably be a JTable object...
				$nrow = JTable::getInstance('Feed','Table');
				$nrow->bind($row);
				$checked 	= JHTML::_('grid.checkedout', $nrow, $i);
				unset($nrow);
				//end hack
				$published 	= JHTML::_('grid.published', $row, $i );
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td><?php echo $this->page->getRowOffset( $i ); ?></td>
					<td align="center" class="feedid"><?php echo $checked; ?></td>
					<td class="feedtitle" rel="<?php echo $row->id; ?>" title="<?php echo $row->title; ?>">
						<?php
						if (  $user->get('id') == $row->checked_out ) {
							echo $row->title;
						} else {
							?>
							<a href="<?php echo JRoute::_( $link ); ?>">
							<?php echo htmlspecialchars($row->title, ENT_QUOTES); ?></a>
							<?php
						}
						?>
					</td>
					<td width="2%" align="center"><?php echo $published;?></td>
					<?php $ftask = $row->front_page ? 'front_no' : 'front_yes'; ?>
					<td width="2%" align="center">
						<a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo ( $row->front_page ) ? 'front_no' : 'front_yes' ;?>')" title="<?php echo ( $row->front_page ) ? JText::_( 'Yes' ) : JText::_( 'No' );?>">
						<img src="<?php echo $admin_img.( $row->front_page ? 'featured.png' : 'disabled.png' );?>" width="16" height="16" border="0" alt="<?php echo ( $row->front_page ) ? JText::_( 'Yes' ) : JText::_( 'No' );?>" /></a>
					</td>
					<td align='left'><?php echo $row->feed; ?></td>
					<td align='left'><?php echo $row->content_type; ?></td>
					<td align='left'><?php echo $row->cat_name; ?></td>
					<td align='left'><?php echo $row->last_run; ?></td>
					<td><?php echo $row->id; ?></td>
			   	</tr>
	   		<?php $k = 1 - $k; } //end for loop ?>
	 	</tbody>
	</table>
	<input type="hidden" name="option" value="com_feedgator" />
	<input type="hidden" name="task" value="feeds" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="redirect" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>