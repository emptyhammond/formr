<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Kohana_Formr class.
 *
 * Formr::create($model,$options)->render($flavour);
 * Formr::edit($model, $id, $options)->render($flavour);
 *
 */
class Kohana_Formr
{
	protected static $_instance;
	
	protected static $_config;
	
	protected static $_string;
	
	protected static $_output = array();
	
	protected static $_hidden = array();
	
	protected static $_object;
	
	protected static $_column_names = array();
	
	protected static $_options = array(
		'actions' => false,
		'enctype' => 'application/x-www-form-urlencoded', //application/x-www-form-urlencoded|multipart/form-data
		'exclude' => array(),
		'classes' => array(),
		'labels' => array(),
		'placeholders' => array(),
		'types' => array(),
		'errors' => array(),
		'help' => array(),
		'disabled' => array(),
		'group_by' => array(),
		'fieldsets' => false,
		'additional' => array(),
		'legend' => '',
		'sources' => array(),
		'order' => false,
		'values' => array(),
		'filters' => array(),
		'display' => array(),
		'attributes' => array(),
	);
	
	private function __construct($config)
	{	
		self::$_config = $config;
	}
	
	public static function setup($options)
	{
		self::$_options['classes'] = array_merge(array_flip(self::column_names()), array_flip(isset($options['additional']) ? $options['additional'] : array() )) ;

		foreach(self::$_options['classes'] as &$class)
		{
			$class = '';
		}
		unset($class);
		
		if (isset($options['actions']))
		{
			self::$_options['actions'] = $options['actions'];
		}
		
		if (isset($options['enctype']))
		{
			self::$_options['enctype'] = isset($options['enctype']) ? $options['enctype'] : self::$_options['enctype'];
		}
		
		if (isset($options['exclude']))
		{
			self::$_options['exclude'] = array_merge(self::$_options['exclude'], $options['exclude']);
		}
		
		if (isset($options['classes']))
		{
			self::$_options['classes'] = array_merge(self::$_options['classes'], $options['classes']);
		}
		
		if (isset($options['labels']))
		{
			self::$_options['labels'] = array_merge(self::$_options['labels'], $options['labels']);
		}
		
		if (isset($options['placeholders']))
		{
			self::$_options['placeholders'] = array_merge(self::$_options['placeholders'], $options['placeholders']);
		}
		
		if (isset($options['types']))
		{
			self::$_options['types'] = array_merge(self::$_options['types'], $options['types']);
		}
		
		if (isset($options['errors']))
		{
			self::$_options['errors'] = array_merge(self::$_options['errors'], $options['errors']);
		}
		
		if (isset($options['help']))
		{
			self::$_options['help'] = array_merge(self::$_options['help'], $options['help']);
		}
		
		if (isset($options['disabled']))
		{
			self::$_options['disabled'] = array_merge(self::$_options['disabled'], $options['disabled']);
		}
		
		if (isset($options['group_by']))
		{
			self::$_options['group_by'] = array_merge(self::$_options['group_by'], $options['group_by']);
		}
		
		if (isset($options['additional']))
		{
			self::$_options['additional'] = array_merge(self::$_options['additional'], $options['additional']);
		}
		
		self::$_options['legend'] = isset($options['legend']) ? $options['legend'] : ucwords(self::$_object->object_name());
		
		if (isset($options['sources']))
		{
			self::$_options['sources'] = array_merge(self::$_options['sources'], $options['sources']);
		}
		
		if (isset($options['order']))
		{
			self::$_options['order'] = $options['order'];
		}
		
		self::$_options['fieldsets'] = isset($options['fieldsets']) ? $options['fieldsets'] : false;
		
		if (isset($options['values']))
		{
			self::$_options['values'] = array_merge(self::$_options['values'], $options['values']);
		}
		
		if (isset($options['filters']))
		{
			self::$_options['filters'] = array_merge(self::$_options['filters'], $options['filters']);
		}
		
		if (isset($options['display']))
		{
			self::$_options['display'] = array_merge(self::$_options['display'], $options['display']);
		}
		
		if (isset($options['attributes']))
		{
			self::$_options['attributes'] = array_merge(self::$_options['attributes'], $options['attributes']);
		}
		
		if ($_POST)
		{
			self::$_object->values($_POST);
		}
	}
	
	public static function create($model, array $options = array())
	{
		if ( ! isset(Formr::$_instance))
		{				
			$config = Kohana::$config->load('formr');
			
			Formr::$_instance = new Kohana_Formr($config);
			
			self::$_object = ORM::factory($model);
			
			self::setup($options);
		}
		
		return self::$_instance;
	}
	
	public static function edit($model, $id, array $options = array())
	{
		if ( ! isset(Formr::$_instance))
		{				
			$config = Kohana::$config->load('formr');
			
			Formr::$_instance = new Kohana_Formr($config);
			
			self::$_object = ORM::factory($model, $id);
			
			self::setup($options);
		}
		
		return self::$_instance;
	}
	
