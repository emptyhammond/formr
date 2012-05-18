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
		$defaults = array('enctype' => 'application/x-www-form-urlencoded', 'class' => 'well form-horizontal', 'method' => false, 'legend' => ucwords(self::$_object->object_name()));
		
		$options = array_merge($defaults, $array);
		
		$string = Form::open(null, array('enctype' => $options['enctype'], 'method' => ($options['method'] ? $options['method'] : 'post'), 'class' => $options['class']));
		$string .= '<fieldset>';
		$string .= '<legend>'.$options['legend'].'</legend>';
		
		return $string;
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
		$string = '</fieldset>';
		$string .= '</form>';
		
		return $string;
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
		$string = '<div class="form-actions">';
		$string .= self::submit();
		$string .= self::reset();
		$string .= '</div>';
		
		return $string;
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
		$string = Form::hidden($column['column_name'],(self::$_object->{$column['column_name']} ? self::$_object->{$column['column_name']} : (isset($column['default']) ? $column['default'] : '')));
		
		return $string;
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
		
		$string = '<div class="control-group'.(isset(self::$_options['errors'][$column['column_name']]) ? ' error': '').'">';
		$string .= self::label($column);
		$string .= '<div class="controls">';
		
		$string .= Form::input($column['column_name'],(self::$_object->{$column['column_name']} ? self::$_object->{$column['column_name']} : (isset($column['default']) ? $column['default'] : '')), 
			Arr::merge(array(
				'type' => 'number', 
				'min' => (isset($column['min']) ? $column['min'] : 0), 
				'max' => (isset($column['max']) ? $column['max'] : 99999999999999),
				'step' => '0.01',
				'class' => 'number '.self::$_options['classes'][$column['column_name']],
			), $disabled));
		
		$string .= (isset(self::$_options['help'][$column['column_name']]) or isset(self::$_options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset(self::$_options['errors'][$column['column_name']]) ? self::$_options['errors'][$column['column_name']]: self::$_options['help'][$column['column_name']]).'</p>' : '';
		
		$string .= '</div>';
		$string .= '</div>';
		
		return $string;
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
		? (is_numeric(self::$_object->{$column['column_name']}) ? date('m/d/Y',self::$_object->{$column['column_name']}) : self::$_object->{$column['column_name']})
		: (isset($column['default']) ? $column['default'] : ''));
		
		$string = '<div class="control-group'.(isset(self::$_options['errors'][$column['column_name']]) ? ' error': '').'">';
		$string .= self::label($column);
		$string .= '<div class="controls">';
		
		$string .= Form::input($column['column_name'], $value, 
			Arr::merge(array(
				'type' => 'date',
				'class' => 'date '.self::$_options['classes'][$column['column_name']],
			), $disabled));
		
		$string .= (isset(self::$_options['help'][$column['column_name']]) or isset(self::$_options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset(self::$_options['errors'][$column['column_name']]) ? self::$_options['errors'][$column['column_name']]: self::$_options['help'][$column['column_name']]).'</p>' : '';
		
		$string .= '</div>';
		$string .= '</div>';
		
		return $string;
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
		
		$string = '<div class="control-group'.(isset(self::$_options['errors'][$column['column_name']]) ? ' error': '').'">';
		$string .= self::label($column);
		$string .= '<div class="controls">';
		
		$string .= Form::input($column['column_name'], $value, 
			Arr::merge(array(
				'type' => 'email',
				'class' => 'email '.self::$_options['classes'][$column['column_name']],
			), $disabled));
		
		$string .= (isset(self::$_options['help'][$column['column_name']]) or isset(self::$_options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset(self::$_options['errors'][$column['column_name']]) ? self::$_options['errors'][$column['column_name']]: self::$_options['help'][$column['column_name']]).'</p>' : '';
		
		$string .= '</div>';
		$string .= '</div>';
		
		return $string;
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
		
		$string = '<div class="control-group'.(isset(self::$_options['errors'][$column['column_name']]) ? ' error': '').'">';
		$string .= self::label($column);
		$string .= '<div class="controls">';
		
		$string .= Form::input($column['column_name'],(self::$_object->{$column['column_name']} ? self::$_object->{$column['column_name']} : (isset($column['default']) ? $column['default'] : '')),
			Arr::merge(array(
				'class' => self::$_options['classes'][$column['column_name']],
				'maxlength' => $column['character_maximum_length'],
			), $disabled));
		
		$string .= (isset(self::$_options['help'][$column['column_name']]) or isset(self::$_options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset(self::$_options['errors'][$column['column_name']]) ? self::$_options['errors'][$column['column_name']]: self::$_options['help'][$column['column_name']]).'</p>' : '';
		$string .= '</div>';
		$string .= '</div>';
		
		return $string;
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
		
		$string = '<div class="control-group'.(isset(self::$_options['errors'][$column['column_name']]) ? ' error': '').'">';
		$string .= self::label($column); 
		$string .= '<div class="controls">';
		
		$string .= Form::textarea($column['column_name'],(self::$_object->{$column['column_name']} ? self::$_object->{$column['column_name']} : (isset($column['default']) ? $column['default'] : '')), 
			Arr::merge(array(
				'class' => self::$_options['classes'][$column['column_name']],
				'maxlength' => $column['character_maximum_length'],
			), $disabled));
		
		$string .= (isset(self::$_options['help'][$column['column_name']]) or isset(self::$_options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset(self::$_options['errors'][$column['column_name']]) ? self::$_options['errors'][$column['column_name']]: self::$_options['help'][$column['column_name']]).'</p>' : '';
		$string .= '</div>';
		$string .= '</div>';
		
		return $string;
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
		
		$string  = '<div class="control-group">';
        $string .= '<label class="control-label" for="'.$column['column_name'].'">'.ucwords($column['column_name']).'</label>';
        $string .= '<div class="controls">';
		$string .= '<label class="checkbox">';
		
		$string .= '<input type="checkbox" name="'.$column['column_name'].'" id="'.$column['column_name'].'" value="1"'.( (boolean) self::$_object->{$column['column_name']} ? 'checked' : false).' class="'.self::$_options['classes'][$column['column_name']].(isset(self::$_options['disabled'][$column['column_name']]) ? ' disabled' : false).' "/>';
		
        $string .= (isset(self::$_options['help'][$column['column_name']]) or isset(self::$_options['errors'][$column['column_name']])) ? (isset(self::$_options['errors'][$column['column_name']]) ? self::$_options['errors'][$column['column_name']]: self::$_options['help'][$column['column_name']]) : '';
		$string .= '</label>';
		$string .= '</div>';
		$string .= '</div>';
		
		return $string;
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
		
		$string = '<div class="control-group'.(isset(self::$_options['errors'][$column['column_name']]) ? ' error': '').'">';
		$string .= self::label($column);
		$string .= '<div class="controls">';
		$string .= Form::file($column['column_name'], 
			Arr::merge(array(
				'class' => 'input-file '.self::$_options['classes'][$column['column_name']],
			), $disabled));
		$string .= (isset(self::$_options['help'][$column['column_name']]) or isset(self::$_options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset(self::$_options['errors'][$column['column_name']]) ? self::$_options['errors'][$column['column_name']]: self::$_options['help'][$column['column_name']]).'</p>' : '';
		$string .= '</div>';
		$string .= '</div>';
		
		return $string;
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
		
		$string = '';
		
		$options = array();
		
		foreach($column['options'] as $option)
		{
			$options[$option] = $option;
		}
		unset($option);
		
		$attributes = $disabled;
		
		if (self::$_object->{$column['column_name']} === NULL)
		{
			$selected = array($column['column_default']);
		}
		else
		{
			$selected = array(self::$_object->{$column['column_name']});
		}
		
		$string .= '<div class="control-group'.(isset(self::$_options['errors'][$column['column_name']]) ? ' error': '').'">';
		$string .= self::label(array('column_name' => $column['column_name']));
		$string .= '<div class="controls">';
		$string .= Form::select($column['column_name'], $options, $selected, $attributes);
		$string .= (isset(self::$_options['help'][$column['column_name']]) or isset(self::$_options['errors'][$column['column_name']])) 
		? '<p class="help-block">'.(isset(self::$_options['errors'][$column['column_name']]) ? self::$_options['errors'][$column['column_name']]: self::$_options['help'][$column['column_name']]).'</p>' 
		: '';
		$string .= '</div>';
		$string .= '</div>';
		
		return $string;
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
		$disabled = in_array($relation['model'], self::$_options['disabled']) ? array('disabled' => true) : array();
		
		$string = '';
		
		if( (bool) ORM::factory($relation['model'])->count_all())
		{
			if ($multi)
			{
				$options = array();			
			}
			else
			{
				$options = array('0' => '-- '.$relation['model'].' --');
			}
			
			$model = ORM::factory($relation['model']);

			if (isset(self::$_options['group_by'][$relation['relation_name']]))
			{
				$group = self::$_options['group_by'][$relation['relation_name']];
				
				foreach(ORM::factory($relation['model'])->find_all() as $option)
				{
					$options[$option->{$group}->name][ (string) $option->{$model->primary_key()}] = $option->name;
				}
				unset($option);
			}
			else
			{
				foreach(ORM::factory($relation['model'])->find_all() as $option)
				{
					$options[ (string) $option->{$model->primary_key()}] = $option->name;
				}
				unset($option);
			}
			
			$attributes = ($multi) ? array('multiple' => 'multiple') : array();
			
			$attributes['name'] = $name = ($multi) ? $model->object_plural().'[]' : $relation['foreign_key'];
			
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
					$selected = self::$_object->{$relation['relation_name']}->pk();
				}
			}
			
			$string .= '<div class="control-group'.(isset(self::$_options['errors'][$relation['model']]) ? ' error': '').'">';
			$string .= self::label(array('column_name' => $relation['relation_name']));
			$string .= '<div class="controls">';
			$string .= Form::select($name, $options, $selected, $attributes);
			$string .= (isset(self::$_options['help'][$relation['model']]) or isset(self::$_options['errors'][$relation['model']])) 
			? '<p class="help-block">'.(isset(self::$_options['errors'][$relation['model']]) ? self::$_options['errors'][$relation['model']]: self::$_options['help'][$relation['model']]).'</p>' 
			: '';
			$string .= '</div>';
			$string .= '</div>';
		
		}
		
		return $string;
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
		$string = '';
		
		if ( (bool) ORM::factory($relation['model'])->count_all())
		{
			$model = ORM::factory($relation['model']);
		
			$plural = $model->object_plural();
			
			$name = $relation['foreign_key'];
			
			$string .= '<div class="control-group">';
	        $string .= '<label class="control-label">'.ucwords($plural).'</label>';
	        $string .= '<div class="controls">';
	        
	        foreach(ORM::factory($relation['model'])->find_all() as $option)
			{
		        $string .= '<label class="radio">';
		        $string .= '<input type="radio" name="'.$name.'" id="'.$relation['model'].$option->pk().'" value="'.$option->pk().'" '.(self::$_object->has($plural, $option->pk()) ? 'checked' : false).'>';
				$string .= ucwords($option->name);
		        $string .= '</label>';
			}
	        $string .= '</div>';
	        $string .= '</div>';
		}
		
		return $string;
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
		$string = '';
		
		if ( (bool) ORM::factory($relation['model'])->count_all())
		{
			$model = ORM::factory($relation['model']);
			
			$plural = $model->object_plural();
			
			$name = (isset($relation['foreign_key'])) ? $plural.'[]' : $relation['foreign_key'];
			
			$string .= '<div class="control-group">';
			$string .= '<label class="control-label" for="'.$plural.'">'.ucwords($plural).'</label>';
			$string .= '<div class="controls">';
			
			foreach(ORM::factory($relation['model'])->find_all() as $option)
			{
				$string .= '<label class="checkbox inline">';
				$string .= '<input type="checkbox" name="'.$name.'" id="'.$relation['model'].$option->pk().'" value="'.$option->pk().'" '.(self::$_object->has($plural, $option->pk()) ? 'checked="checked"' : false).'> ';
				$string .= ucwords($option->name);
				$string .= '</label>';
			}
			
			$string .= '</div>';
			$string .= '</div>';
		}
		
		return $string;
	}
	
	protected static function label($column)
	{
		$string = Form::label($column['column_name'], isset(self::$_options['labels'][$column['column_name']]) ? self::$_options['labels'][$column['column_name']] : ucwords($column['column_name']), array());
		
		return $string;
	}
	
	protected static function reset()
	{
		$string = Form::input('reset', 'Cancel', array('type' => 'reset', 'class' => 'btn')).' ';
		
		return $string;
	}
	
	protected static function submit()
	{
		$string = Form::submit('save','Save', array('class' => 'btn btn-primary')).' ';
		
		return $string;
	}
}