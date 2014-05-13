<?php
$disabled = in_array($column['column_name'], $options['disabled']) ? array('disabled' => true) : array();

echo '<div class="form-group'.(isset($options['errors'][$column['column_name']]) ? ' error': '').'">';
echo Formr_Render::label($column, $options);
echo '<div class="col-sm-10">';

echo Form::input($column['column_name'],($object->{$column['column_name']} ? $object->{$column['column_name']} : (isset($column['default']) ? $column['default'] : '')),
	Arr::merge(array(
		'type' => 'number',
		'min' => (isset($column['min']) ? $column['min'] : 0),
		'max' => (isset($column['max']) ? $column['max'] : 99999999999999),
		'step' => '0.01',
		'class' => 'number form-control '.$options['classes'][$column['column_name']],
	), $disabled, (isset($options['attributes'][$column['column_name']]) ? $options['attributes'][$column['column_name']] : array())));

echo (isset($options['help'][$column['column_name']]) or isset($options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset($options['errors'][$column['column_name']]) ? $options['errors'][$column['column_name']]: $options['help'][$column['column_name']]).'</p>' : '';

echo '</div>';
echo '</div>';