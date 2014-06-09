<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
	<?php	
	if ($options['actions'])
	{
		foreach($options['actions'] as $name => $parameters)
		{
			$parameters = array_merge(array('name' => 'name', 'value' => 'value', 'attributes' => array('class' => 'btn btn-default')), $parameters);
	
			echo call_user_func(__NAMESPACE__ .'Formr_Render::'.$parameters['type'], $name, $parameters['value'], $parameters['attributes']);
		}
	}
	else
	{
		echo Formr_Render::submit();
		echo Formr_Render::reset();
	}
	?>
    </div>
</div>
