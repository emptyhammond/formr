<?php
echo  = '<div class="control-group">';
echo Formr_Render::label($column, $options);
echo '<div class="controls">';
echo '<label class="checkbox inline">';
echo '<input type="checkbox" name="'.$column['column_name'].'" id="'.$column['column_name'].'" value="1"'.((isset($object->{$column['column_name']}) and (boolean) $object->{$column['column_name']}) ? ' checked' : ((isset($options['values'][$column['column_name']]) and $options['values'][$column['column_name']]) ? ' checked' : null)).' class="'.$options['classes'][$column['column_name']].(isset($options['disabled'][$column['column_name']]) ? ' disabled' : false).' "/>';
echo '</label>';

echo (isset($options['help'][$column['column_name']]) or isset($options['errors'][$column['column_name']])) ? (isset($options['errors'][$column['column_name']]) ? '<p class="help-block">'.$options['errors'][$column['column_name']].'</p>' : '<p class="help-block">'.$options['help'][$column['column_name']].'</p>') : '';
echo '</div>';
echo '</div>';