	public function render($flavour = 'default')
	{
		$formr = 'Formr_'.ucfirst($flavour);
		
		$hidden = array();
		
		$belongs_to = array();
		
		$has_one = array();
		
		foreach(self::$_object->belongs_to() as $model)
		{
			array_push($belongs_to, $model['foreign_key']);
		}
		
		foreach(self::$_object->has_one() as $model)
		{
			array_push($has_one, $model['foreign_key']);
		}
		
		foreach(self::$_object->list_columns() as $column)
		{
			if ( ! in_array($column['column_name'], array_merge(self::$_options['exclude'], $has_one, $belongs_to)))
			{
				if (isset(self::$_options['types'][$column['column_name']]))
				{
					$type = self::$_options['types'][$column['column_name']];
					
					self::$_output[$column['column_name']] = $formr::$type($column);
				}
				else
				{
					switch ($column['data_type'])
					{
						case 'hidden':
						case $column['column_name'] === self::$_object->primary_key():
							self::$_hidden[$column['column_name']] = $formr::hidden($column);
							break;
						case 'int':
						case 'int unsigned':
						case 'int signed':
						case 'tinyint':
						case 'tinyint unsigned':
						case 'tinyint signed':
							self::$_output[$column['column_name']] = $formr::int($column);
							break;
						case 'float':
						case 'double':
							self::$_output[$column['column_name']] = $formr::number($column);
							break;
						case 'varchar':
							self::$_output[$column['column_name']] = $formr::varchar($column);
							break;
						case 'date':
							self::$_output[$column['column_name']] = $formr::date($column);
							break;
						case 'text':
							self::$_output[$column['column_name']] = $formr::text($column);
							break;
						case 'enum':
							self::$_output[$column['column_name']] = $formr::enum($column);
							break;
						default:
							self::$_output[$column['column_name']] = $formr::varchar($column);
					}
				}
			}
		}
		unset($column);
		
		foreach(self::$_options['additional'] as $additional)
		{
			$column = array('column_name' => $additional, 'relation_name' => $additional);
			
			$func = self::$_options['types'][$additional];
			
			if (self::$_options['types'][$additional] === 'hidden')
			{
				self::$_hidden[$additional] = $formr::$func($column);	
			}
			else
			{
				self::$_output[$additional] = $formr::$func($column);
			}
		}
		
		foreach(self::$_object->belongs_to() as $name => $model)
		{
			$model['relation_name'] = $name;
			
			if ( ! in_array($model['relation_name'], self::$_options['exclude']))
			{
				if (isset(self::$_options['types'][$model['foreign_key']]))
				{
					$type = self::$_options['types'][$model['foreign_key']];
					
					self::$_output[$model['relation_name']] = $formr::$type($model);
				}
				else
				{
					self::$_output[$model['relation_name']] = $formr::select($model, false);
				}
			}
		}
		unset($model);
		
		foreach(self::$_object->has_one() as $name => $model)
		{
			$model['relation_name'] = $name;
			
			if ( ! in_array($model['relation_name'], self::$_options['exclude']))
			{
				if (isset(self::$_options['types'][$model['foreign_key']]))
				{
					$type = self::$_options['types'][$model['foreign_key']];
										
					self::$_output[$model['relation_name']] = $formr::$type($model);
				}
				else
				{
					self::$_output[$model['relation_name']] = $formr::select($model, false);
				}
			}
		}
		unset($model);
		
		foreach(self::$_object->has_many() as $name => $model)
		{
			$model['relation_name'] = $name;
			
			if ( ! in_array($model['relation_name'], self::$_options['exclude']))
			{
				if (isset(self::$_options['types'][Inflector::plural($model['model'])]))
				{
					$type = self::$_options['types'][Inflector::plural($model['model'])];
					
					self::$_output[$model['relation_name']] = $formr::$type($model, true);
				}
				else
				{
					self::$_output[$model['relation_name']] = $formr::select($model, true);
				}
			}
		}
		unset($model);
		
		if (self::$_options['order'])
		{
			$order = array_flip(self::$_options['order']);
			
			foreach($order as $key => $val)
			{
				$order[$key] = isset(self::$_output[$key]) ? self::$_output[$key] : null;
				unset(self::$_output[$key]);
			}
			unset($key, $val);
			
			foreach(self::$_output as $key => $val)
			{
				$order[$key] = self::$_output[$key];
			}
			unset($key, $val);
			
			self::$_output = $order;
		}
		
		self::$_string = $formr::open(null, array('enctype' => self::$_options['enctype']));
		
		self::$_string .= implode("\n", self::$_hidden);
		
		if (is_array(self::$_options['fieldsets']))
		{
			foreach(self::$_options['fieldsets'] as $fieldset => $inputs)
			{
				self::$_string .= '<fieldset>';
				self::$_string .= '<legend>'.$fieldset.'</legend>';
				foreach($inputs as $input)
				{
					self::$_string .= self::$_output[$input];	
				}
				
				self::$_string .= '</fieldset>';
			}
		}
		else
		{
			self::$_string .= '<fieldset>';
			self::$_string .= '<legend>'.self::$_options['legend'].'</legend>';
			self::$_string .= implode("\n", self::$_output);
		}
				
		self::$_string .= $formr::actions();
		
		if (!(sizeof(self::$_options['fieldsets'] > 0)))
		{
			self::$_string .= '</fieldset>';
		}
		
		self::$_string .= $formr::close();
		
		echo self::$_string;
	}
	
	public static function column_names()
	{
		if ( ! sizeof(self::$_column_names) > 0)
		{		
			foreach(self::$_object->list_columns() as $column)
			{
				array_push(self::$_column_names, $column['column_name']);
			}
		}
		
		return self::$_column_names;
	}
}