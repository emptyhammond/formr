<?php
echo '<div class="form-group'.(isset($options['errors'][$column['column_name']]) ? ' has-error': '').'">';
	echo Formr_Render::label($column, $options);
	echo '<div class="col-sm-10">';
		echo Form::file($column['column_name'],
			Arr::merge(array(
				'class' => 'form-control input-file '.$options['classes'][$column['column_name']],
			), $disabled, (isset($options['attributes'][$column['column_name']]) ? $options['attributes'][$column['column_name']] : array())));
		echo (isset($options['help'][$column['column_name']]) or isset($options['errors'][$column['column_name']])) ? '<p class="help-block">'.(isset($options['errors'][$column['column_name']]) ? $options['errors'][$column['column_name']]: $options['help'][$column['column_name']]).'</p>' : '';
	echo '</div>';
	echo '<span class="help-block col-sm-10">';
		echo $path ? HTML::anchor($path, 'Current uploaded file') : 'No current uploaded file';
	echo '</span>';
echo '</div>';
