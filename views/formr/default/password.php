<?php
echo '<div class="'.(isset($options['errors'][$column['column_name']]) ? ' error': '').'">';
echo Formr_Render::label($column, $options);

echo Form::password($column['column_name'], null,
	Arr::merge(array(
		'class' => $options['classes'][$column['column_name']],
		'maxlength' => isset($column['character_maximum_length']) ? $column['character_maximum_length'] : 255,
	), $disabled, (isset($options['attributes'][$column['column_name']]) ? $options['attributes'][$column['column_name']] : array())));

echo (isset($options['help'][$column['column_name']]) or isset($options['errors'][$column['column_name']])) ? '<p>'.(isset($options['errors'][$column['column_name']]) ? $options['errors'][$column['column_name']]: $options['help'][$column['column_name']]).'</p>' : '';

echo '</div>';