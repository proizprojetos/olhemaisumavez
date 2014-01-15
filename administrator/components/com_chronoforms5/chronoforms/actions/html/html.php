<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/
namespace GCore\Admin\Extensions\Chronoforms\Actions\Html;
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
Class Html {
	static $title = 'HTML (Render Form)';
	
	function execute(&$form, $action_id){
		$config = !empty($form->actions_config[$action_id]) ? $form->actions_config[$action_id] : array();
		$config = new \GCore\Libs\Parameter($config);
		
		$doc = \GCore\Libs\Document::getInstance();
		//$doc->_('forms');
		
		//check fields validation
		/*
		if(!empty($form->form['Form']['extras']['fields'])){
			$validations = array();
			foreach($form->form['Form']['extras']['fields'] as $k => $field){
				if(!empty($field['validation'])){
					foreach($field['validation'] as $rule => $rule_data){
						$validations[$rule][] = $field['name'].(strlen(trim($rule_data)) > 0 ? ':'.$rule_data : ':');
					}
				}
				if(!empty($field['inputs'])){
					foreach($field['inputs'] as $fn => $field_input){
						if(!empty($field_input['validation'])){
							foreach($field_input['validation'] as $rule => $rule_data){
								$validations[$rule][] = $field_input['name'].(strlen(trim($rule_data)) > 0 ? ':'.$rule_data : ':');
							}
						}
					}
				}
			}
			foreach($validations as $rule => &$fields){
				$fields = implode("\n", $fields);
			}
			$form->execute('client_validation', array('rules' => $validations));
		}
		*/
		$theme = '';
		if($form->params->get('theme', 'bootstrap3') == 'bootstrap3'){
			$theme = 'bootstrap3';
		}else if($form->params->get('theme', 'bootstrap3') == 'semantic1'){
			$theme = 'semantic1';
		}else if($form->params->get('theme', 'bootstrap3') == 'gcoreui'){
			$theme = 'gcoreui';
		}
		$doc->theme = $theme;
		\GCore\Helpers\Theme::getInstance();
		
		//check fields events
		if(!empty($form->form['Form']['extras']['fields'])){
			$events_codes = array();
			$events_codes[] = 'jQuery(document).ready(function($){';
			foreach($form->form['Form']['extras']['fields'] as $k => $field){
				if(!empty($field['id']) AND !empty($field['events'])){
					$_f = '$("[name=\''.$field['name'].'\']").on("click", function(){';
					$_l = '});';
					$_m = array();
					foreach($field['events'] as $k => $event_data){
						if(strlen($event_data['state']) AND strlen($event_data['action']) AND strlen($event_data['target'])){
							$_m[] = $this->create_event($field, $event_data);
						}
					}
					if(!empty($_m)){
						$events_codes[] = $_f."\n".implode("\n", $_m)."\n".$_l;
					}
				}
				if(!empty($field['inputs'])){
					foreach($field['inputs'] as $fn => $field_input){
						if(!empty($field_input['id']) AND !empty($field_input['events'])){
							$_f = '$("[name=\''.$field_input['name'].'\']").on("click", function(){';
							$_l = '});';
							$_m = array();
							foreach($field_input['events'] as $k => $event_data){
								if(strlen($event_data['state']) AND strlen($event_data['action']) AND strlen($event_data['target'])){
									$_m[] = $this->create_event($field_input, $event_data);
								}
							}
							if(!empty($_m)){
								$events_codes[] = $_f."\n".implode("\n", $_m)."\n".$_l;
							}
						}
					}
				}
			}
			$events_codes[] = '});';
			if((bool)$form->params->get('jquery', 1) === true){
				$doc->_('jquery');
			}
			$form->execute('js', array('content' => implode("\n", $events_codes)));
		}
		
		ob_start();
		eval('?>'.$form->form['Form']['content']);
		$output = ob_get_clean();
		//select the page to display
		$form_pages = explode('<!--_CHRONOFORMS_PAGE_BREAK_-->', $output);
		$active_page_index = (int)$config->get('page', 1) - 1;
		$output = $form_pages[$active_page_index];
		//get current url
		$current_url = \GCore\Libs\Url::current();
		if((bool)$config->get('relative_url', 1) === false){
			$current_url = r_('index.php?ext=chronoforms');
		}
		//generate <form tag
		$form_tag = '<form';
		$form_action = (strlen($config->get('action_url', '')) > 0) ? $config->get('action_url', '') : \GCore\Libs\Url::buildQuery($current_url, array('chronoform' => $form->form['Form']['title'], 'event' => 'submit'));
		$form_tag .= ' action="'.r_($form_action).'"';
		//get method
		$form_method = $config->get('form_method', 'post');
		if($config->get('form_method', 'post') == 'file'){
			$form_tag .= ' enctype="multipart/form-data"';
			$form_method = 'post';
		}
		$form_tag .= ' method="'.$form_method.'"';
		$form_tag .= ' name="'.$form->form['Form']['title'].'"';
		$form_tag .= ' id="chronoform-'.$form->form['Form']['title'].'"';
		$form_tag .= ' class="'.$config->get('form_class', 'chronoform').(($theme == 'bootstrap3') ? ' form-horizontal' : '').'"';
		if($config->get('form_tag_attach', '')){
			$form_tag .= $config->get('form_tag_attach', '');
		}

		$form_tag .= '>';
		
		if(empty($theme)){
			$doc->_('forms');
		}
		if($theme == 'bootstrap3'){
			$doc->_('jquery');
			$doc->_('bootstrap');
			\GCore\Helpers\Html::bs();
			echo '<div class="gcore chronoform-container">';
		}
		if(strpos($output, 'validate[') !== false){
			$doc->_('jquery');
			$doc->_('gvalidation');
			$doc->addJsCode('jQuery(document).ready(function($){ $("#chronoform-'.$form->form['Form']['title'].'").gvalidate(); });');
		}
		if(strpos($output, 'data-inputmask=') !== false){
			$doc->_('jquery');
			$doc->_('jquery.inputmask');
			$doc->addJsCode('jQuery(document).ready(function($){ $(":input").inputmask(); });');
		}
		
		echo $form_tag;
		//add fields values
		$output = \GCore\Helpers\DataLoader::load($output, $form->data);
		//show output
		echo $output;
		echo '</form>';
		if($theme == 'bootstrap3'){
			echo '</div>';
		}
	}
	
	function create_event($field, $event_data){
		$return = '';
		if($event_data['state'] == 'check'){
			$return .= 'if($(this).attr("checked") == "checked")';
		}else if($event_data['state'] == 'uncheck'){
			$return .= 'if($(this).attr("checked") == null)';
		}else{
			if($field['type'] == 'checkbox_group'){
				$return .= 'if($("[name=\''.$field['name'].'\'][value=\''.$event_data['state'].'\']").attr("checked") == "checked")';
			}else{
				$return .= 'if($(this).val() == "'.$event_data['state'].'")';
			}
		}
		$return .= '{'."\n";
		if($event_data['action'] == 'enable'){
			$return .= '$("#'.$event_data['target'].'").attr("disabled", false);';
		}
		if($event_data['action'] == 'disable'){
			$return .= '$("#'.$event_data['target'].'").attr("disabled", true);';
		}
		if($event_data['action'] == 'show'){
			$return .= '$("#'.$event_data['target'].'").css("display", "inline-block");';
		}
		if($event_data['action'] == 'hide'){
			$return .= '$("#'.$event_data['target'].'").css("display", "none");';
		}
		if($event_data['action'] == 'set_options'){
			$return .= '$("#'.$event_data['target'].'").find("option").remove();';
			$options = array();
			if(!empty($event_data['options'])){
				$lines = explode("\n", $event_data['options']);
				foreach($lines as $line){
					$opts = explode("=", $line);
					$options[$opts[0]] = $opts[1];
					$return .= '$("#'.$event_data['target'].'").append(\'<option value="'.$opts[0].'">'.trim($opts[1]).'</option>\');'."\n";
				}
			}
		}
		if($event_data['action'] == 'function'){
			$return .= $event_data['target'].';';
		}
		$return .= "\n".'}';
		return $return;
	}
	
	public static function config(){
		echo \GCore\Helpers\Html::formStart('action_config html_action_config', 'html_action_config_{N}');
		echo \GCore\Helpers\Html::formSecStart();
		
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][page]', array('type' => 'text', 'label' => l_('CF_PAGE'), 'value' => '1', 'sublabel' => l_('CF_PAGE_DESC')));
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][form_method]', array('type' => 'dropdown', 'label' => l_('CF_FORM_METHOD'), 'options' => array('file' => 'File', 'post' => 'Post', 'get' => 'Get'), 'sublabel' => l_('CF_FORM_METHOD_DESC')));
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][action_url]', array('type' => 'text', 'label' => l_('CF_ACTION_URL'), 'class' => 'XL', 'sublabel' => l_('CF_ACTION_URL_DESC')));
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][form_class]', array('type' => 'text', 'label' => l_('CF_FORM_CLASS'), 'value' => 'chronoform', 'sublabel' => l_('CF_FORM_CLASS_DESC')));
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][form_tag_attach]', array('type' => 'text', 'label' => l_('CF_FORM_TAG_ATTACHMENT'), 'class' => 'XL', 'rows' => 1, 'sublabel' => l_('CF_FORM_TAG_ATTACHMENT_DESC')));
		echo \GCore\Helpers\Html::formLine('Form[extras][actions_config][{N}][relative_url]', array('type' => 'dropdown', 'label' => l_('CF_RELATIVE_URL'), 'options' => array(0 => l_('NO'), 1 => l_('YES')), 'values' => 1, 'sublabel' => l_('CF_RELATIVE_URL_DESC')));
		
		echo \GCore\Helpers\Html::formSecEnd();
		echo \GCore\Helpers\Html::formEnd();
	}
}