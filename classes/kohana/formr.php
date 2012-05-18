<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Kohana_Formr class.
 *
 * Formr::create($model)->render($flavour);
 * Formr::edit($model)->render($flavour);
 *
 */
class Kohana_Formr
{
	protected static $_instance;
	
	protected static $_config;
	
	protected static $_string;
	
	protected static $_object;
	
	protected static $_column_names = array();
	
	protected static $_options = array(
		'enctype' => 'application/x-www-form-urlencoded',
		'exclude' => array(),
		'classes' => array(),
		'labels' => array(),
		'placeholders' => array(),
		'types' => array(),
		'errors' => array(),
		'help' => array(),
		'disabled' => array(),
		'group_by' => array(),
	);
	
	private function __construct($config)
	{	
		self::$_config = $config;
	}
	
	public static function setup($options)
	{
		self::$_options['classes'] = array_flip(self::column_names());
		
		foreach(self::$_options['classes'] as &$class)
		{
			$class = '';
		}
		unset($class);
		
		//array_merge_recursive(self::$_options, $options);
		
		if (isset($options['enctype']))
		{
			self::$_options['enctype'] = $options['enctype'];
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
		
		self::$_string = $formr::open(null, array('enctype' => self::$_options['enctype']));
		
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
					
					self::$_string .= $formr::$type($column);
				}
				else
				{
					switch ($column['data_type'])
					{
						case 'hidden':
						case $column['column_name'] === self::$_object->primary_key(): 
							array_push($hidden, $formr::hidden($column));
							break;
						case 'int':
						case 'int unsigned':
						case 'int signed':
						case 'tinyint':
						case 'tinyint unsigned':
						case 'tinyint signed':
						case 'float':
						case 'double':
							self::$_string .= $formr::number($column);
							break;
						case 'varchar':
							self::$_string .= $formr::varchar($column);
							break;
						case 'date':
							self::$_string .= $formr::date($column);
							break;
						case 'text':
							self::$_string .= $formr::text($column);
							break;
						case 'enum':
							self::$_string .= $formr::enum($column);
							break;
						default:
							self::$_string .= $formr::varchar($column);
					}
				}
			}
		}
		unset($column);
		
		foreach(self::$_object->belongs_to() as $name => $model)
		{
			$model['relation_name'] = $name;
			
			if ( ! in_array($model['relation_name'], self::$_options['exclude']))
			{
				if (isset(self::$_options['types'][$model['foreign_key']]))
				{
					$type = self::$_options['types'][$model['foreign_key']];
					
					self::$_string .= $formr::$type($model);
				}
				else
				{
					self::$_string .= $formr::select($model, false);
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
										
					self::$_string .= $formr::$type($model);
				}
				else
				{
					self::$_string .= $formr::select($model, false);
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
					
					self::$_string .= $formr::$type($model, true);
				}
				else
				{
					self::$_string .= $formr::select($model, true);
				}
			}
		}
		unset($model);
		
		self::$_string .= implode("\n", $hidden);
		
		self::$_string .= $formr::actions();
		
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