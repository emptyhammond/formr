<?php defined('SYSPATH') or die('No direct script access.');
error_reporting(E_ALL);
/**
 * Kohana_Formr class.
 *
 * Formr::create($model,$options)->render($flavour);
 * Formr::edit($model, $id, $options)->render($flavour);
 *
 */
class Kohana_Formr
{
	protected $_instance;
	
	protected $_config;
	
	protected $_string;
	
	protected $_output = array();
	
	protected $_hidden = array();
	
	protected $_object;
	
	protected $_column_names = array();
	
	protected $_options = array(
		'action' => null,
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
		'tabs' => false,
		'html' => array(),
	);
	
	private function __construct($model, $id = null, $options)
	{	
		$this->_config = Kohana::$config->load('formr');
		
		$this->_object = ORM::factory($model, $id);
			
		$this->_options['classes'] = array_merge(array_flip($this->column_names()), array_flip(isset($options['additional']) ? $options['additional'] : array() )) ;

		foreach($this->_options['classes'] as &$class)
		{
			$class = '';
		}
		unset($class);
		
		if (isset($options['action']))
		{
			$this->_options['action'] = $options['action'];
		}
		
		if (isset($options['actions']))
		{
			$this->_options['actions'] = $options['actions'];
		}
		
		if (isset($options['enctype']))
		{
			$this->_options['enctype'] = isset($options['enctype']) ? $options['enctype'] : $this->_options['enctype'];
		}
		
		if (isset($options['exclude']))
		{
			$this->_options['exclude'] = array_merge($this->_options['exclude'], $options['exclude']);
		}
		
		if (isset($options['classes']))
		{
			$this->_options['classes'] = array_merge($this->_options['classes'], $options['classes']);
		}
		
		if (isset($options['labels']))
		{
			$this->_options['labels'] = array_merge($this->_options['labels'], $options['labels']);
		}
		
		if (isset($options['placeholders']))
		{
			$this->_options['placeholders'] = array_merge($this->_options['placeholders'], $options['placeholders']);
		}
		
		if (isset($options['types']))
		{
			$this->_options['types'] = array_merge($this->_options['types'], $options['types']);
		}
		
		if (isset($options['errors']))
		{
			$this->_options['errors'] = array_merge($this->_options['errors'], $options['errors']);
		}
		
		if (isset($options['help']))
		{
			$this->_options['help'] = array_merge($this->_options['help'], $options['help']);
		}
		
		if (isset($options['disabled']))
		{
			$this->_options['disabled'] = array_merge($this->_options['disabled'], $options['disabled']);
		}
		
		if (isset($options['group_by']))
		{
			$this->_options['group_by'] = array_merge($this->_options['group_by'], $options['group_by']);
		}
		
		if (isset($options['additional']))
		{
			$this->_options['additional'] = array_merge($this->_options['additional'], $options['additional']);
		}
		
		$this->_options['legend'] = isset($options['legend']) ? $options['legend'] : ucwords($this->_object->object_name());
		
		if (isset($options['sources']))
		{
			$this->_options['sources'] = array_merge($this->_options['sources'], $options['sources']);
		}
		
		if (isset($options['order']))
		{
			$this->_options['order'] = $options['order'];
		}
		
		$this->_options['fieldsets'] = isset($options['fieldsets']) ? $options['fieldsets'] : false;
		
		if (isset($options['values']))
		{
			$this->_options['values'] = array_merge($this->_options['values'], $options['values']);
		}
		
		if (isset($options['filters']))
		{
			$this->_options['filters'] = array_merge($this->_options['filters'], $options['filters']);
		}
		
		if (isset($options['display']))
		{
			$this->_options['display'] = array_merge($this->_options['display'], $options['display']);
		}
		
		if (isset($options['attributes']))
		{
			$this->_options['attributes'] = array_merge($this->_options['attributes'], $options['attributes']);
		}
		
		if (isset($options['tabs']))
		{
			$this->_options['tabs'] = $options['tabs'];
		}
		
		if (isset($options['html']))
		{
			$this->_options['html'] = array_merge($this->_options['html'], $options['html']);
		}
		
		if ($_POST)
		{
			$this->_object->values($_POST);
		}
	}
	
	public static function create($model, array $options = array())
	{
		return new Kohana_Formr($model, null, $options);
	}
	
	public static function edit($model, $id, array $options = array())
	{	
		return new Kohana_Formr($model, $id, $options);
	}
	
