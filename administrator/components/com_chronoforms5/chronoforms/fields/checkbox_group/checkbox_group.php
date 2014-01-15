<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/
namespace GCore\Admin\Extensions\Chronoforms\Fields\CheckboxGroup;
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
class CheckboxGroup {
	static $title = 'Checkbox Group';
	static $cat_id = 'basic';
	static $cat_title = 'Basic';
	static $settings = array(
		'tag' => 'input',
		'type' => 'checkbox_group',
		'name' => 'checkbox_group[]',
		'id' => 'checkbox_group',
		'values' => array(),
		'options' => array('No' => 'No', 'Yes' => 'Yes'),
		'label' => 'Checkbox Group',
		'sublabel' => '',
		'class' => '',
		'title' => '',
		'style' => ''
	);
	
	static $configs = array(
		'name' => array('value' => 'checkbox_group{N}[]', 'label' => 'Field Name', 'type' => 'text', 'class' => 'element_field_name L'),
		'id' => array('value' => 'checkbox_group{N}', 'label' => 'Field ID', 'type' => 'text', 'class' => 'L'),
		'options' => array('value' => "No=No\nYes=Yes", 'label' => 'Options', 'type' => 'textarea', 'rows' => 5, 'alt' => 'options', 'class' => 'L', 'sublabel' => 'In Multiline format, value=Title'),
		'values' => array('value' => '', 'label' => 'Selected Values', 'type' => 'textarea', 'alt' => 'multiline', 'class' => 'L', 'sublabel' => 'In Multiline format'),
		'label.text' => array('value' => 'Checkbox Group', 'label' => 'Label', 'type' => 'text', 'class' => 'L'),
		'label.position' => array('values' => 'left', 'label' => 'Label position', 'type' => 'dropdown', 'options' => array('left' => 'Left', 'top' => 'Top')),
		'sublabel' => array('value' => '', 'label' => 'Sub Label', 'type' => 'text', 'class' => 'L'),
		'horizontal' => array('label' => 'Layout', 'type' => 'dropdown', 'options' => array(0 => 'Vertical', 1 => 'Horizontal')),
		'class' => array('value' => '', 'label' => 'Class', 'type' => 'text', 'class' => 'L'),
		'title' => array('value' => '', 'label' => 'Title', 'type' => 'text', 'class' => 'L'),
		'style' => array('value' => '', 'label' => 'Style', 'type' => 'text', 'class' => 'L'),
		'params' => array('value' => '', 'label' => 'Extra params', 'type' => 'textarea', 'alt' => 'multiline', 'rows' => 5, 'cols' => 60, 'sublabel' => 'In Multiline format:param_name=param_value'),
	);
	
	public static function element($data = array()){
		echo \GCore\Helpers\Html::formSecStart('original_element', 'checkbox_group_origin');
		echo \GCore\Helpers\Html::formLine(self::$settings['name'], array_merge(self::$settings, $data));
		echo \GCore\Helpers\Html::formSecEnd();
	}
	
	public static function config($data = array(), $k = '{N}'){
		echo \GCore\Helpers\Html::formStart('original_element_config single_element_config', 'checkbox_group_origin_config');
		?>
		<ul class="nav nav-tabs">
			<li><a href="#general-<?php echo $k; ?>" data-g-toggle="tab"><?php echo l_('CF_GENERAL'); ?></a></li>
			<li><a href="#validation-<?php echo $k; ?>" data-g-toggle="tab"><?php echo l_('CF_VALIDATION'); ?></a></li>
			<li><a href="#events-<?php echo $k; ?>" data-g-toggle="tab"><?php echo l_('CF_EVENTS'); ?></a></li>
		</ul>
		<div class="tab-content">
			<div id="general-<?php echo $k; ?>" class="tab-pane">
			<?php
			echo \GCore\Helpers\Html::formSecStart();
			foreach(self::$configs as $name => $params){
				$value = \GCore\Libs\Arr::getVal($data, explode('.', $name));
				$field_name = implode('][', explode('.', $name));
				$params['value'] = $value ? (($params['type'] == 'text') ? htmlspecialchars($value, ENT_QUOTES) : $value) : (isset($params['value']) ? $params['value'] : '');
				$params['values'] = $value ? $value : (isset($params['values']) ? $params['values'] : '');
				echo \GCore\Helpers\Html::formLine('Form[extras][fields]['.$k.']['.$field_name.']', $params);
			}
			echo \GCore\Helpers\Html::input('Form[extras][fields]['.$k.'][brackets]', array('type' => 'hidden', 'value' => '0'));
			echo \GCore\Helpers\Html::input('Form[extras][fields]['.$k.'][type]', array('type' => 'hidden', 'value' => self::$settings['type']));
			echo \GCore\Helpers\Html::input('Form[extras][fields]['.$k.'][container_id]', array('type' => 'hidden', 'id' => 'container_id'.$k, 'value' => '0'));
			echo \GCore\Helpers\Html::formSecEnd();
			?>
			</div>
			<div id="validation-<?php echo $k; ?>" class="tab-pane">
			<?php
			echo \GCore\Helpers\Html::formSecStart();
			echo \GCore\Helpers\Html::formLine('Form[extras][fields]['.$k.'][validation][group:'.$k.']', array('type' => 'dropdown', 'label' => l_('CF_REQUIRED'), 'options' => array('' => l_('NO'), 1 => l_('YES'))));
			echo \GCore\Helpers\Html::formSecEnd();
			?>
			</div>
			<div id="events-<?php echo $k; ?>" class="tab-pane">
			<?php
			echo \GCore\Helpers\Html::formSecStart();
			if(empty($data['events'])){
				$data['events'] = array(array());
			}
			foreach($data['events'] as $i => $event){
				echo \GCore\Helpers\Html::formLine('Form[extras][fields]['.$k.'][events][required]', array('type' => 'multi', 'layout' => 'wide',
					'inputs' => array(
						array('type' => 'custom', 'name' => 'Form[extras][fields]['.$k.'][events]['.$i.'][label]', 'code' => 'On selecting'),
						array('type' => 'text', 'name' => 'Form[extras][fields]['.$k.'][events]['.$i.'][state]', 'sublabel' => 'Value selected', 'class' => 'S'),
						array('type' => 'dropdown', 'name' => 'Form[extras][fields]['.$k.'][events]['.$i.'][action]', 'options' => array('' => '', 'enable' => 'enable', 'disable' => 'disable', 'show' => 'show', 'hide' => 'hide', 'function' => 'function'), 'sublabel' => 'Action', 'style' => 'width:auto;'),
						array('type' => 'text', 'name' => 'Form[extras][fields]['.$k.'][events]['.$i.'][target]', 'sublabel' => 'Target field ID or Function name'),
					)
				));
			}
			echo \GCore\Helpers\Html::formLine('add_field_event', array('type' => 'button', 'value' => l_('CF_ADD_EVENT'), 'id' => 'add_field_event_'.$k, 'onclick' => 'addFieldEvent(this, \'add_field_event_'.$k.'\');'));
			
			echo \GCore\Helpers\Html::formSecEnd();
			?>
			</div>
		</div>
		<?php
		echo \GCore\Helpers\Html::formEnd();
	}
}
?>