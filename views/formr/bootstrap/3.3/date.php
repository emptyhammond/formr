<?php
echo '<div class="form-group'.(isset($options['errors'][$column['column_name']]) ? ' has-error': '').'">';
echo Formr_Render::label($column, $options);
echo '<div class="col-sm-10">';

echo Form::input($column['column_name'], $value,
	Arr::merge(array(
		'autocomplete' => 'off',			
		'type' => 'date',
		'class' => 'date form-control '.$options['classes'][$column['column_name']],
	), $disabled, (isset($options['attributes'][$column['column_name']]) ? $options['attributes'][$column['column_name']] : array())));

echo (isset($options['help'][$column['column_name']]) or isset($options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset($options['errors'][$column['column_name']]) ? $options['errors'][$column['column_name']]: $options['help'][$column['column_name']]).'</p>' : '';

echo '</div>';
echo '</div>';