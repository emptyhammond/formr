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
		$defaults = array('class' => 'form-horizontal', 'method' => false);

		$options = array_merge($defaults, $array['open']);
		
		$output = Form::open(null, array(
			'enctype' => $array['enctype'], 
			'method' => (isset($options['method']) ? $options['method'] : 'post'), 
			'class' => (isset($options['class']) ? $options['class'] : null),
			'id' => (isset($options['id']) ? $options['id'] : null),
		));

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
	protected static function actions($options)
	{
		$output = '<div class="form-actions">';
		
		if ($options['actions'])
		{
			foreach($options['actions'] as $name => $parameters)
			{
				$parameters = array_merge(array('name' => 'name', 'value' => 'value', 'attributes' => array('class' => 'btn')), $parameters);

				$output .= call_user_func(__NAMESPACE__ .'Self::'.$parameters['type'], $name, $parameters['value'], $parameters['attributes']);
			}
		}
		else
		{
			$output .= self::submit();
			$output .= self::reset();
		}
	
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
	protected static function hidden($column, $object, $options)
	{
		$output = Form::hidden($column['column_name'],(isset($object->{$column['column_name']}) ? $object->{$column['column_name']} : (isset($options['values'][$column['column_name']]) ? $options['values'][$column['column_name']] : (isset($column['default']) ? $column['default'] : ''))), (isset($options['attributes'][$column['column_name']]) ? $options['attributes'][$column['column_name']] : array()));

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
	protected static function number($column, $object, $options)
	{
		$disabled = in_array($column['column_name'], $options['disabled']) ? array('disabled' => true) : array();

		$output = '<div class="control-group'.(isset($options['errors'][$column['column_name']]) ? ' error': '').'">';
		$output .= self::label($column, $options);
		$output .= '<div class="controls">';

		$output .= Form::input($column['column_name'],($object->{$column['column_name']} ? $object->{$column['column_name']} : (isset($column['default']) ? $column['default'] : '')),
			Arr::merge(array(
				'type' => 'number',
				'min' => (isset($column['min']) ? $column['min'] : 0),
				'max' => (isset($column['max']) ? $column['max'] : 99999999999999),
				'step' => '0.01',
				'class' => 'number '.$options['classes'][$column['column_name']],
			), $disabled, (isset($options['attributes'][$column['column_name']]) ? $options['attributes'][$column['column_name']] : array())));

		$output .= (isset($options['help'][$column['column_name']]) or isset($options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset($options['errors'][$column['column_name']]) ? $options['errors'][$column['column_name']]: $options['help'][$column['column_name']]).'</p>' : '';

		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}
	
	protected static function int($column, $object, $options)
	{
		$disabled = in_array($column['column_name'], $options['disabled']) ? array('disabled' => true) : array();

		$output = '<div class="control-group'.(isset($options['errors'][$column['column_name']]) ? ' error': '').'">';
		$output .= self::label($column, $options);
		$output .= '<div class="controls">';

		$output .= Form::input($column['column_name'],($object->{$column['column_name']} ? $object->{$column['column_name']} : (isset($column['default']) ? $column['default'] : '')),
			Arr::merge(array(
				'type' => 'number',
				'min' => (isset($column['min']) ? $column['min'] : 0),
				'max' => (isset($column['max']) ? $column['max'] : 99999999999999),
				'step' => '1',
				'class' => 'number '.(isset($options['classes'][$column['column_name']]) ? $options['classes'][$column['column_name']] : ''),
			), $disabled, (isset($options['attributes'][$column['column_name']]) ? $options['attributes'][$column['column_name']] : array())));

		$output .= (isset($options['help'][$column['column_name']]) or isset($options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset($options['errors'][$column['column_name']]) ? $options['errors'][$column['column_name']]: $options['help'][$column['column_name']]).'</p>' : '';

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
	protected static function date($column, $object, $options)
	{
		$disabled = in_array($column['column_name'], $options['disabled']) ? array('disabled' => true) : array();

		if ($object->{$column['column_name']} and is_numeric($object->{$column['column_name']}))
		{
			$value = date('d-m-Y',$object->{$column['column_name']});
		}
		elseif (isset($options['values'][$column['column_name']]))
		{
			$value = date('d-m-Y',$options['values'][$column['column_name']]);
		}
		elseif(isset($column['default']))
		{
			$value = $column['default'];
		}
		else
		{
			$value = '';
		}

		$output = '<div class="control-group'.(isset($options['errors'][$column['column_name']]) ? ' error': '').'">';
		$output .= self::label($column, $options);
		$output .= '<div class="controls">';

		$output .= Form::input($column['column_name'], $value,
			Arr::merge(array(
				'autocomplete' => 'off',			
				'type' => 'date',
				'class' => 'date '.$options['classes'][$column['column_name']],
			), $disabled, (isset($options['attributes'][$column['column_name']]) ? $options['attributes'][$column['column_name']] : array())));

		$output .= (isset($options['help'][$column['column_name']]) or isset($options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset($options['errors'][$column['column_name']]) ? $options['errors'][$column['column_name']]: $options['help'][$column['column_name']]).'</p>' : '';

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
	protected static function datetime($column, $object, $options)
	{
		$disabled = in_array($column['column_name'], $options['disabled']) ? array('disabled' => true) : array();
		
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
		
		if ($object->{$column['column_name']} and is_numeric($object->{$column['column_name']}))
		{
			$value = date('d-m-Y',$object->{$column['column_name']});
		}
		elseif (isset($options['values'][$column['column_name']]))
		{
			$value = date('d-m-Y',$options['values'][$column['column_name']]);
		}
		elseif(isset($column['default']))
		{
			$value = $column['default'];
		}
		else
		{
			$value = '';
		}
		
		$output = '<div class="control-group form-inline '.(isset($options['errors'][$column['column_name']]) ? ' error': '').'">';
		
		$output .= self::label($column, $options);
		
		$output .= '<div class="controls">';
		
		$output .= Form::input($column['column_name'], $value,
			Arr::merge(array(
				'autocomplete' => 'off',
				'type' => 'date',
				'class' => 'date '.$options['classes'][$column['column_name']],
			), $disabled, (isset($options['attributes'][$column['column_name']]) ? $options['attributes'][$column['column_name']] : array())));

		$output .= ' '.Form::select('hour['.$column['column_name'].']', $hours, ($object->{$column['column_name']} ? date('H',$object->{$column['column_name']}) : '09'), array('type' => 'number', 'class' => 'input-mini hour-datetime'));
		
		$output .= ' <label class="label-datetime" for=":">:</label> ';
		
		$output .= Form::select('minute['.$column['column_name'].']', $minutes, ($object->{$column['column_name']} ? date('i',$object->{$column['column_name']}) : '00'), array('type' => 'number', 'class' => 'input-mini minute-datetime'));

		$output .= (isset($options['help'][$column['column_name']]) or isset($options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset($options['errors'][$column['column_name']]) ? $options['errors'][$column['column_name']]: $options['help'][$column['column_name']]).'</p>' : '';

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
	protected static function time($column, $object, $options)
	{
		$disabled = in_array($column['column_name'], $options['disabled']) ? array('disabled' => true) : array();

		$value = ($object->{$column['column_name']}
		? (is_numeric($object->{$column['column_name']}) ? date('m/d/Y',$object->{$column['column_name']}) : $object->{$column['column_name']})
		: (isset($column['default']) ? $column['default'] : ''));

		$output = '<div class="control-group'.(isset($options['errors'][$column['column_name']]) ? ' error': '').'">';
		$output .= self::label($column, $options);
		$output .= '<div class="controls">';

		$output .= Form::input($column['column_name'], $value,
			Arr::merge(array(
				'autocomplete' => 'off',			
				'type' => 'time',
				'class' => 'time '.$options['classes'][$column['column_name']],
			), $disabled, (isset($options['attributes'][$column['column_name']]) ? $options['attributes'][$column['column_name']] : array())));

		$output .= (isset($options['help'][$column['column_name']]) or isset($options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset($options['errors'][$column['column_name']]) ? $options['errors'][$column['column_name']]: $options['help'][$column['column_name']]).'</p>' : '';

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
	protected static function email($column, $object, $options)
	{
		$disabled = in_array($column['column_name'], $options['disabled']) ? array('disabled' => true) : array();

		$value = ($object->{$column['column_name']}
		? $object->{$column['column_name']}
		: (isset($column['default']) ? $column['default'] : ''));

		$output = '<div class="control-group'.(isset($options['errors'][$column['column_name']]) ? ' error': '').'">';
		$output .= self::label($column, $options);
		$output .= '<div class="controls">';

		$output .= Form::input($column['column_name'], $value,
			Arr::merge(array(
				'type' => 'email',
				'class' => 'email '.$options['classes'][$column['column_name']],
			), $disabled, (isset($options['attributes'][$column['column_name']]) ? $options['attributes'][$column['column_name']] : array())));

		$output .= (isset($options['help'][$column['column_name']]) or isset($options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset($options['errors'][$column['column_name']]) ? $options['errors'][$column['column_name']]: $options['help'][$column['column_name']]).'</p>' : '';

		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}
	
	protected static function color($column, $object, $options)
	{
		$disabled = in_array($column['column_name'], $options['disabled']) ? array('disabled' => true) : array();

		$value = ($object->{$column['column_name']}
		? $object->{$column['column_name']}
		: (isset($column['default']) ? $column['default'] : ''));

		$output = '<div class="control-group'.(isset($options['errors'][$column['column_name']]) ? ' error': '').'">';
		$output .= self::label($column, $options);
		$output .= '<div class="controls">';

		$output .= Form::input($column['column_name'], $value,
			Arr::merge(array(
				'type' => 'color',
				'class' => 'color '.$options['classes'][$column['column_name']],
				'style' => 'height: 20px; width: 20px;'
			), $disabled, (isset($options['attributes'][$column['column_name']]) ? $options['attributes'][$column['column_name']] : array())));

		$output .= (isset($options['help'][$column['column_name']]) or isset($options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset($options['errors'][$column['column_name']]) ? $options['errors'][$column['column_name']]: $options['help'][$column['column_name']]).'</p>' : '';

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
	protected static function varchar($column, $object, $options)
	{
		$disabled = in_array($column['column_name'], $options['disabled']) ? array('disabled' => true) : array();

		$output = '<div class="control-group'.(isset($options['errors'][$column['column_name']]) ? ' error': '').'">';
		$output .= self::label($column, $options);
		$output .= '<div class="controls">';
		
		if ((boolean) isset($object->{$column['column_name']}) and $object->{$column['column_name']})
		{
			$value = $object->{$column['column_name']};
		}		
		elseif (isset($options['values'][$column['column_name']]))
		{
			$value = $options['values'][$column['column_name']];	
		}
		elseif (isset($column['default']))
		{
			$value = $column['default'];
		}
		else
		{
			$value = '';
		}
		
		$output .= Form::input($column['column_name'],$value,
			Arr::merge(array(
				'class' => $options['classes'][$column['column_name']],
				'maxlength' => isset($column['character_maximum_length']) ? $column['character_maximum_length'] : '8000',
			), $disabled, (isset($options['attributes'][$column['column_name']]) ? $options['attributes'][$column['column_name']] : array())));

		$output .= (isset($options['help'][$column['column_name']]) or isset($options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset($options['errors'][$column['column_name']]) ? $options['errors'][$column['column_name']]: $options['help'][$column['column_name']]).'</p>' : '';
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
	protected static function password($column, $object, $options)
	{
		$disabled = in_array($column['column_name'], $options['disabled']) ? array('disabled' => true) : array();

		$output = '<div class="control-group'.(isset($options['errors'][$column['column_name']]) ? ' error': '').'">';
		$output .= self::label($column, $options);
		$output .= '<div class="controls">';

		$output .= Form::password($column['column_name'], null,
			Arr::merge(array(
				'class' => $options['classes'][$column['column_name']],
				'maxlength' => isset($column['character_maximum_length']) ? $column['character_maximum_length'] : 255,
			), $disabled, (isset($options['attributes'][$column['column_name']]) ? $options['attributes'][$column['column_name']] : array())));

		$output .= (isset($options['help'][$column['column_name']]) or isset($options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset($options['errors'][$column['column_name']]) ? $options['errors'][$column['column_name']]: $options['help'][$column['column_name']]).'</p>' : '';
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
	protected static function text($column, $object, $options)
	{
		$disabled = in_array($column['column_name'], $options['disabled']) ? array('disabled' => true) : array();

		$output = '<div class="control-group'.(isset($options['errors'][$column['column_name']]) ? ' error': '').'">';
		$output .= self::label($column, $options);
		$output .= '<div class="controls">';

		if ((boolean) $object->{$column['column_name']})
		{
			$value = $object->{$column['column_name']};
		}		
		elseif (isset($options['values'][$column['column_name']]))
		{
			$value = $options['values'][$column['column_name']];
		}
		elseif (isset($column['default']))
		{
			$value = $column['default'];
		}
		else
		{
			$value = '';
		}
		
		$output .= Form::textarea($column['column_name'], $value,
			Arr::merge(array(
				'class' => (isset($options['classes'][$column['column_name']]) ? $options['classes'][$column['column_name']] : ''),
				'maxlength' => isset($column['character_maximum_length']) ? $column['character_maximum_length'] : 8000,
			), $disabled, (isset($options['attributes'][$column['column_name']]) ? $options['attributes'][$column['column_name']] : array())));

		$output .= (isset($options['help'][$column['column_name']]) or isset($options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset($options['errors'][$column['column_name']]) ? $options['errors'][$column['column_name']]: $options['help'][$column['column_name']]).'</p>' : '';
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
	protected static function checkbox($column, $object, $options)
	{
		$disabled = in_array($column['column_name'], $options['disabled']) ? array('disabled' => true) : array();

		$output  = '<div class="control-group">';
        $output .= self::label($column, $options);
        $output .= '<div class="controls">';
		$output .= '<label class="checkbox inline">';
		$output .= '<input type="checkbox" name="'.$column['column_name'].'" id="'.$column['column_name'].'" value="1"'.((isset($object->{$column['column_name']}) and (boolean) $object->{$column['column_name']}) ? ' checked' : ((isset($options['values'][$column['column_name']]) and $options['values'][$column['column_name']]) ? ' checked' : null)).' class="'.$options['classes'][$column['column_name']].(isset($options['disabled'][$column['column_name']]) ? ' disabled' : false).' "/>';
		$output .= '</label>';
		
		$output .= (isset($options['help'][$column['column_name']]) or isset($options['errors'][$column['column_name']])) ? (isset($options['errors'][$column['column_name']]) ? '<p class="help-block">'.$options['errors'][$column['column_name']].'</p>' : '<p class="help-block">'.$options['help'][$column['column_name']].'</p>') : '';
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
	protected static function file($column, $object, $options)
	{
		$disabled = in_array($column['column_name'], $options['disabled']) ? array('disabled' => true) : array();

		$output = '<div class="control-group'.(isset($options['errors'][$column['column_name']]) ? ' error': '').'">';
		$output .= self::label($column, $options);
		$output .= '<div class="controls">';
		$output .= Form::file($column['column_name'],
			Arr::merge(array(
				'class' => 'input-file '.$options['classes'][$column['column_name']],
			), $disabled, (isset($options['attributes'][$column['column_name']]) ? $options['attributes'][$column['column_name']] : array())));
		$output .= (isset($options['help'][$column['column_name']]) or isset($options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset($options['errors'][$column['column_name']]) ? $options['errors'][$column['column_name']]: $options['help'][$column['column_name']]).'</p>' : '';
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
	protected static function enum($column, $object, $options)
	{
		$disabled = in_array($column['column_name'], $options['disabled']) ? array('disabled' => true) : array();

		$output = '';

		$options = array();

		foreach($column['options'] as $option)
		{
			$options[$option] = $option;
		}
		unset($option);

		$attributes = array_merge($disabled, (isset($options['attributes'][$column['column_name']]) ? $options['attributes'][$column['column_name']] : array()));

		if ($object->{$column['column_name']} === NULL)
		{
			$selected = $column['column_default'];
		}
		else
		{
			$selected = $object->{$column['column_name']};
		}

		$output .= '<div class="control-group'.(isset($options['errors'][$column['column_name']]) ? ' error': '').'">';
		$output .= self::label(array('column_name' => $column['column_name']), $options);
		$output .= '<div class="controls">';
		$output .= Form::select($column['column_name'], $options, $selected, $attributes);
		$output .= (isset($options['help'][$column['column_name']]) or isset($options['errors'][$column['column_name']]))
		? '<p class="help-block">'.(isset($options['errors'][$column['column_name']]) ? $options['errors'][$column['column_name']]: $options['help'][$column['column_name']]).'</p>'
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
	protected static function select($relation, $multi = false, $object, $options)
	{
		$disabled = in_array($relation['relation_name'], $options['disabled']) ? array('disabled' => true) : array();
		
		$attributes = ($multi) ? array('multiple' => 'multiple') : array();
		
		$attributes['class'] = isset($options['classes'][$relation['relation_name']]) ? $options['classes'][$relation['relation_name']] : '';
		
		$attributes = array_merge($attributes, (isset($options['attributes'][$relation['relation_name']]) ? $options['attributes'][$relation['relation_name']] : array()));
		
		$attributes['name'] = $name = $relation['relation_name'];

		if (isset($options['sources'][$relation['relation_name']])) // An array source is specified
		{
			$opts = $options['sources'][$relation['relation_name']];
		}
		else //treat as ORM relation
		{
			if (!$multi)
			{
				$opts = array('' => '-- '.$relation['model'].' --');
			}
			else
			{
				$opts = array();				
			}
	
			if( (bool) ORM::factory($relation['model'])->count_all())
			{
				$model = ORM::factory($relation['model']);
				
				$query = ORM::factory($relation['model']);
				
				$attributes['name'] = $name = ($multi) ? $name.'[]' : $relation['foreign_key'];
				
				if (isset($options['filters'][$relation['relation_name']]))
				{
					foreach($options['filters'][$relation['relation_name']] as $clause => $filters)
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
	
				if (isset($options['group_by'][$relation['relation_name']]))
				{
					$group = preg_replace("/&#?[a-z0-9]{2,8};/i","",$options['group_by'][$relation['relation_name']]);
	
					foreach($items as $option)
					{
						if(isset($options['display'][$relation['relation_name']]))
						{
							if (method_exists($option, $options['display'][$relation['relation_name']]))
							{
								$display_value = preg_replace("/&#?[a-z0-9]{2,8};/i","",call_user_func_array(array($option, $options['display'][$relation['relation_name']]), array()));
							}
							else
							{
								$display_value = preg_replace("/&#?[a-z0-9]{2,8};/i","",$option->{$options['display'][$relation['relation_name']]});
							}
						}
						else
						{
							$display_value = preg_replace("/&#?[a-z0-9]{2,8};/i","",(isset($option->name) ? $option->name : $option->{$model->primary_key()}));
						}
						
						$opts[$option->{$group}->name][ (string) $option->{$model->primary_key()}] = $display_value;
					}
					unset($option, $display_value);
				}
				else
				{
					foreach($items as $option)
					{
						if(isset($options['display'][$relation['relation_name']]))
						{
							if (method_exists($option, $options['display'][$relation['relation_name']]))
							{
								$display_value = preg_replace("/&#?[a-z0-9]{2,8};/i","",call_user_func_array(array($option, $options['display'][$relation['relation_name']]), array()));
							}
							else
							{
								$display_value = preg_replace("/&#?[a-z0-9]{2,8};/i","",$option->{$options['display'][$relation['relation_name']]});
							}
						}
						else
						{
							$display_value = preg_replace("/&#?[a-z0-9]{2,8};/i","",(isset($option->name) ? $option->name : $option->{$model->primary_key()}));
						}
						
						$opts[ (string) $option->{$model->primary_key()}] = $display_value;
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
			if (isset($options['values'][$relation['relation_name']]))
			{			
				$selected = $options['values'][$relation['relation_name']];
			}
			else
			{
				if ($multi)
				{
					$selected = array();
	
					foreach($object->{$relation['relation_name']}->find_all() as $related)
					{
						array_push($selected, $related->pk());
					}
					unset($related);
				}
				else
				{
					$selected = isset($object->{$relation['relation_name']}) ? $object->{$relation['relation_name']}->pk() : 0;
				}
			}
		}

		$output = '';
		$output .= '<div class="control-group'.(isset($options['errors'][$relation['relation_name']]) ? ' error': '').'">';
		$output .= self::label(array('column_name' => $relation['relation_name']), $options);
		$output .= '<div class="controls">';
		$output .= Form::select($name, $opts, $selected, $attributes);
		$output .= (isset($options['help'][$relation['relation_name']]) or isset($options['errors'][$relation['relation_name']]))
		? '<p class="help-block">'.(isset($options['errors'][$relation['relation_name']]) ? $options['errors'][$relation['relation_name']]: $options['help'][$relation['relation_name']]).'</p>'
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
	protected static function radial_group($relation, $object, $options)
	{
		$output = '';

		if ( (bool) ORM::factory($relation['model'])->count_all())
		{
			$model = ORM::factory($relation['model']);

			$plural = $model->object_plural();

			$name = $relation['foreign_key'];
			
			$query = ORM::factory($relation['model']);
			
			if (isset($options['filters'][$relation['relation_name']]))
			{
				foreach($options['filters'][$relation['relation_name']] as $clause => $filters)
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
				if(isset($options['display'][$relation['relation_name']]))
				{
					if (method_exists($option, $options['display'][$relation['relation_name']]))
					{
						$display_value = preg_replace("/&#?[a-z0-9]{2,8};/i","",call_user_func_array(array($option, $options['display'][$relation['relation_name']]), array()));
					}
					else
					{
						$display_value = preg_replace("/&#?[a-z0-9]{2,8};/i","",$option->{$options['display'][$relation['relation_name']]});
					}
				}
				else
				{
					$display_value = preg_replace("/&#?[a-z0-9]{2,8};/i","",(isset($option->name) ? $option->name : $option->{$model->primary_key()}));
				}
				
		        $output .= '<label class="radio">';
		        $output .= '<input type="radio" name="'.$relation['relation_name'].'[]" id="'.$relation['model'].$option->pk().'" value="'.$option->pk().'" '.($object->has($plural, $option->pk()) ? 'checked' : false).'>';
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
	protected static function checkbox_group($relation, $object, $options)
	{
		$output = '';

		if ( (bool) ORM::factory($relation['model'])->count_all())
		{
			$model = ORM::factory($relation['model']);

			$plural = $model->object_plural();

			$name = (isset($relation['foreign_key'])) ? $plural.'[]' : $relation['foreign_key'];

			$query = ORM::factory($relation['model']);
			
			if (isset($options['filters'][$relation['relation_name']]))
			{
				foreach($options['filters'][$relation['relation_name']] as $clause => $filters)
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
			$output .= self::label(array('column_name' => $relation['relation_name']), $options);
			$output .= '<div class="controls">';

			foreach($items as $option)
			{
				if(isset($options['display'][$relation['relation_name']]))
				{
					if (method_exists($option, $options['display'][$relation['relation_name']]))
					{
						$display_value = preg_replace("/&#?[a-z0-9]{2,8};/i","",call_user_func_array(array($option, $options['display'][$relation['relation_name']]), array()));
					}
					else
					{
						$display_value = preg_replace("/&#?[a-z0-9]{2,8};/i","",$option->{$options['display'][$relation['relation_name']]});
					}
				}
				else
				{
					$display_value = preg_replace("/&#?[a-z0-9]{2,8};/i","",(isset($option->name) ? $option->name : $option->{$model->primary_key()}));
				}

				$output .= '<label class="checkbox inline">';
				$output .= '<input type="checkbox" name="'.$name.'" id="'.$relation['model'].$option->pk().'" value="'.$option->pk().'"';
				$output .= (!$_POST and $object->has($plural, $option->pk())) ? ' checked="checked"' : '';
				$output .= (isset($_POST[$plural]) and in_array($option->pk(),$_POST[$plural])) ? ' checked="checked"' : '';
				$output .= ' />';
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
	protected static function autocomplete($column, $object, $options)
	{
		if (isset($options['sources'][$column['column_name']]))
		{
			if (Valid::url($options['sources'][$column['column_name']]))
			{
				$source = (string) file_get_contents($options['sources'][$column['column_name']]);
			}
			else //should be stringyfied json
			{
				$source = (string) $options['sources'][$column['column_name']];
			}
		}
		
		$disabled = in_array($column['column_name'], $options['disabled']) ? array('disabled' => true) : array();

		$output = '<div class="control-group'.(isset($options['errors'][$column['column_name']]) ? ' error': '').'">';
		$output .= self::label($column, $options);
		$output .= '<div class="controls">';

		$output .= Form::input($column['column_name'],(isset($object->{$column['column_name']}) ? $object->{$column['column_name']} : (isset($column['default']) ? $column['default'] : '')),
			Arr::merge(array(
				'data-provide' => 'typeahead',
				'class' => isset($options['classes'][$column['column_name']]) ? $options['classes'][$column['column_name']] : '',
				'maxlength' => isset($column['character_maximum_length']) ? $column['character_maximum_length'] : 8000,
				'data-source' => $source,
			), $disabled, (isset($options['attributes'][$column['column_name']]) ? $options['attributes'][$column['column_name']] : array())));

		$output .= (isset($options['help'][$column['column_name']]) or isset($options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset($options['errors'][$column['column_name']]) ? $options['errors'][$column['column_name']]: $options['help'][$column['column_name']]).'</p>' : '';
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}
	
	/**
	 * searchcomplete function.
	 * 
	 * @access protected
	 * @static
	 * @param mixed $column
	 * @param mixed $object
	 * @param mixed $options
	 * @return void
	 *
	 * Requires the following JS to work
		 
	 */
	protected static function searchcomplete($column, $object, $options)
	{
		if (isset($options['sources'][$column['column_name']]))
		{
			if (is_string($options['sources'][$column['column_name']]) and Valid::url($options['sources'][$column['column_name']]))
			{
				$source = (string) file_get_contents($options['sources'][$column['column_name']]);
			}
			else //should be stringyfied json
			{
				$source = (string) $options['sources'][$column['column_name']];
			}
		}
		
		$id = (isset($object->{$column['column_name']}) and $object->{$column['column_name']}->loaded()) ? $object->{$column['column_name']} : (isset($options['values'][$column['relation_name']]) ? $options['values'][$column['relation_name']] : (isset($column['default']) ? $column['default'] : ''));
		
		$object = ORM::factory($column['model'], $id);
		
		$disabled = in_array($column['column_name'], $options['disabled']) ? array('disabled' => true) : array();

		$output = '<div class="control-group'.(isset($options['errors'][$column['column_name']]) ? ' error': '').'">';
		$output .= self::label($column, $options);
		$output .= '<div class="controls">';
		$output .= Form::hidden($column['column_name'],$object->id);
		$output .= Form::input($column['column_name'].'-selector',$object->name,
			Arr::merge(array(
				'class' => isset($options['classes'][$column['column_name']]) ? $options['classes'][$column['column_name']] : '',
				'maxlength' => isset($column['character_maximum_length']) ? $column['character_maximum_length'] : 8000,
				'data-source' => $source,
			), $disabled, (isset($options['attributes'][$column['column_name']]) ? $options['attributes'][$column['column_name']] : array()),array('class' => 'searchcomplete')));

		$output .= (isset($options['help'][$column['column_name']]) or isset($options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset($options['errors'][$column['column_name']]) ? $options['errors'][$column['column_name']]: $options['help'][$column['column_name']]).'</p>' : '';
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

	/**
	 * label function.
	 * 
	 * @access protected
	 * @static
	 * @param mixed $column
	 * @param mixed $options
	 * @return void
	 */
	protected static function label($column, $options)
	{
		$options['classes'] = null;
		
		$output = Form::label($column['column_name'], isset($options['labels'][$column['column_name']]) ? $options['labels'][$column['column_name']] : ucwords($column['column_name']), array('class' => 'control-label '.(isset($options['classes'][$column['column_name']]) ? $options['classes'][$column['column_name']] : '')));

		return $output;
	}

	/**
	 * reset function.
	 * 
	 * @access protected
	 * @static
	 * @param string $name (default: 'reset')
	 * @param string $value (default: 'Reset')
	 * @param string $attributes (default: array('class' => 'btn'))
	 * @return void
	 */
	protected static function reset($name = 'reset', $value = 'Reset', $attributes = array('class' => 'btn'))
	{
		$attributes = array_merge(array('type' => 'reset'),$attributes);
		
		$output = Form::input($name, $value, $attributes).' ';

		return $output;
	}

	/**
	 * submit function.
	 * 
	 * @access protected
	 * @static
	 * @param string $name (default: 'save')
	 * @param string $value (default: 'Save')
	 * @param string $attributes (default: array('class' => 'btn btn-primary'))
	 * @return void
	 */
	protected static function submit($name = 'save', $value = 'Save', $attributes = array('class' => 'btn btn-primary'))
	{
		$output = Form::submit($name, $value, $attributes).' ';

		return $output;
	}
	
	/**
	 * button function.
	 * 
	 * @access protected
	 * @static
	 * @param string $name (default: 'save')
	 * @param string $value (default: 'Save')
	 * @param string $attributes (default: array('class' => 'btn'))
	 * @return void
	 */
	protected static function button($name = 'save', $value = 'Save', $attributes = array('class' => 'btn'))
	{
		$output = Form::button($name, $value, $attributes).' ';

		return $output;
	}
}
