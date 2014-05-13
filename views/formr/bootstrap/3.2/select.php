<?php
echo '<div class="control-group'.(isset($options['errors'][$relation['relation_name']]) ? ' error': '').'">';
echo Formr_Render::label(array('column_name' => $relation['relation_name']), $options);
echo '<div class="controls">';
echo Form::select($name, $opts, $selected, $attributes);
echo (isset($options['help'][$relation['relation_name']]) or isset($options['errors'][$relation['relation_name']]))
? '<p class="help-block">'.(isset($options['errors'][$relation['relation_name']]) ? $options['errors'][$relation['relation_name']]: $options['help'][$relation['relation_name']]).'</p>'
: '';
echo '</div>';
echo '</div>';