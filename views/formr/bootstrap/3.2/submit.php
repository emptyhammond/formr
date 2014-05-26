<?php
if (isset($attributes['class']))
{
	$attributes['class'] .= ' btn btn-primary';
}
else
{
	$attributes['class'] = 'btn btn-primary';
}
echo Form::submit($name, $value, $attributes).' ';
