<?php
echo '<div class="form-group'.(isset($options['errors'][$column['column_name']]) ? ' has-error': '').'">';
echo Formr_Render::label(array('column_name' => $column['column_name']), $options);
echo '<div class="col-sm-10">';
if (isset($attributes['classes']))
{
	$attributes['class'] .= ' form-control';
}
else
{
	$attributes['class'] = 'form-control';
}
echo Form::select($column['column_name'], $opts, $selected, $attributes);
echo (isset($options['help'][$column['column_name']]) or isset($options['errors'][$column['column_name']]))
? '<p class="help-block">'.(isset($options['errors'][$column['column_name']]) ? $options['errors'][$column['column_name']]: $options['help'][$column['column_name']]).'</p>'
: '';
echo '</div>';
echo '</div>';
