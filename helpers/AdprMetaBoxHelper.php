<?php
if ( ! defined('ABSPATH') ) {
	die( 'You are not allowed to call this page directly.' );
}
class AdprMetaBoxHelper {
	
	public static function adpr_do_meta_fields($field_array) {
		$fields = '<div id="adpr" style="display: inline-block">';
		foreach ($field_array as $field) {
			$meta_fields = $field['meta_id'];
			$value = null;
			$type = $field['type'];
			$name = $field['name'];
			$id = $field['id'];
			$label = $field['label'];
			$options = null;
			if(isset($field['options']))
				$options = $field['options'];
			if(isset($meta_fields[$name]))
				$value = $meta_fields[$name];
			$description = $field['description'];
			
			switch($type) {
				case 'time':
					$fields .= '<div>';
					$fields .= AdprMetaBoxHelper::adpr_print_label($label);
					$fields .= AdprMetaBoxHelper::$adpr_group_open;
					$fields .= AdprMetaBoxHelper::$adpr_addon_open;
					$fields .= 'Hours';
					$fields .= AdprMetaBoxHelper::$adpr_span_close;
					$fields .= AdprMetaBoxHelper::adpr_print_number_input('number','adpr_hours', 'adpr_hours', $value[0], 'auto','1', '1000', '1', '0');
					$fields .= AdprMetaBoxHelper::$adpr_div_close;
					
					$fields .= AdprMetaBoxHelper::$adpr_group_open;
					$fields .= AdprMetaBoxHelper::$adpr_addon_open;
					$fields .= 'Minutes';
					$fields .= AdprMetaBoxHelper::$adpr_span_close;
					$fields .= AdprMetaBoxHelper::adpr_print_number_input('number','adpr_minutes', 'adpr_minutes', $value[0], 'auto','0', '59', '1', '0');
					$fields .= AdprMetaBoxHelper::$adpr_div_close;
					$fields .= '</div>';
					break;
				case 'number':
					$fields .= AdprMetaBoxHelper::$adpr_group_open;
					$fields .= AdprMetaBoxHelper::$adpr_addon_open;
					$fields .= $label;
					$fields .= AdprMetaBoxHelper::$adpr_span_close;
					$fields .= AdprMetaBoxHelper::adpr_print_number_input($type,$name,$id,$value[0], 'auto', '0', '10000', '0.01', '0.00');
					$fields .= AdprMetaBoxHelper::$adpr_div_close;
					break;
				case 'select':
					$fields .= AdprMetaBoxHelper::$adpr_group_open;
					$fields .= AdprMetaBoxHelper::$adpr_addon_open;
					$fields .= $label;
					$fields .= AdprMetaBoxHelper::$adpr_span_close;
					$fields .= AdprMetaBoxHelper::adpr_print_select($name, $id, $options, $value[0], 'auto');
					$fields .= '</div>';
					break;
			}
		}
		$fields .= '</div>';
		echo $fields;
	}
	
	private static $adpr_group_open = '<div class="adpr-group" style="position: relative; display: table; border-collapse: separate;margin-top: 10px; margin-right: 10px; float: left; width: 10%;">';
	private static $adpr_addon_open = '<span class="adpr-group-addon" style="display:table-cell;width: 1%;white-space: nowrap;vertical-align: middle;padding: 6px 12px;font-size: 14px;font-weight: 400;line-height: 1;color: #555;text-align: center;background-color: #eee;border: 1px solid #ccc;border-radius: 4px;border-right:0;border-top-right-radius: 0;border-bottom-right-radius: 0;">';
	private static $adpr_span_close = '</span>';
	private static $adpr_div_close = '</div>';
	
	private static function adpr_print_label($label) {
		return '<label style="float: left">' . $label . '</label>';
	}
	
	private static function adpr_print_select($name, $id, $options, $value, $width) {
		$select = '<select name="';
		$select .= $name;
		$select .= '" id="';
		$select .= $id;
		$select .= '" style="';
		$select .= AdprMetaBoxHelper::adpr_get_field_styles($width);
		$select .= '">';
		if($value == null){
			$select .= '<option value="null" disabled selected>00</option>';
		} else {
			$select .= '<option value="null" disabled>00</option>';
		}
		foreach($options as $option){
			if($value == $option){
				$select .= '<option value="' . $option . '" selected>' . $option . '</option>';
			} else {
				$select .= '<option value="' . $option . '">' . $option . '</option>';
			}
		}
		
		$select .= '</select>';
		
		return $select;
	}
	
	private static function adpr_print_input( $type, $name, $id, $value, $width) {
		$input = '<input type="';
		$input .= $type;
		$input .= '" name="';
		$input .= $name;
		$input .='" id="';
		$input .= $id;
		$input .= '" value="';
		$input .= $value;
		$input .= '" style="';
		$input .= AdprMetaBoxHelper::adpr_get_field_styles($width);
		$input .= '" />';
		return $input;
	}
	
	private static function adpr_print_number_input( $type, $name, $id, $value, $width, $min, $max, $step, $placeholder) {
		$input = '<input type="';
		$input .= $type;
		$input .= '" name="';
		$input .= $name;
		$input .='" id="';
		$input .= $id;
		$input .= '" value="';
		$input .= $value;
		$input .= '" style="';
		$input .= AdprMetaBoxHelper::adpr_get_field_styles($width);
		$input .= '" min="'.$min.'" max="'.$max.'" step="'.$step.'" placeholder="'.$placeholder.'"/>';
		return $input;
	}
	
	private static function adpr_get_field_styles($width) {
		$style = 'position: relative;';
		$style .= 'margin: 0;';
		$style .= 'display: table-cell;';
		$style .= 'z-index: 3;';
		$style .= 'float: left;';
		$style .= 'width: '. $width . ';';
		$style .= 'margin-bottom: 0;';
		$style .= 'height: 34px;';
		$style .= 'padding: 6px 12px;';
		$style .= 'font-size: 14px;';
		$style .= 'line-height: 1.42857143;';
		$style .= 'color: #555;';
		$style .= 'background-color: #fff;';
		$style .= 'background-image: none;';
		$style .= 'border: 1px solid #ccc;';
		$style .= 'border-radius: 0 4px 4px 0;';
		$style .= '-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);';
		$style .= 'box-shadow: inset 0 1px 1px rgba(0,0,0,.075);';
		$style .= '-webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;';
		$style .= '-o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;';
		$style .= 'transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;';
		return $style;
	}
	
}