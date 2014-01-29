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

defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.framework');
JHtml::_('behavior.tooltip');

?>

<form name="adminForm" id="adminForm" method="post" action="index.php">

	<?php echo FeedgatorHelper::renderFieldset('advanced',$this->config); ?>

	<input type="hidden" name="id" value="<?php echo $this->config->getValue('id');?>" />
	<input type="hidden" name="option" value="com_feedgator"/>
	<input type="hidden" value="" name="task"/>
	<?php echo JHtml::_( 'form.token' ); ?>
</form>