	public function render($flavour = 'default')
	{
		$formr = 'Formr_'.ucfirst($flavour);
		
		$hidden = array();
		
		$belongs_to = array();
		
		$has_one = array();
		
		foreach($this->_object->belongs_to() as $model)
		{
			array_push($belongs_to, $model['foreign_key']);
		}
		
		foreach($this->_object->has_one() as $model)
		{
			array_push($has_one, $model['foreign_key']);
		}
		
		foreach($this->_object->list_columns() as $column)
		{
			if ( ! in_array($column['column_name'], array_merge($this->_options['exclude'], $has_one, $belongs_to)))
			{
				if (isset($this->_options['types'][$column['column_name']]))
				{
					$type = $this->_options['types'][$column['column_name']];
					
					$this->_output[$column['column_name']] = $formr::$type($column, $this->_object, $this->_options);
				}
				else
				{
					switch ($column['data_type'])
					{
						case 'hidden':
						case $column['column_name'] === $this->_object->primary_key():
							$this->_hidden[$column['column_name']] = $formr::hidden($column, $this->_object, $this->_options);
							break;
						case 'int':
						case 'int unsigned':
						case 'int signed':
						case 'tinyint':
						case 'tinyint unsigned':
						case 'tinyint signed':
							$this->_output[$column['column_name']] = $formr::int($column, $this->_object, $this->_options);
							break;
						case 'float':
						case 'double':
							$this->_output[$column['column_name']] = $formr::number($column, $this->_object, $this->_options);
							break;
						case 'varchar':
							$this->_output[$column['column_name']] = $formr::varchar($column, $this->_object, $this->_options);
							break;
						case 'date':
							$this->_output[$column['column_name']] = $formr::date($column, $this->_object, $this->_options);
							break;
						case 'text':
							$this->_output[$column['column_name']] = $formr::text($column, $this->_object, $this->_options);
							break;
						case 'enum':
							$this->_output[$column['column_name']] = $formr::enum($column, $this->_object, $this->_options);
							break;
						default:
							$this->_output[$column['column_name']] = $formr::varchar($column, $this->_object, $this->_options);
					}
				}
			}
		}
		unset($column);
		
		foreach($this->_options['additional'] as $additional)
		{
			$column = array('column_name' => $additional, 'relation_name' => $additional);
			
			$func = $this->_options['types'][$additional];
			
			if ($this->_options['types'][$additional] === 'hidden')
			{
				$this->_hidden[$additional] = $formr::$func($column, $this->_object, $this->_options);	
			}
			else
			{
				if ($func === 'html')
				{
					$this->_output[$additional] = $this->_options['html'][$additional];
				}
				elseif ($func === 'select')
				{
					$this->_output[$additional] = $formr::$func($column, false, $this->_object, $this->_options);					
				}
				else
				{
					$this->_output[$additional] = $formr::$func($column, $this->_object, $this->_options);					
				}
			}
		}
		
		foreach($this->_object->belongs_to() as $name => $model)
		{
			$model['relation_name'] = $name;
			$model['column_name'] = $name;

			if ( ! in_array($model['relation_name'], $this->_options['exclude']))
			{
				if (isset($this->_options['types'][$model['foreign_key']]) or isset($this->_options['types'][$name]))
				{
					$type = isset($this->_options['types'][$model['foreign_key']]) ? $this->_options['types'][$model['foreign_key']] : $this->_options['types'][$name];
					
					$this->_output[$model['relation_name']] = $formr::$type($model, $this->_object, $this->_options);
				}
				else
				{
					$this->_output[$model['relation_name']] = $formr::select($model, false, $this->_object, $this->_options);
				}
			}
		}
		unset($model);
		
		foreach($this->_object->has_one() as $name => $model)
		{
			$model['relation_name'] = $name;
			$model['column_name'] = $name;
						
			if ( ! in_array($model['relation_name'], $this->_options['exclude']))
			{
				if (isset($this->_options['types'][$model['foreign_key']]))
				{
					$type = $this->_options['types'][$model['foreign_key']];
										
					$this->_output[$model['relation_name']] = $formr::$type($model, $this->_object, $this->_options);
				}
				else
				{
					$this->_output[$model['relation_name']] = $formr::select($model, false, $this->_object, $this->_options);
				}
			}
		}
		unset($model);
		
		foreach($this->_object->has_many() as $name => $model)
		{
			$model['relation_name'] = $name;
			$model['column_name'] = $name;			

			if ( ! in_array($model['relation_name'], $this->_options['exclude']))
			{
				if (isset($this->_options['types'][Inflector::plural($model['model'])]))
				{
					if ($this->_options['types'][Inflector::plural($model['model'])] === 'hidden')
					{
						foreach($this->_object->{$name}->find_all() as $i => $relation)
						{
							$this->_options['values'][$model['relation_name'].'['.$i.']'] = $relation->id;
							
							$this->_output[$model['relation_name'].'['.$i.']'] = $formr::hidden(array('column_name' => $model['relation_name'].'['.$i.']'), $relation, $this->_options);
						}
					}
					else
					{
						$type = $this->_options['types'][Inflector::plural($model['model'])];
						
						$this->_output[$model['relation_name']] = $formr::$type($model, $this->_object, $this->_options);
					}
				}
				else
				{
					$this->_output[$model['relation_name']] = $formr::select($model, true, $this->_object, $this->_options);
				}
			}
		}
		unset($model);
		
		if ($this->_options['order'])
		{
			$order = array_flip($this->_options['order']);
			
			foreach($order as $key => $val)
			{
				$order[$key] = isset($this->_output[$key]) ? $this->_output[$key] : null;
				unset($this->_output[$key]);
			}
			unset($key, $val);
			
			foreach($this->_output as $key => $val)
			{
				$order[$key] = $this->_output[$key];
			}
			unset($key, $val);
			
			$this->_output = $order;
		}
		
		$this->_string = $formr::open(null, array('enctype' => $this->_options['enctype']));
		
		$this->_string .= implode("\n", $this->_hidden);
		
		if ($this->_options['tabs'] and is_array($this->_options['fieldsets']))
		{
			$list = '';
			$content = '';
			$active = true;
			
			foreach($this->_options['fieldsets'] as $fieldset => $inputs)
			{
				if (is_array(current($inputs)))
				{
					$list .= '<li class="dropdown">';
					$list .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$fieldset.' <b class="caret"></b></a>';
					$list .= '<ul class="dropdown-menu">';
					
					foreach($inputs as $tab => $fields)
					{
						$list .= '<li class="'.($active ? 'active' : '').'"><a data-toggle="tab" href="#'.strtolower(preg_replace('/\s*/', '',$tab)).'">'.$tab.'</a></li>';
						
						$content .= '<div id="'.strtolower(preg_replace('/\s*/', '',$tab)).'" class="tab-pane '.($active ? 'active' : '').'">';
						$content .= '<fieldset>';
						$content .= '<legend>'.$tab.'</legend>';
		
						foreach($fields as $field)
						{
							if (isset($this->_output[$field]))
							{
								$content .= $this->_output[$field];	
							}
						}	
						
						$content .= '</fieldset>';
						$content .= '</div>';
						
						$active = false;
					}
					
					$list .= '</ul>';
					$list .= '</li>';	
				}
				else
				{
					$list .= '<li class="'.($active ? 'active' : '').'"><a data-toggle="tab" href="#'.strtolower(preg_replace('/\s*/', '',$fieldset)).'">'.$fieldset.'</a></li>';
					$content .= '<div id="'.strtolower(preg_replace('/\s*/', '',$fieldset)).'" class="tab-pane '.($active ? 'active' : '').'">';
					$content .= '<fieldset>';
					$content .= '<legend>'.$fieldset.'</legend>';
					foreach($inputs as $input)
					{
						if (isset($this->_output[$input]))
						{
							$content .= $this->_output[$input];	
						}
					}
					
					$content .= '</fieldset>';
					$content .= '</div>';
					
					$active = false;	
				}
			}
			
			$this->_string .= '<div class="tabbable">';
			$this->_string .= '<ul class="nav nav-tabs">';
			$this->_string .= $list;
			$this->_string .= '</ul>';
			$this->_string .= '<div class="tab-content">';
			$this->_string .= $content;
			$this->_string .= $formr::actions($this->_options);						
			$this->_string .= '</div>';			
			$this->_string .= '</div>';
		}
		elseif (is_array($this->_options['fieldsets']))
		{
			foreach($this->_options['fieldsets'] as $fieldset => $inputs)
			{
				$this->_string .= '<fieldset>';
				$this->_string .= '<legend>'.$fieldset.'</legend>';

				foreach($inputs as $input)
				{
					if (isset($this->_output[$input]))
					{
						$this->_string .= $this->_output[$input];	
					}
				}
				
				$this->_string .= '</fieldset>';
			}
			
			$this->_string .= $formr::actions($this->_options);
		}
		else
		{
			$this->_string .= '<fieldset>';
			$this->_string .= '<legend>'.$this->_options['legend'].'</legend>';
			$this->_string .= implode("\n", $this->_output);
			$this->_string .= '</fieldset>';
			$this->_string .= $formr::actions($this->_options);
		}
		
		$this->_string .= $formr::close();
		
		return $this->_string;
	}
	
	public function column_names()
	{
		if ( ! sizeof($this->_column_names) > 0)
		{		
			foreach($this->_object->list_columns() as $column)
			{
				array_push($this->_column_names, $column['column_name']);
			}
		}
		
		return $this->_column_names;
	}
}
