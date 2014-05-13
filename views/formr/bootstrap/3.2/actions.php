<?php
echo '<div class="form-actions">';

if ($options['actions'])
{
	foreach($options['actions'] as $name => $parameters)
	{
		$parameters = array_merge(array('name' => 'name', 'value' => 'value', 'attributes' => array('class' => 'btn')), $parameters);

		echo call_user_func(__NAMESPACE__ .'$this->'.$parameters['type'], $name, $parameters['value'], $parameters['attributes']);
	}
}
else
{
	echo Formr_Render::submit();
	echo Formr_Render::reset();
}

echo '</div>';