<?php
echo '<div class="control-group form-inline '.(isset($options['errors'][$column['column_name']]) ? ' error': '').'">';

echo Formr_Render::label($column, $options);

echo '<div class="controls">';

echo Form::input($column['column_name'], $value,
	Arr::merge(array(
		'autocomplete' => 'off',
		'type' => 'date',
		'class' => 'date '.$options['classes'][$column['column_name']],
	), $disabled, (isset($options['attributes'][$column['column_name']]) ? $options['attributes'][$column['column_name']] : array())));

echo ' '.Form::select('hour['.$column['column_name'].']', $hours, ($object->{$column['column_name']} ? date('H',$object->{$column['column_name']}) : '09'), array('type' => 'number', 'class' => 'input-mini hour-datetime'));

echo ' <label class="label-datetime" for=":">:</label> ';

echo Form::select('minute['.$column['column_name'].']', $minutes, ($object->{$column['column_name']} ? date('i',$object->{$column['column_name']}) : '00'), array('type' => 'number', 'class' => 'input-mini minute-datetime'));

echo (isset($options['help'][$column['column_name']]) or isset($options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset($options['errors'][$column['column_name']]) ? $options['errors'][$column['column_name']]: $options['help'][$column['column_name']]).'</p>' : '';

echo '</div>';
echo '</div>';