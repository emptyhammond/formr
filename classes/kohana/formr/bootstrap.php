<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Kohana_Formr_Bootstrap class.
 *
 * @extends Kohana_Formr
 */
class Kohana_Formr_Bootstrap extends Kohana_Formr
{
	/**
	 * open function.
	 *
	 * @access protected
	 * @static
	 * @param string $path (default: '')
	 * @param array $array (default: array())
	 * @return void
	 */
	protected static function open($path = '', array $array = array())
	{
		$defaults = array('class' => 'well form-horizontal', 'method' => false);

		$options = array_merge($defaults, $array);

		$output = Form::open(null, array('enctype' => $options['enctype'], 'method' => ($options['method'] ? $options['method'] : 'post'), 'class' => $options['class']));

		return $output;
	}

	/**
	 * close function.
	 *
	 * @access protected
	 * @static
	 * @return void
	 */
	protected static function close()
	{
		$output = '</form>';

		return $output;
	}

	/**
	 * actions function.
	 *
	 * @access protected
	 * @static
	 * @return void
	 */
	protected static function actions()
	{
		$output = '<div class="form-actions">';
		$output .= self::submit();
		$output .= self::reset();
		$output .= '</div>';

		return $output;
	}

	/**
	 * hidden function.
	 *
	 * @access protected
	 * @static
	 * @param mixed $column
	 * @return void
	 */
	protected static function hidden($column)
	{
		$output = Form::hidden($column['column_name'],(isset(self::$_object->{$column['column_name']}) ? self::$_object->{$column['column_name']} : (isset($column['default']) ? $column['default'] : '')), (isset(self::$_options['attributes'][$column['column_name']]) ? self::$_options['attributes'][$column['column_name']] : array()));

		return $output;
	}

