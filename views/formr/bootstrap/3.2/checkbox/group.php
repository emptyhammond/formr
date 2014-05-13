<?php
echo '<div class="control-group">';
echo Formr_Render::label(array('column_name' => $relation['relation_name']), $options);
echo '<div class="controls">';

foreach($items as $option)
{
	if(isset($options['display'][$relation['relation_name']]))
	{
		if (method_exists($option, $options['display'][$relation['relation_name']]))
		{
			$display_value = preg_replace("/&#?[a-z0-9]{2,8};/i","",call_user_func_array(array($option, $options['display'][$relation['relation_name']]), array()));
		}
		else
		{
			$display_value = preg_replace("/&#?[a-z0-9]{2,8};/i","",$option->{$options['display'][$relation['relation_name']]});
		}
	}
	else
	{
		$display_value = preg_replace("/&#?[a-z0-9]{2,8};/i","",(isset($option->name) ? $option->name : $option->{$model->primary_key()}));
	}

	echo '<label class="checkbox inline">';
	echo '<input type="checkbox" name="'.$name.'" id="'.$relation['model'].$option->pk().'" value="'.$option->pk().'"';
	echo (!$_POST and $object->has($plural, $option->pk())) ? ' checked="checked"' : '';
	echo (isset($_POST[$plural]) and in_array($option->pk(),$_POST[$plural])) ? ' checked="checked"' : '';
	echo ' />';
	echo $display_value;
	echo '</label>';
}

echo '</div>';
echo '</div>';