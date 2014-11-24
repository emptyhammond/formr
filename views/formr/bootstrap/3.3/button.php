<?php
if (isset($attributes['class']))
{
	$attributes['class'] .= ' btn btn-default';
}
else
{
	$attributes['class'] = 'btn btn-default';
}
echo Form::button($name, $value, $attributes).' ';