	/**
	 * number function.
	 *
	 * @access protected
	 * @static
	 * @param mixed $column
	 * @return void
	 * @todo add attributes merge
	 */
	protected static function number($column)
	{
		$disabled = in_array($column['column_name'], self::$_options['disabled']) ? array('disabled' => true) : array();

		$output = '<div class="control-group'.(isset(self::$_options['errors'][$column['column_name']]) ? ' error': '').'">';
		$output .= self::label($column);
		$output .= '<div class="controls">';

		$output .= Form::input($column['column_name'],(self::$_object->{$column['column_name']} ? self::$_object->{$column['column_name']} : (isset($column['default']) ? $column['default'] : '')),
			Arr::merge(array(
				'type' => 'number',
				'min' => (isset($column['min']) ? $column['min'] : 0),
				'max' => (isset($column['max']) ? $column['max'] : 99999999999999),
				'step' => '0.01',
				'class' => 'number '.self::$_options['classes'][$column['column_name']],
			), $disabled, (isset(self::$_options['attributes'][$column['column_name']]) ? self::$_options['attributes'][$column['column_name']] : array())));

		$output .= (isset(self::$_options['help'][$column['column_name']]) or isset(self::$_options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset(self::$_options['errors'][$column['column_name']]) ? self::$_options['errors'][$column['column_name']]: self::$_options['help'][$column['column_name']]).'</p>' : '';

		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}
	
	protected static function int($column)
	{
		$disabled = in_array($column['column_name'], self::$_options['disabled']) ? array('disabled' => true) : array();

		$output = '<div class="control-group'.(isset(self::$_options['errors'][$column['column_name']]) ? ' error': '').'">';
		$output .= self::label($column);
		$output .= '<div class="controls">';

		$output .= Form::input($column['column_name'],(self::$_object->{$column['column_name']} ? self::$_object->{$column['column_name']} : (isset($column['default']) ? $column['default'] : '')),
			Arr::merge(array(
				'type' => 'number',
				'min' => (isset($column['min']) ? $column['min'] : 0),
				'max' => (isset($column['max']) ? $column['max'] : 99999999999999),
				'step' => '1',
				'class' => 'number '.self::$_options['classes'][$column['column_name']],
			), $disabled, (isset(self::$_options['attributes'][$column['column_name']]) ? self::$_options['attributes'][$column['column_name']] : array())));

		$output .= (isset(self::$_options['help'][$column['column_name']]) or isset(self::$_options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset(self::$_options['errors'][$column['column_name']]) ? self::$_options['errors'][$column['column_name']]: self::$_options['help'][$column['column_name']]).'</p>' : '';

		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

	/**
	 * date function.
	 *
	 * @access protected
	 * @static
	 * @param mixed $column
	 * @return void
	 */
	protected static function date($column)
	{
		$disabled = in_array($column['column_name'], self::$_options['disabled']) ? array('disabled' => true) : array();

		$value = (self::$_object->{$column['column_name']}
		? (is_numeric(self::$_object->{$column['column_name']}) ? date('Y-m-d',self::$_object->{$column['column_name']}) : self::$_object->{$column['column_name']})
		: (isset($column['default']) ? $column['default'] : ''));

		$output = '<div class="control-group'.(isset(self::$_options['errors'][$column['column_name']]) ? ' error': '').'">';
		$output .= self::label($column);
		$output .= '<div class="controls">';

		$output .= Form::input($column['column_name'], $value,
			Arr::merge(array(
				'autocomplete' => 'off',			
				'type' => 'date',
				'class' => 'date '.self::$_options['classes'][$column['column_name']],
			), $disabled, (isset(self::$_options['attributes'][$column['column_name']]) ? self::$_options['attributes'][$column['column_name']] : array())));

		$output .= (isset(self::$_options['help'][$column['column_name']]) or isset(self::$_options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset(self::$_options['errors'][$column['column_name']]) ? self::$_options['errors'][$column['column_name']]: self::$_options['help'][$column['column_name']]).'</p>' : '';

		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}
	
	
	/**
	 * datetime function.
	 * 
	 * @access protected
	 * @static
	 * @param mixed $column
	 * @return void
	 */
	protected static function datetime($column)
	{
		$disabled = in_array($column['column_name'], self::$_options['disabled']) ? array('disabled' => true) : array();
		
		$hours = array(''=>'--');
		
		$minutes = array(''=>'--');
		
		for($i = 0;$i < 24;$i++)
		{
			$hours[ (string) sprintf("%02u", $i)] = sprintf("%02u", $i);
		}
		
		for($i = 0;$i < 60;$i++)
		{
			$minutes[ (string) sprintf("%02u", $i)] = sprintf("%02u", $i);
		}
		
		$value = (self::$_object->{$column['column_name']}
		? (is_numeric(self::$_object->{$column['column_name']}) ? date('Y-m-d',self::$_object->{$column['column_name']}) : self::$_object->{$column['column_name']})
		: (isset($column['default']) ? $column['default'] : ''));

		$output = '<div class="control-group form-inline '.(isset(self::$_options['errors'][$column['column_name']]) ? ' error': '').'">';
		
		$output .= self::label($column);
		
		$output .= '<div class="controls">';
		
		$output .= Form::input($column['column_name'], $value,
			Arr::merge(array(
				'autocomplete' => 'off',
				'type' => 'date',
				'class' => 'date '.self::$_options['classes'][$column['column_name']],
			), $disabled, (isset(self::$_options['attributes'][$column['column_name']]) ? self::$_options['attributes'][$column['column_name']] : array())));
		
		$output .= ' '.Form::select('hour['.$column['column_name'].']', $hours, (self::$_object->{$column['column_name']} ? date('H',self::$_object->{$column['column_name']}) : '09'), array('type' => 'number', 'class' => 'input-mini hour-datetime'));
		
		$output .= ' '.self::label(array('column_name' => ':'), 'label-datetime').' ';
		
		$output .= Form::select('minute['.$column['column_name'].']', $minutes, (self::$_object->{$column['column_name']} ? date('i',self::$_object->{$column['column_name']}) : '00'), array('type' => 'number', 'class' => 'input-mini minute-datetime'));

		$output .= (isset(self::$_options['help'][$column['column_name']]) or isset(self::$_options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset(self::$_options['errors'][$column['column_name']]) ? self::$_options['errors'][$column['column_name']]: self::$_options['help'][$column['column_name']]).'</p>' : '';

		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}
	
	/**
	 * datetime function.
	 * 
	 * @access protected
	 * @static
	 * @param mixed $column
	 * @return void
	 */
	protected static function time($column)
	{
		$disabled = in_array($column['column_name'], self::$_options['disabled']) ? array('disabled' => true) : array();

		$value = (self::$_object->{$column['column_name']}
		? (is_numeric(self::$_object->{$column['column_name']}) ? date('m/d/Y',self::$_object->{$column['column_name']}) : self::$_object->{$column['column_name']})
		: (isset($column['default']) ? $column['default'] : ''));

		$output = '<div class="control-group'.(isset(self::$_options['errors'][$column['column_name']]) ? ' error': '').'">';
		$output .= self::label($column);
		$output .= '<div class="controls">';

		$output .= Form::input($column['column_name'], $value,
			Arr::merge(array(
				'autocomplete' => 'off',			
				'type' => 'time',
				'class' => 'time '.self::$_options['classes'][$column['column_name']],
			), $disabled, (isset(self::$_options['attributes'][$column['column_name']]) ? self::$_options['attributes'][$column['column_name']] : array())));

		$output .= (isset(self::$_options['help'][$column['column_name']]) or isset(self::$_options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset(self::$_options['errors'][$column['column_name']]) ? self::$_options['errors'][$column['column_name']]: self::$_options['help'][$column['column_name']]).'</p>' : '';

		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

	/**
	 * email function.
	 *
	 * @access protected
	 * @static
	 * @param mixed $column
	 * @return void
	 */
	protected static function email($column)
	{
		$disabled = in_array($column['column_name'], self::$_options['disabled']) ? array('disabled' => true) : array();

		$value = (self::$_object->{$column['column_name']}
		? self::$_object->{$column['column_name']}
		: (isset($column['default']) ? $column['default'] : ''));

		$output = '<div class="control-group'.(isset(self::$_options['errors'][$column['column_name']]) ? ' error': '').'">';
		$output .= self::label($column);
		$output .= '<div class="controls">';

		$output .= Form::input($column['column_name'], $value,
			Arr::merge(array(
				'type' => 'email',
				'class' => 'email '.self::$_options['classes'][$column['column_name']],
			), $disabled, (isset(self::$_options['attributes'][$column['column_name']]) ? self::$_options['attributes'][$column['column_name']] : array())));

		$output .= (isset(self::$_options['help'][$column['column_name']]) or isset(self::$_options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset(self::$_options['errors'][$column['column_name']]) ? self::$_options['errors'][$column['column_name']]: self::$_options['help'][$column['column_name']]).'</p>' : '';

		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}
	
	protected static function color($column)
	{
		$disabled = in_array($column['column_name'], self::$_options['disabled']) ? array('disabled' => true) : array();

		$value = (self::$_object->{$column['column_name']}
		? self::$_object->{$column['column_name']}
		: (isset($column['default']) ? $column['default'] : ''));

		$output = '<div class="control-group'.(isset(self::$_options['errors'][$column['column_name']]) ? ' error': '').'">';
		$output .= self::label($column);
		$output .= '<div class="controls">';

		$output .= Form::input($column['column_name'], $value,
			Arr::merge(array(
				'type' => 'color',
				'class' => 'color '.self::$_options['classes'][$column['column_name']],
				'style' => 'height: 20px; width: 20px;'
			), $disabled, (isset(self::$_options['attributes'][$column['column_name']]) ? self::$_options['attributes'][$column['column_name']] : array())));

		$output .= (isset(self::$_options['help'][$column['column_name']]) or isset(self::$_options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset(self::$_options['errors'][$column['column_name']]) ? self::$_options['errors'][$column['column_name']]: self::$_options['help'][$column['column_name']]).'</p>' : '';

		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

	/**
	 * varchar function.
	 *
	 * @access protected
	 * @static
	 * @param mixed $column
	 * @return void
	 */
	protected static function varchar($column)
	{
		$disabled = in_array($column['column_name'], self::$_options['disabled']) ? array('disabled' => true) : array();

		$output = '<div class="control-group'.(isset(self::$_options['errors'][$column['column_name']]) ? ' error': '').'">';
		$output .= self::label($column);
		$output .= '<div class="controls">';

		$output .= Form::input($column['column_name'],(isset(self::$_object->{$column['column_name']}) ? self::$_object->{$column['column_name']} : (isset($column['default']) ? $column['default'] : '')),
			Arr::merge(array(
				'class' => self::$_options['classes'][$column['column_name']],
				'maxlength' => isset($column['character_maximum_length']) ? $column['character_maximum_length'] : '8000',
			), $disabled, (isset(self::$_options['attributes'][$column['column_name']]) ? self::$_options['attributes'][$column['column_name']] : array())));

		$output .= (isset(self::$_options['help'][$column['column_name']]) or isset(self::$_options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset(self::$_options['errors'][$column['column_name']]) ? self::$_options['errors'][$column['column_name']]: self::$_options['help'][$column['column_name']]).'</p>' : '';
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

	/**
	 * password function.
	 *
	 * @access protected
	 * @static
	 * @param mixed $column
	 * @return void
	 */
	protected static function password($column)
	{
		$disabled = in_array($column['column_name'], self::$_options['disabled']) ? array('disabled' => true) : array();

		$output = '<div class="control-group'.(isset(self::$_options['errors'][$column['column_name']]) ? ' error': '').'">';
		$output .= self::label($column);
		$output .= '<div class="controls">';

		$output .= Form::password($column['column_name'], null,
			Arr::merge(array(
				'class' => self::$_options['classes'][$column['column_name']],
				'maxlength' => isset($column['character_maximum_length']) ? $column['character_maximum_length'] : 255,
			), $disabled, (isset(self::$_options['attributes'][$column['column_name']]) ? self::$_options['attributes'][$column['column_name']] : array())));

		$output .= (isset(self::$_options['help'][$column['column_name']]) or isset(self::$_options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset(self::$_options['errors'][$column['column_name']]) ? self::$_options['errors'][$column['column_name']]: self::$_options['help'][$column['column_name']]).'</p>' : '';
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

	/**
	 * text function.
	 *
	 * @access protected
	 * @static
	 * @param mixed $column
	 * @return void
	 */
	protected static function text($column)
	{
		$disabled = in_array($column['column_name'], self::$_options['disabled']) ? array('disabled' => true) : array();

		$output = '<div class="control-group'.(isset(self::$_options['errors'][$column['column_name']]) ? ' error': '').'">';
		$output .= self::label($column);
		$output .= '<div class="controls">';

		$output .= Form::textarea($column['column_name'],(self::$_object->{$column['column_name']} ? self::$_object->{$column['column_name']} : (isset($column['default']) ? $column['default'] : '')),
			Arr::merge(array(
				'class' => self::$_options['classes'][$column['column_name']],
				'maxlength' => isset($column['character_maximum_length']) ? $column['character_maximum_length'] : 8000,
			), $disabled, (isset(self::$_options['attributes'][$column['column_name']]) ? self::$_options['attributes'][$column['column_name']] : array())));

		$output .= (isset(self::$_options['help'][$column['column_name']]) or isset(self::$_options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset(self::$_options['errors'][$column['column_name']]) ? self::$_options['errors'][$column['column_name']]: self::$_options['help'][$column['column_name']]).'</p>' : '';
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

	/**
	 * checkbox function.
	 *
	 * @access protected
	 * @static
	 * @param mixed $column
	 * @return void
	 */
	protected static function checkbox($column)
	{
		$disabled = in_array($column['column_name'], self::$_options['disabled']) ? array('disabled' => true) : array();

		$output  = '<div class="control-group">';
        $output .= self::label($column);
        $output .= '<div class="controls">';
		$output .= '<label class="checkbox inline">';

		$output .= '<input type="checkbox" name="'.$column['column_name'].'" id="'.$column['column_name'].'" value="1"'.( (boolean) self::$_object->{$column['column_name']} ? 'checked' : false).' class="'.self::$_options['classes'][$column['column_name']].(isset(self::$_options['disabled'][$column['column_name']]) ? ' disabled' : false).' "/>';

        $output .= (isset(self::$_options['help'][$column['column_name']]) or isset(self::$_options['errors'][$column['column_name']])) ? (isset(self::$_options['errors'][$column['column_name']]) ? self::$_options['errors'][$column['column_name']]: self::$_options['help'][$column['column_name']]) : '';
		$output .= '</label>';
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

	/**
	 * file function.
	 *
	 * @access protected
	 * @static
	 * @param mixed $column
	 * @return void
	 */
	protected static function file($column)
	{
		$disabled = in_array($column['column_name'], self::$_options['disabled']) ? array('disabled' => true) : array();

		$output = '<div class="control-group'.(isset(self::$_options['errors'][$column['column_name']]) ? ' error': '').'">';
		$output .= self::label($column);
		$output .= '<div class="controls">';
		$output .= Form::file($column['column_name'],
			Arr::merge(array(
				'class' => 'input-file '.self::$_options['classes'][$column['column_name']],
			), $disabled, (isset(self::$_options['attributes'][$column['column_name']]) ? self::$_options['attributes'][$column['column_name']] : array())));
		$output .= (isset(self::$_options['help'][$column['column_name']]) or isset(self::$_options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset(self::$_options['errors'][$column['column_name']]) ? self::$_options['errors'][$column['column_name']]: self::$_options['help'][$column['column_name']]).'</p>' : '';
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

	/**
	 * enum function.
	 *
	 * @access protected
	 * @static
	 * @param mixed $column
	 * @return void
	 */
	protected static function enum($column)
	{
		$disabled = in_array($column['column_name'], self::$_options['disabled']) ? array('disabled' => true) : array();

		$output = '';

		$options = array();

		foreach($column['options'] as $option)
		{
			$options[$option] = $option;
		}
		unset($option);

		$attributes = array_merge($disabled, (isset(self::$_options['attributes'][$column['column_name']]) ? self::$_options['attributes'][$column['column_name']] : array()));

		if (self::$_object->{$column['column_name']} === NULL)
		{
			$selected = $column['column_default'];
		}
		else
		{
			$selected = self::$_object->{$column['column_name']};
		}

		$output .= '<div class="control-group'.(isset(self::$_options['errors'][$column['column_name']]) ? ' error': '').'">';
		$output .= self::label(array('column_name' => $column['column_name']));
		$output .= '<div class="controls">';
		$output .= Form::select($column['column_name'], $options, $selected, $attributes);
		$output .= (isset(self::$_options['help'][$column['column_name']]) or isset(self::$_options['errors'][$column['column_name']]))
		? '<p class="help-block">'.(isset(self::$_options['errors'][$column['column_name']]) ? self::$_options['errors'][$column['column_name']]: self::$_options['help'][$column['column_name']]).'</p>'
		: '';
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

	/**
	 * select function.
	 *
	 * @access protected
	 * @static
	 * @param mixed $relation
	 * @param bool $multi (default: false)
	 * @return void
	 * @todo add disabled
	 */
	protected static function select($relation, $multi = false)
	{								
		$disabled = in_array($relation['relation_name'], self::$_options['disabled']) ? array('disabled' => true) : array();
		
		$attributes = ($multi) ? array('multiple' => 'multiple') : array();
		
		$attributes['class'] = isset(self::$_options['classes'][$relation['relation_name']]) ? self::$_options['classes'][$relation['relation_name']] : '';
		
		$attributes = array_merge($attributes, (isset(self::$_options['attributes'][$relation['relation_name']]) ? self::$_options['attributes'][$relation['relation_name']] : array()));
		
		$attributes['name'] = $name = $relation['relation_name'];

		if (isset(self::$_options['sources'][$relation['relation_name']])) // An array source is specified
		{
			$options = self::$_options['sources'][$relation['relation_name']];
		}
		else //treat as ORM relation
		{
			if (!$multi)
			{
				$options = array('0' => '-- '.$relation['model'].' --');
			}
			else
			{
				$options = array();				
			}
	
			if( (bool) ORM::factory($relation['model'])->count_all())
			{
				$model = ORM::factory($relation['model']);
				
				$query = ORM::factory($relation['model']);
				
				$attributes['name'] = $name = ($multi) ? $model->object_plural().'[]' : $relation['foreign_key'];
				
				if (isset(self::$_options['filters'][$relation['relation_name']]))
				{
					foreach(self::$_options['filters'][$relation['relation_name']] as $clause => $filters)
					{
						switch ($clause)
						{
							case 'and_where':
								foreach($filters as $filter)
								{
									$query->and_where(
										$filter[0],
										$filter[1],
										$filter[2]
									);	
								}
								break;
							case 'order_by':
								foreach($filters as $filter)
								{
									$query->order_by(
										$filter[0],
										$filter[1]
									);
								}
								break;
						}
					}
				}
				
				$items = $query->find_all();
	
				if (isset(self::$_options['group_by'][$relation['relation_name']]))
				{
					$group = preg_replace("/&#?[a-z0-9]{2,8};/i","",self::$_options['group_by'][$relation['relation_name']]);
	
					foreach($items as $option)
					{
						if(isset(self::$_options['display'][$relation['relation_name']]))
						{
							if (method_exists($option, self::$_options['display'][$relation['relation_name']]))
							{
								$display_value = preg_replace("/&#?[a-z0-9]{2,8};/i","",call_user_func_array(array($option, self::$_options['display'][$relation['relation_name']]), array()));
							}
							else
							{
								$display_value = preg_replace("/&#?[a-z0-9]{2,8};/i","",$option->{self::$_options['display'][$relation['relation_name']]});
							}
						}
						else
						{
							$display_value = preg_replace("/&#?[a-z0-9]{2,8};/i","",(isset($option->name) ? $option->name : $option->{$model->primary_key()}));
						}
						
						$options[$option->{$group}->name][ (string) $option->{$model->primary_key()}] = $display_value;
					}
					unset($option, $display_value);
				}
				else
				{
					foreach($items as $option)
					{
						if(isset(self::$_options['display'][$relation['relation_name']]))
						{
							if (method_exists($option, self::$_options['display'][$relation['relation_name']]))
							{
								$display_value = preg_replace("/&#?[a-z0-9]{2,8};/i","",call_user_func_array(array($option, self::$_options['display'][$relation['relation_name']]), array()));
							}
							else
							{
								$display_value = preg_replace("/&#?[a-z0-9]{2,8};/i","",$option->{self::$_options['display'][$relation['relation_name']]});
							}
						}
						else
						{
							$display_value = preg_replace("/&#?[a-z0-9]{2,8};/i","",(isset($option->name) ? $option->name : $option->{$model->primary_key()}));
						}
						
						$options[ (string) $option->{$model->primary_key()}] = $display_value;
					}
					unset($option);
				}
			}	
		}

		if ($_POST)
		{
			if ($multi)
			{
				$selected = isset($_POST[str_replace('[]','',$name)]) ? $_POST[str_replace('[]','',$name)] : array();
			}
			else
			{
				$selected = isset($_POST[$name]) ? $_POST[$name] : array();
			}
		}
		else
		{
			if (isset(self::$_options['values'][$relation['relation_name']]))
			{			
				$selected = self::$_options['values'][$relation['relation_name']];
			}
			else
			{
				if ($multi)
				{
					$selected = array();
	
					foreach(self::$_object->{$relation['relation_name']}->find_all() as $related)
					{
						array_push($selected, $related->pk());
					}
					unset($related);
				}
				else
				{
					$selected = isset(self::$_object->{$relation['relation_name']}) ? self::$_object->{$relation['relation_name']}->pk() : 0;
				}
			}
		}

		$output = '';
		$output .= '<div class="control-group'.(isset(self::$_options['errors'][$relation['relation_name']]) ? ' error': '').'">';
		$output .= self::label(array('column_name' => $relation['relation_name']));
		$output .= '<div class="controls">';
		$output .= Form::select($name, $options, $selected, $attributes);
		$output .= (isset(self::$_options['help'][$relation['relation_name']]) or isset(self::$_options['errors'][$relation['relation_name']]))
		? '<p class="help-block">'.(isset(self::$_options['errors'][$relation['relation_name']]) ? self::$_options['errors'][$relation['relation_name']]: self::$_options['help'][$relation['relation_name']]).'</p>'
		: '';
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

	/**
	 * radial_group function.
	 *
	 * @access protected
	 * @static
	 * @param mixed $relation
	 * @return void
	 * @todo add disabled
	 */
	protected static function radial_group($relation)
	{
		$output = '';

		if ( (bool) ORM::factory($relation['model'])->count_all())
		{
			$model = ORM::factory($relation['model']);

			$plural = $model->object_plural();

			$name = $relation['foreign_key'];
			
			$query = ORM::factory($relation['model']);
			
			if (isset(self::$_options['filters'][$relation['relation_name']]))
			{
				foreach(self::$_options['filters'][$relation['relation_name']] as $clause => $filters)
				{
					switch ($clause)
					{
						case 'and_where':
							foreach($filters as $filter)
							{
								$query->and_where(
									$filter[0],
									$filter[1],
									$filter[2]
								);	
							}
							break;
						case 'order_by':
							foreach($filters as $filter)
							{
								$query->order_by(
									$filter[0],
									$filter[1]
								);
							}
							break;
					}
				}
			}
			
			$items = $query->find_all();

			$output .= '<div class="control-group">';
	        $output .= self::label(array('column_name' => $relation['relation_name']));
	        $output .= '<div class="controls">';

	        foreach($items as $option)
			{
				if(isset(self::$_options['display'][$relation['relation_name']]))
				{
					if (method_exists($option, self::$_options['display'][$relation['relation_name']]))
					{
						$display_value = preg_replace("/&#?[a-z0-9]{2,8};/i","",call_user_func_array(array($option, self::$_options['display'][$relation['relation_name']]), array()));
					}
					else
					{
						$display_value = preg_replace("/&#?[a-z0-9]{2,8};/i","",$option->{self::$_options['display'][$relation['relation_name']]});
					}
				}
				else
				{
					$display_value = preg_replace("/&#?[a-z0-9]{2,8};/i","",(isset($option->name) ? $option->name : $option->{$model->primary_key()}));
				}
				
		        $output .= '<label class="radio">';
		        $output .= '<input type="radio" name="'.$relation['relation_name'].'[]" id="'.$relation['model'].$option->pk().'" value="'.$option->pk().'" '.(self::$_object->has($plural, $option->pk()) ? 'checked' : false).'>';
				$output .= $display_value;
		        $output .= '</label>';
			}
	        $output .= '</div>';
	        $output .= '</div>';
		}

		return $output;
	}

	/**
	 * checkbox_group function.
	 *
	 * @access protected
	 * @static
	 * @param mixed $relation
	 * @return void
	 * @todo add disabled
	 */
	protected static function checkbox_group($relation)
	{
		$output = '';

		if ( (bool) ORM::factory($relation['model'])->count_all())
		{
			$model = ORM::factory($relation['model']);

			$plural = $model->object_plural();

			$name = (isset($relation['foreign_key'])) ? $plural.'[]' : $relation['foreign_key'];

			$query = ORM::factory($relation['model']);
			
			if (isset(self::$_options['filters'][$relation['relation_name']]))
			{
				foreach(self::$_options['filters'][$relation['relation_name']] as $clause => $filters)
				{
					switch ($clause)
					{
						case 'and_where':
							foreach($filters as $filter)
							{
								$query->and_where(
									$filter[0],
									$filter[1],
									$filter[2]
								);	
							}
							break;
						case 'order_by':
							foreach($filters as $filter)
							{
								$query->order_by(
									$filter[0],
									$filter[1]
								);
							}
							break;
					}
				}
			}
			
			$items = $query->find_all();

			$output .= '<div class="control-group">';
			$output .= self::label(array('column_name' => $relation['relation_name']));
			$output .= '<div class="controls">';

			foreach($items as $option)
			{
				if(isset(self::$_options['display'][$relation['relation_name']]))
				{
					if (method_exists($option, self::$_options['display'][$relation['relation_name']]))
					{
						$display_value = preg_replace("/&#?[a-z0-9]{2,8};/i","",call_user_func_array(array($option, self::$_options['display'][$relation['relation_name']]), array()));
					}
					else
					{
						$display_value = preg_replace("/&#?[a-z0-9]{2,8};/i","",$option->{self::$_options['display'][$relation['relation_name']]});
					}
				}
				else
				{
					$display_value = preg_replace("/&#?[a-z0-9]{2,8};/i","",(isset($option->name) ? $option->name : $option->{$model->primary_key()}));
				}

				$output .= '<label class="checkbox inline">';
				$output .= '<input type="checkbox" name="'.$name.'" id="'.$relation['model'].$option->pk().'" value="'.$option->pk().'" '.(self::$_object->has($plural, $option->pk()) ? 'checked="checked"' : false).'> ';
				$output .= $display_value;
				$output .= '</label>';
			}

			$output .= '</div>';
			$output .= '</div>';
		}

		return $output;
	}
	
	/**
	 * autocomplete function.
	 * 
	 * @access protected
	 * @static
	 * @param mixed $column
	 * @return void
	 */
	protected static function autocomplete($column)
	{
		if (isset(self::$_options['sources'][$column['column_name']]))
		{
			if (Valid::url(self::$_options['sources'][$column['column_name']]))
			{
				$source = (string) file_get_contents(self::$_options['sources'][$column['column_name']]);
			}
			else //should be stringyfied json
			{
				$source = (string) self::$_options['sources'][$column['column_name']];
			}
		}
		
		$disabled = in_array($column['column_name'], self::$_options['disabled']) ? array('disabled' => true) : array();

		$output = '<div class="control-group'.(isset(self::$_options['errors'][$column['column_name']]) ? ' error': '').'">';
		$output .= self::label($column);
		$output .= '<div class="controls">';

		$output .= Form::input($column['column_name'],(isset(self::$_object->{$column['column_name']}) ? self::$_object->{$column['column_name']} : (isset($column['default']) ? $column['default'] : '')),
			Arr::merge(array(
				'data-provide' => 'typeahead',
				'class' => self::$_options['classes'][$column['column_name']],
				'maxlength' => isset($column['character_maximum_length']) ? $column['character_maximum_length'] : 8000,
				'data-source' => $source,
			), $disabled, (isset(self::$_options['attributes'][$column['column_name']]) ? self::$_options['attributes'][$column['column_name']] : array())));

		$output .= (isset(self::$_options['help'][$column['column_name']]) or isset(self::$_options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset(self::$_options['errors'][$column['column_name']]) ? self::$_options['errors'][$column['column_name']]: self::$_options['help'][$column['column_name']]).'</p>' : '';
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

	protected static function label($column, $classes = 'control-label')
	{
		$output = Form::label($column['column_name'], isset(self::$_options['labels'][$column['column_name']]) ? self::$_options['labels'][$column['column_name']] : ucwords($column['column_name']), array('class' => $classes));

		return $output;
	}

	protected static function reset()
	{
		$output = Form::input('reset', 'Cancel', array('type' => 'reset', 'class' => 'btn')).' ';

		return $output;
	}

	protected static function submit()
	{
		$output = Form::submit('save','Save', array('class' => 'btn btn-primary')).' ';

		return $output;
	}
}