<?php
echo '<div class="'.(isset($options['errors'][$column['column_name']]) ? ' error': '').'">';
echo Formr_Render::label(array('column_name' => $column['column_name']), $options);
echo Form::select($column['column_name'], $options, $selected, $attributes);
echo (isset($options['help'][$column['column_name']]) or isset($options['errors'][$column['column_name']]))
? '<p class="">'.(isset($options['errors'][$column['column_name']]) ? $options['errors'][$column['column_name']]: $options['help'][$column['column_name']]).'</p>'
: '';
echo '</div>';