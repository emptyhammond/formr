<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Kohana_Formr class.
 *
 * Formr::create($model);
 * Formr::edit($model);
 *
 */
class Kohana_Formr_Default extends Kohana_Formr
{
	protected static function number($column)
	{
		$string = '<div>';
		$string .= self::label($column);
		$string .= Form::input($column['column_name'],isset($column['default']), array('type' => 'number', 'min' => $column['min'], 'max' => $column['max']));
		$string .= '</div>';
		
		return $string;
	}
	
	protected static function text($column)
	{
		$string = '<div>';
		$string .= self::label($column);
		$string .= Form::input($column['column_name'],isset($column['default']), array());
		$string .= '</div>';
		
		return $string;
	}
	
	protected static function textarea($column)
	{
		$string = '<div>';
		$string .= self::label($column); 
		$string .= Form::textarea($column['column_name'],isset($column['default']), array());
		$string .= '</div>';
		
		return $string;
	}
	
	protected static function label($column)
	{
		$string = Form::label($column['column_name'], isset($column['label']) ? $column['label'] : ucwords($column['column_name']), array());
		
		return $string;
	}
}