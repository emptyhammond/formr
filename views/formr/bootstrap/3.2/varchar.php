<?php
echo '<div class="control-group'.(isset($options['errors'][$column['column_name']]) ? ' error': '').'">';
echo Formr_Render::label($column, $options);
echo '<div class="controls">';
echo Form::input($column['column_name'],$value,
	Arr::merge(array(
		'class' => $options['classes'][$column['column_name']],
		'maxlength' => isset($column['character_maximum_length']) ? $column['character_maximum_length'] : '8000',
	), $disabled, (isset($options['attributes'][$column['column_name']]) ? $options['attributes'][$column['column_name']] : array())));

echo (isset($options['help'][$column['column_name']]) or isset($options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset($options['errors'][$column['column_name']]) ? $options['errors'][$column['column_name']]: $options['help'][$column['column_name']]).'</p>' : '';
echo '</div>';
echo '</div>';