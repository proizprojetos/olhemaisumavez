<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/
namespace GCore\Admin\Extensions\Chronoforms\Actions\Email;
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
Class Email {
	static $title = 'Email';
	static $setup = array('simple' => array('title' => 'Email'));
	
	public static function config(){
		/*$doc = \GCore\Libs\Document::getInstance();
		$doc->_('jquery');
		$doc->addJsCode('
			jQuery(document).ready(function($){
				$("#email_template_loader_{N}").on("click", function(){
					$.ajax({
						url: "'.r_("index.php?ext=chronoforms&act=action_fn&action_name=email&fn=generate_email&tvout=ajax").'",
						data: obj
					}).done(function(msg){
						$("#loading_gif").remove();
						var $newElem = $(msg);
						$newElem.find("td").attr("style", "width: auto !important");
						Element.replaceWith($newElem);
						Element = $newElem.css("width", "100%").removeAttr("id");
						addLinks(Element);
					});
				});
			});
		');
		*/
		echo \GCore\Helpers\Html::formStart('action_config email_action_config', 'email_action_config_{N}');
		echo \GCore\Helpers\Html::formSecStart();
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][action_label]', array('type' => 'text', 'label' => l_('CF_EMAIL_LABEL'), 'class' => 'XL', 'sublabel' => l_('CF_EMAIL_LABEL_DESC')));
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][enabled]', array('type' => 'dropdown', 'label' => l_('CF_ENABLED'), 'options' => array(0 => l_('NO'), 1 => l_('YES')), 'sublabel' => l_('CF_ENABLED_DESC')));
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][to]', array('type' => 'text', 'label' => l_('CF_TO'), 'class' => 'XL', 'sublabel' => l_('CF_TO_DESC')));
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][subject]', array('type' => 'text', 'label' => l_('CF_SUBJECT'), 'class' => 'XL', 'sublabel' => l_('CF_SUBJECT_DESC')));
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][from_name]', array('type' => 'text', 'label' => l_('CF_FROM_NAME'), 'class' => 'XL', 'sublabel' => l_('CF_FROM_NAME_DESC')));
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][from_email]', array('type' => 'text', 'label' => l_('CF_FROM_EMAIL'), 'class' => 'XL', 'sublabel' => l_('CF_FROM_EMAIL_DESC')));
		
		//echo \GCore\Helpers\Html::formLine('email_template_loader', array('type' => 'custom', 'code' => '<input type="button" class="email_template_loader" id="email_template_loader_{N}" value="'.l_('CF_GENERATE_TEMPLATE').'" />', 'sublabel' => l_('CF_GENERATE_TEMPLATE_DESC')));
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][load_editor]', array('type' => 'button', 'class' => 'btn btn-primary', 'value' => l_('CF_LOAD_EDITOR'), 'onclick' => 'toggleEditor(this, \'email_template_{N}\');'));
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][template]', array('type' => 'textarea', 'label' => l_('CF_EMAIL_TEMPLATE'), 'id' => 'email_template_{N}', 'style' => 'width:auto;', 'rows' => 20, 'cols' => 70, 'sublabel' => l_('CF_EMAIL_TEMPLATE_DESC')));
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][attach]', array('type' => 'text', 'label' => l_('CF_ATTACHMENT_FILES'), 'class' => 'XL', 'sublabel' => l_('CF_ATTACHMENT_FILES_DESC')));
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][dto]', array('type' => 'text', 'label' => l_('CF_DYNAMIC_TO'), 'class' => 'XL', 'sublabel' => l_('CF_DYNAMIC_TO_DESC')));
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][dsubject]', array('type' => 'text', 'label' => l_('CF_DYNAMIC_SUBJECT'), 'class' => 'XL', 'sublabel' => l_('CF_DYNAMIC_SUBJECT_DESC')));
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][reply_name]', array('type' => 'text', 'label' => l_('CF_REPLY_TO_NAME'), 'class' => 'XL', 'sublabel' => l_('CF_REPLY_TO_NAME_DESC')));
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][reply_email]', array('type' => 'text', 'label' => l_('CF_REPLY_TO_EMAIL'), 'class' => 'XL', 'sublabel' => l_('CF_REPLY_TO_EMAIL_DESC')));
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][dreply_name]', array('type' => 'text', 'label' => l_('CF_DYNAMIC_REPLY_TO_NAME'), 'class' => 'XL', 'sublabel' => l_('CF_DYNAMIC_REPLY_TO_NAME_DESC')));
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][dreply_email]', array('type' => 'text', 'label' => l_('CF_DYNAMIC_REPLY_TO_EMAIL'), 'class' => 'XL', 'sublabel' => l_('CF_DYNAMIC_REPLY_TO_EMAIL_DESC')));
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][dfrom_name]', array('type' => 'text', 'label' => l_('CF_DYNAMIC_FROM_NAME'), 'class' => 'XL', 'sublabel' => l_('CF_DYNAMIC_FROM_NAME_DESC')));
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][dfrom_email]', array('type' => 'text', 'label' => l_('CF_DYNAMIC_FROM_EMAIL'), 'class' => 'XL', 'sublabel' => l_('CF_DYNAMIC_FROM_EMAIL_DESC')));
		echo \GCore\Helpers\Html::input('Form[extras][actions_config][{N}][__action_title__]', array('type' => 'hidden', 'value' => 'email'));
		echo \GCore\Helpers\Html::formSecEnd();
		echo \GCore\Helpers\Html::formEnd();
	}
	
	function on_form_save(&$data, $action_id){
		if(!empty($data['content']) AND empty($data['extras']['actions_config'][$action_id]['template'])){
			$data['extras']['actions_config'][$action_id]['template'] = $this->field_replacer($data['content']);
		}
	}
	
	function execute(&$form, $action_id){
		$config =  $form->actions_config[$action_id];
		$config = new \GCore\Libs\Parameter($config);
		ob_start();
		eval('?>'.$config->get('template', ''));
		$body = ob_get_clean();
		$body = \GCore\Libs\Str::replacer($body, $form->data, array('replace_null' => true));
		//get recipient
		$tos = array();
		if(strlen(trim($config->get('to', '')))){
			$tos = explode(',', trim($config->get('to', '')));
		}
		if(strlen(trim($config->get('dto', '')))){
			$dtos = explode(',', trim($config->get('dto', '')));
			foreach($dtos as $dto){
				$tos = array_merge((array)$form->data($dto), $tos);
			}
		}
		//subject
		$subject = trim($config->get('subject', '')) ? $config->get('subject', '') : $form->data($config->get('dsubject', ''));
		//from
		$others = array();
		$others['from_name'] = trim($config->get('from_name', '')) ? $config->get('from_name', '') : $form->data($config->get('dfrom_name'), null);
		$others['from_email'] = trim($config->get('from_email', '')) ? $config->get('from_email', '') : $form->data($config->get('dfrom_email'), null);
		//reply to
		$others['reply_name'] = trim($config->get('reply_name', '')) ? $config->get('reply_name', '') : $form->data($config->get('dreply_name'), null);
		$others['reply_email'] = trim($config->get('reply_email', '')) ? $config->get('reply_email', '') : $form->data($config->get('dreply_email'), null);
		
		//attach
		$attachments = array();
		if(strlen(trim($config->get('attach', '')))){
			$attachs = explode(',', trim($config->get('attach', '')));
			foreach($form->files as $name => $file){
				if(in_array($name, $attachs)){
					$attachments[] = $file['path'];
				}
			}
		}
		//load global settings
		$settings = $form::_settings();
		if(!empty($settings['mail'])){
			foreach($settings['mail'] as $k => $v){
				\GCore\Libs\Base::setConfig($k, $v);
			}
		}
		
		$sent = \GCore\Libs\Mailer::send($tos, $subject, $body, $attachments, $others);
	}
	
	function field_replacer($htmlcode = ''){		
		//find any style code in the email template and get it here
		preg_match_all('/<style(.*?)<\/style>/is', $htmlcode, $style_matches);
		if(isset($style_matches[0]) && !empty($style_matches[0])){
			foreach($style_matches[0] as $style_code){
				$htmlcode = str_replace($style_code, '', $htmlcode);
			}
		}
		//ob_start();
		/*eval( "?>".$htmlcode);*/
		$html_string = $htmlcode;//ob_get_clean();
		$usednames = array();
		//end fields names
		//text fields
		$pattern_input = '/<input([^>]*?)type=("|\')(text|password|hidden|file)("|\')([^>]*?)>/is';
		$matches = array();
		preg_match_all($pattern_input, $html_string, $matches);
		foreach($matches[0] as $match){
			$pattern_name = '/name=("|\')([^(>|"|\')]*?)("|\')/i';
			preg_match($pattern_name, $match, $matches_name);
			if(isset($matches_name[2]) && trim(str_replace('[]', '', $matches_name[2]))){				
				$email_data_name = "{".str_replace('[]', '', $matches_name[2])."}";
				$email_data_name = str_replace(array('[', ']'), array('.', ''), $email_data_name);
				if(!in_array($email_data_name, $usednames)){
					$html_string = str_replace($match, $email_data_name, $html_string);
					$usednames[] = $email_data_name;
				}else{
					$html_string = str_replace($match, "", $html_string);
				}
			}else{
				//$html_string = str_replace($match, "{This_element_has_no_name_attribute}", $html_string);
				$html_string = str_replace($match, "", $html_string);
			}
		}
		//buttons
		$pattern_input = '/<input([^>]*?)type=("|\')(submit|button|reset|image)("|\')([^>]*?)>/is';
		$matches = array();
		preg_match_all($pattern_input, $html_string, $matches);
		foreach($matches[0] as $match){
			$pattern_name = '/name=("|\')([^(>|"|\')]*?)("|\')/i';
			preg_match($pattern_name, $match, $matches_name);
			if(isset($matches_name[2]) && trim(str_replace('[]', '', $matches_name[2]))){				
				$email_data_name = "";
				if(!in_array($email_data_name, $usednames)){
					$html_string = str_replace($match, $email_data_name, $html_string);
					$usednames[] = $email_data_name;
				}else{
					$html_string = str_replace($match, "", $html_string);
				}
			}else{
				//$html_string = str_replace($match, "{This_element_has_no_name_attribute}", $html_string);
				$html_string = str_replace($match, "", $html_string);
			}
		}
		//checkboxes or radios fields
		$pattern_input = '/<input([^>]*?)type=("|\')(checkbox|radio)("|\')([^>]*?)>/is';
		$matches = array();
		$check_radio_idslist = array();
		preg_match_all($pattern_input, $html_string, $matches);
		foreach($matches[0] as $match){
			$pattern_id = '/id=("|\')([^(>|"|\')]*?)("|\')/i';
			$pattern_name = '/name=("|\')([^(>|"|\')]*?)("|\')/i';
			preg_match($pattern_name, $match, $matches_name);
			preg_match($pattern_id, $match, $matches_id);
			if(isset($matches_name[2]) && trim(str_replace('[]', '', $matches_name[2]))){	
				$check_radio_idslist[] = $matches_id[2];		
				$email_data_name = "{".str_replace('[]', '', $matches_name[2])."}";
				$email_data_name = str_replace(array('[', ']'), array('.', ''), $email_data_name);
				if(!in_array($email_data_name, $usednames)){
					$html_string = str_replace($match, $email_data_name, $html_string);
					$usednames[] = $email_data_name;
				}else{
					$html_string = str_replace($match, "", $html_string);
				}
			}else{
				//$html_string = str_replace($match, "{This_element_has_no_name_attribute}", $html_string);
				$html_string = str_replace($match, "", $html_string);
			}
		}
		//radios-checks labels
		$pattern_label = '/<label([^>]*?)for=("|\')('.implode("|", $check_radio_idslist).')("|\')([^>]*?)>(.*?)<\/label>/is';
		$matches = array();
		preg_match_all($pattern_label, $html_string, $matches);
		foreach($matches[0] as $match){
			$html_string = str_replace($match, "", $html_string);
		}
		//textarea fields
		$pattern_textarea = '/<textarea([^>]*?)>(.*?)<\/textarea>/is';
		$matches = array();
		preg_match_all($pattern_textarea, $html_string, $matches);
		$namematch = '';
		foreach($matches[0] as $match){
			$pattern_name = '/name=("|\')([^(>|"|\')]*?)("|\')/i';
			preg_match($pattern_name, $match, $matches_name);
			if(isset($matches_name[2]) && trim(str_replace('[]', '', $matches_name[2]))){				
				$email_data_name = "{".str_replace('[]', '', $matches_name[2])."}";
				$email_data_name = str_replace(array('[', ']'), array('.', ''), $email_data_name);
				if(!in_array($email_data_name, $usednames)){
					$html_string = str_replace($match, $email_data_name, $html_string);
					$usednames[] = $email_data_name;
				}else{
					$html_string = str_replace($match, "", $html_string);
				}
			}else{
				//$html_string = str_replace($match, "{This_element_has_no_name_attribute}", $html_string);
				$html_string = str_replace($match, "", $html_string);
			}
		}
		//select boxes
		$pattern_select = '/<select(.*?)select>/is';
		$matches = array();
		preg_match_all($pattern_select, $html_string, $matches);

		foreach($matches[0] as $match){
			$selectmatch = $match;
			$pattern_select2 = '/<select([^>]*?)>/is';
			preg_match_all($pattern_select2, $match, $matches2);
			$pattern_name = '/name=("|\')([^(>|"|\')]*?)("|\')/i';
			preg_match($pattern_name, $matches2[0][0], $matches_name);
			if(isset($matches_name[2]) && trim(str_replace('[]', '', $matches_name[2]))){				
				$email_data_name = "{".str_replace('[]', '', $matches_name[2])."}";
				$email_data_name = str_replace(array('[', ']'), array('.', ''), $email_data_name);
				if(!in_array($email_data_name, $usednames)){
					$html_string = str_replace($match, $email_data_name, $html_string);
					$usednames[] = $email_data_name;
				}else{
					$html_string = str_replace($match, "", $html_string);
				}
			}else{
				//$html_string = str_replace($match, "{This_element_has_no_name_attribute}", $html_string);
				$html_string = str_replace($match, "", $html_string);
			}
		}
		return $html_string;
		
	}
}