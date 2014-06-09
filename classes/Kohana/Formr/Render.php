<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Kohana_Formr_Render class.
 * 
 * @extends Kohana_Formr
 */
class Kohana_Formr_Render extends Kohana_Formr
{
	/**
	 * _config
	 * 
	 * (default value: array())
	 * 
	 * @var array
	 * @access protected
	 */
	protected $_config = array();
	
	/**
	 * __construct function.
	 * 
	 * @access private
	 * @param mixed $model
	 * @param mixed $id (default: null)
	 * @param mixed $options
	 * @return void
	 */
	private function __construct($model, $id = null, $options)
	{	
		$this->_config = Kohana::$config->load('formr');
	}
	
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
		$output = Form::close();

		return $output;
	}

	/**
	 * actions function.
	 *
	 * @access protected
	 * @static
	 * @return void
	 */
	public static function actions($options)
	{
		return View::factory(Kohana::$config->load('formr.render').'/actions')->bind('options',$options)->render();
	}

	/**
	 * hidden function.
	 * 
	 * @access protected
	 * @static
	 * @param mixed $column
	 * @param mixed $object
	 * @param mixed $options
	 * @return void
	 */
	protected static function hidden($column, $object, $options)
	{
		return View::factory(Kohana::$config->load('formr.render').'/hidden')->bind('column',$column)->bind('object',$object)->bind('options',$options)->render();
	}

	/**
	 * number function.
	 * 
	 * @access protected
	 * @static
	 * @param mixed $column
	 * @param mixed $object
	 * @param mixed $options
	 * @return void
	 */
	protected static function number($column, $object, $options)
	{
		return View::factory(Kohana::$config->load('formr.render').'/number')->bind('column',$column)->bind('object',$object)->bind('options',$options)->render();
	}
	
	/**
	 * int function.
	 * 
	 * @access protected
	 * @static
	 * @param mixed $column
	 * @param mixed $object
	 * @param mixed $options
	 * @return void
	 */
	protected static function int($column, $object, $options)
	{
		$disabled = in_array($column['column_name'], $options['disabled']) ? array('disabled' => true) : array();
		
		return View::factory(Kohana::$config->load('formr.render').'/int')->bind('disabled',$disabled)->bind('column',$column)->bind('object',$object)->bind('options',$options)->render();
	}

	/**
	 * date function.
	 * 
	 * @access protected
	 * @static
	 * @param mixed $column
	 * @param mixed $object
	 * @param mixed $options
	 * @return void
	 */
	protected static function date($column, $object, $options)
	{
		$disabled = in_array($column['column_name'], $options['disabled']) ? array('disabled' => true) : array();

		if ($object->{$column['column_name']} and is_numeric($object->{$column['column_name']}))
		{
			$value = date('Y-m-d',$object->{$column['column_name']});
		}
		elseif (isset($options['values'][$column['column_name']]))
		{
			$value = date('Y-m-d',$options['values'][$column['column_name']]);
		}
		elseif(isset($column['default']))
		{
			$value = $column['default'];
		}
		else
		{
			$value = '';
		}

		return View::factory(Kohana::$config->load('formr.render').'/date')->bind('value',$value)->bind('column',$column)->bind('object',$object)->bind('options',$options)->render();
	}
	
	
	/**
	 * datetime function.
	 * 
	 * @access protected
	 * @static
	 * @param mixed $column
	 * @param mixed $object
	 * @param mixed $options
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
			$value = date('Y-m-d',$object->{$column['column_name']});
		}
		elseif (isset($options['values'][$column['column_name']]))
		{
			$value = date('Y-m-d',$options['values'][$column['column_name']]);
		}
		elseif(isset($column['default']))
		{
			$value = $column['default'];
		}
		else
		{
			$value = '';
		}
		
		return View::factory(Kohana::$config->load('formr.render').'/datetime')->bind('disabled',$disabled)->bind('hours',$hours)->bind('minutes',$minutes)->bind('value',$value)->bind('column',$column)->bind('object',$object)->bind('options',$options)->render();
	}
	
	/**
	 * time function.
	 * 
	 * @access protected
	 * @static
	 * @param mixed $column
	 * @param mixed $object
	 * @param mixed $options
	 * @return void
	 */
	protected static function time($column, $object, $options)
	{
		$disabled = in_array($column['column_name'], $options['disabled']) ? array('disabled' => true) : array();

		$value = ($object->{$column['column_name']}
		? (is_numeric($object->{$column['column_name']}) ? date('m/d/Y',$object->{$column['column_name']}) : $object->{$column['column_name']})
		: (isset($column['default']) ? $column['default'] : ''));

		return View::factory(Kohana::$config->load('formr.render').'/time')->bind('disabled',$disabled)->bind('value',$value)->bind('column',$column)->bind('object',$object)->bind('options',$options)->render();
	}

	/**
	 * email function.
	 * 
	 * @access protected
	 * @static
	 * @param mixed $column
	 * @param mixed $object
	 * @param mixed $options
	 * @return void
	 */
	protected static function email($column, $object, $options)
	{
		$disabled = in_array($column['column_name'], $options['disabled']) ? array('disabled' => true) : array();

		$value = ($object->{$column['column_name']}
		? $object->{$column['column_name']}
		: (isset($column['default']) ? $column['default'] : ''));

		return View::factory(Kohana::$config->load('formr.render').'/email')->bind('disabled',$disabled)->bind('value',$value)->bind('column',$column)->bind('object',$object)->bind('options',$options)->render();
	}
	
	/**
	 * color function.
	 * 
	 * @access protected
	 * @static
	 * @param mixed $column
	 * @param mixed $object
	 * @param mixed $options
	 * @return void
	 */
	protected static function color($column, $object, $options)
	{
		$disabled = in_array($column['column_name'], $options['disabled']) ? array('disabled' => true) : array();

		$value = ($object->{$column['column_name']}
		? $object->{$column['column_name']}
		: (isset($column['default']) ? $column['default'] : ''));

		return View::factory(Kohana::$config->load('formr.render').'/color')->bind('disabled',$disabled)->bind('value',$value)->bind('column',$column)->bind('object',$object)->bind('options',$options)->render();
	}

	/**
	 * varchar function.
	 * 
	 * @access protected
	 * @static
	 * @param mixed $column
	 * @param mixed $object
	 * @param mixed $options
	 * @return void
	 */
	protected static function varchar($column, $object, $options)
	{
		$disabled = in_array($column['column_name'], $options['disabled']) ? array('disabled' => true) : array();
		
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
		
		return View::factory(Kohana::$config->load('formr.render').'/varchar')
			->bind('disabled',$disabled)
			->bind('value',$value)
			->bind('column',$column)
			->bind('object',$object)
			->bind('options',$options)
			->render();
	}

	/**
	 * password function.
	 * 
	 * @access protected
	 * @static
	 * @param mixed $column
	 * @param mixed $object
	 * @param mixed $options
	 * @return void
	 */
	protected static function password($column, $object, $options)
	{
		$disabled = in_array($column['column_name'], $options['disabled']) ? array('disabled' => true) : array();

		return View::factory(Kohana::$config->load('formr.render').'/password')->bind('disabled',$disabled)->bind('column',$column)->bind('object',$object)->bind('options',$options)->render();
	}

	/**
	 * text function.
	 * 
	 * @access protected
	 * @static
	 * @param mixed $column
	 * @param mixed $object
	 * @param mixed $options
	 * @return void
	 */
	protected static function text($column, $object, $options)
	{
		$disabled = in_array($column['column_name'], $options['disabled']) ? array('disabled' => true) : array();

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
		
		return View::factory(Kohana::$config->load('formr.render').'/text')->bind('disabled',$disabled)->bind('value',$value)->bind('column',$column)->bind('object',$object)->bind('options',$options)->render();
	}

	/**
	 * checkbox function.
	 * 
	 * @access protected
	 * @static
	 * @param mixed $column
	 * @param mixed $object
	 * @param mixed $options
	 * @return void
	 */
	protected static function checkbox($column, $object, $options)
	{
		$disabled = in_array($column['column_name'], $options['disabled']) ? array('disabled' => true) : array();

		return View::factory(Kohana::$config->load('formr.render').'/checkbox')->bind('disabled',$disabled)->bind('column',$column)->bind('object',$object)->bind('options',$options)->render();
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

		return View::factory(Kohana::$config->load('formr.render').'/file')->bind('disabled',$disabled)->bind('column',$column)->bind('object',$object)->bind('options',$options)->render();
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
		
		return View::factory(Kohana::$config->load('formr.render').'/enum')->bind('selected',$selected)->bind('options',$options)->bind('attributes',$attributes)->bind('disabled',$disabled)->bind('column',$column)->bind('object',$object)->bind('options',$options)->render();
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

		return View::factory(Kohana::$config->load('formr.render').'/select')
			->bind('relation',$relation)
			->bind('options',$options)
			->bind('name',$name)
			->bind('opts',$opts)
			->bind('selected',$selected)
			->bind('attributes',$attributes)
			->bind('disabled',$disabled)
			->bind('column',$column)
			->bind('object',$object)
			->render();
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

			return View::factory(Kohana::$config->load('formr.render').'/radial/group')
			->bind('relation',$relation)
			->bind('options',$options)
			->bind('items',$items)
			->bind('opts',$opts)
			->bind('disabled',$disabled)
			->bind('column',$column)
			->bind('object',$object)
			->render();
		}
		else
		{
			return null;
		}
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
			
			return View::factory(Kohana::$config->load('formr.render').'/checkbox/group')
			->bind('relation',$relation)
			->bind('options',$options)
			->bind('items',$items)
			->bind('opts',$opts)
			->bind('disabled',$disabled)
			->bind('name',$name)
			->bind('plural',$plural)
			->bind('column',$column)
			->bind('object',$object)
			->render();
		}
		else
		{
			return null;
		}
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
	public static function label($column, $options)
	{
		return View::factory(Kohana::$config->load('formr.render').'/label')
			->bind('column',$column)
			->bind('options',$options)
			->render();
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
	public static function reset($name = 'reset', $value = 'Reset', $attributes = array('class' => ''))
	{
		$attributes = array_merge(array('type' => 'reset'),$attributes);
		
		return View::factory(Kohana::$config->load('formr.render').'/reset')
			->bind('name',$name)
			->bind('value',$value)
			->bind('attributes',$attributes)
			->render();
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
	public static function submit($name = 'save', $value = 'Save', $attributes = array('class' => ''))
	{
		return View::factory(Kohana::$config->load('formr.render').'/submit')
			->bind('name',$name)
			->bind('value',$value)
			->bind('attributes',$attributes)
			->render();
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
	protected static function button($name = 'save', $value = 'Save', $attributes = array('class' => ''))
	{
		return View::factory(Kohana::$config->load('formr.render').'/button')
			->bind('name',$name)
			->bind('value',$value)
			->bind('attributes',$attributes)
			->render();
	}
}
