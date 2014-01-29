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

jimport( 'joomla.html.html.tabs' );
$input = JFactory::getApplication()->input;
$tab = $input->get->post('fgtab','','INT');
$options =  array('startOffset'=>$tab);

/*echo '<pre>';
print_r($this->fgParams);
echo '</pre>';*/

?>

<script language="javascript" type="text/javascript">
<!--

var contentsections = new Array;
var sectioncategories = new Array;

<?php

$i = 0;

foreach ($this->contentsections as $k=>$items) {
	foreach ($items as $v) {
		echo "contentsections[".$i++."] = new Array( '$k','".addslashes( $v->id )."','".addslashes( $v->title )."' );\n\t\t";
	}
}
$i = 0;

foreach ($this->sectioncategories as $k=>$items) {
	foreach ($items as $v) {
		echo "sectioncategories[".$i++."] = new Array( '$k','".addslashes( $v->id )."','".addslashes( $v->title )."' );\n\t\t";
	}
} ?>
Joomla.submitbutton = function(pressbutton) {
     var form = document.adminForm;

     if (pressbutton == 'cancel') {
       submitform( pressbutton );
       return;
     }      // do field validation

     if (form.params_content_type.value == "-1") {
       alert( "You must choose a content type" );
     } else {
       submitform( pressbutton );
     }
}

   function toggle(place,type) {
   		var elems = new Array;
   		var hide = 0;
   		if(place == 'processing') {
			if((type == 0) OR (type == null)) {
				hide = 1;
				elems[] = 'paramscompare_existing';
				elems[] = 'paramsforce_new';
				elems[] = 'paramscheck_text';
				elems[] = 'paramsmerging';
			}
			if(type == 1) {
				elems[] = 'paramscompare_existing';
				elems[] = 'paramsforce_new';
			}
			if(type == 2) {
				elems[] = 'paramscheck_text';
				elems[] = 'paramsmerging';
			}
		}
		foreach(elems as elem) {
			$(elem).parent.setStyle('display',(hide ? 'none' : ''));
		}
	}

-->
</script>

<div class="fgform">
	<form action="index.php" method="post" name="adminForm" id="adminForm">

		<?php

		echo JHtml::_('tabs.start','pane',$options);
		echo JHtml::_('tabs.panel',JText::_('FG_TAB_FEED_DETAILS'),'panel1');

		echo FeedgatorHelper::renderFieldset('feed_3',$this->fgParams);
		echo FeedgatorHelper::renderFieldset('feed_1',$this->fgParams);
		echo FeedgatorHelper::renderFieldset('feed_2',$this->fgParams);

		echo JHtml::_('tabs.panel',JText::_('FG_TAB_PUBLISHING'),'panel2');

		echo FeedgatorHelper::renderFieldset('publishing_1',$this->fgParams);
		echo FeedgatorHelper::renderFieldset('publishing_2',$this->fgParams);

		echo JHtml::_('tabs.panel',JText::_('FG_TAB_PROCESSING_DUPS'),'panel3');

		echo FeedgatorHelper::renderFieldset('duplicates',$this->fgParams);

		echo JHtml::_('tabs.panel',JText::_('FG_TAB_TXT_HANDLING'),'panel4');

		echo FeedgatorHelper::renderFieldset('text_1',$this->fgParams);
		echo FeedgatorHelper::renderFieldset('text_2',$this->fgParams);

		echo JHtml::_('tabs.panel',JText::_('FG_TAB_LANGS'),'panel5');

		echo FeedgatorHelper::renderFieldset('languages',$this->fgParams);

		echo JHtml::_('tabs.panel',JText::_('FG_TAB_IMGS_ENCS'),'panel6');

		echo FeedgatorHelper::renderFieldset('images',$this->fgParams);

		echo JHtml::_('tabs.panel',JText::_('FG_TAB_LINKS'),'panel7');

		echo FeedgatorHelper::renderFieldset('links',$this->fgParams);

		echo JHtml::_('tabs.panel',JText::_('FG_TAB_TXT_FLTRS'),'panel8');

		echo FeedgatorHelper::renderFieldset('text',$this->fgParams);

		echo JHtml::_('tabs.panel',JText::_('FG_TAB_HTML_FLTRS'),'panel9');

		echo FeedgatorHelper::renderFieldset('html',$this->fgParams);

		echo JHtml::_('tabs.panel',JText::_('FG_TAB_IMPORT_FLTRS'),'panel10');

		echo FeedgatorHelper::renderFieldset('import_1',$this->fgParams);
		echo FeedgatorHelper::renderFieldset('import_2',$this->fgParams);
		echo FeedgatorHelper::renderFieldset('import_3',$this->fgParams);

		echo JHtml::_('tabs.panel',JText::_('FG_TAB_TAGGING'),'panel11');

		echo FeedgatorHelper::renderFieldset('tagging',$this->fgParams);

		echo JHtml::_('tabs.panel',JText::_('FG_TAB_PLG_SETTINGS'),'panel12');

		echo '<div id="pluginparams">'.JText::_('FG_PLG_PARAMS_NOT_LOADED').'</div>';

		echo JHtml::_('tabs.panel',JText::_('FG_TAB_IMPORT_HX'),'panel13');

		$input->set('ajax',1);
		$input->set('filter_feedid',$this->fgParams->getValue('id'));
		echo $edit ? $this->display('imports') : '<div id="feedimports">'.JText::_('FG_FEED_IMPORTS').'</div>';

		echo JHtml::_('tabs.end');
		?>

		<input type="hidden" name="cid" value="-2" />
		<input type="hidden" name="option" value="com_feedgator" />
		<input type="hidden" name="task" value="" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
</div>