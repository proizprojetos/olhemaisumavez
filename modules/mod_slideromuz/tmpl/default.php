<?php 

/**
* @Copyright Copyright (C) 2013 - JoniJnm.es
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined( '_JEXEC' ) or die( 'Restricted access' ); 

require_once (JPATH_SITE.'/components/com_content/helpers/route.php');

?>

<?php 
	//foreach($lista as $value => $item) { 
	//$images  = json_decode($item->images);
	$url = ContentHelperRoute::getArticleRoute($item->id.":".$item->alias, $item->catid.":".$item->calias);
	$link = JRoute::_($url);
	//print_r($item->co);
	$date = JFactory::getDate();
	$now = method_exists($date, 'toMySQL') ? $date->toMySQL() : $date->toSql();
	
?>

<h4><?php echo JHTML::_('date', $item->publish_up, JText::_('d')) ?> de <?php echo JHTML::_('date', $item->publish_up, JText::_('F')) ?></h4>
<a href="<?php echo $link; ?>"><h1><?php echo strip_tags($item->title); ?></h1></a>
<p><?php echo strip_tags(substr($item->introtext, 0, 150));  ?>... <a href="<?php echo $link; ?>">Continue lendo</a>	
</p>