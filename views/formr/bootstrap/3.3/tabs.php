<?php

foreach($options['fieldsets'] as $fieldset => $inputs)
{
	if (is_array(current($inputs)))
	{
		$list .= '<li class="dropdown">';
		$list .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$fieldset.' <b class="caret"></b></a>';
		$list .= '<ul class="dropdown-menu">';
		
		foreach($inputs as $tab => $fields)
		{
			$list .= '<li class="'.($active ? 'active' : '').'"><a data-toggle="tab" href="#'.strtolower(preg_replace('/\s*/', '',$tab)).'">'.$tab.'</a></li>';
			
			$content .= '<div id="'.strtolower(preg_replace('/\s*/', '',$tab)).'" class="tab-pane '.($active ? 'active' : '').'">';
			$content .= '<fieldset>';
			$content .= '<legend>'.$tab.'</legend>';

			foreach($fields as $field)
			{
				if (isset($output[$field]))
				{
					$content .= $output[$field];	
				}
			}	
			
			$content .= '</fieldset>';
			$content .= '</div>';
			
			$active = false;
		}
		
		$list .= '</ul>';
		$list .= '</li>';	
	}
	else
	{
		$list .= '<li class="'.($active ? 'active' : '').'"><a data-toggle="tab" href="#'.strtolower(preg_replace('/\s*/', '',$fieldset)).'">'.$fieldset.'</a></li>';
		$content .= '<div id="'.strtolower(preg_replace('/\s*/', '',$fieldset)).'" class="tab-pane '.($active ? 'active' : '').'">';
		$content .= '<fieldset>';
		$content .= '<legend>'.$fieldset.'</legend>';
		foreach($inputs as $input)
		{
			if (isset($output[$input]))
			{
				$content .= $output[$input];	
			}
		}
		
		$content .= '</fieldset>';
		$content .= '</div>';
		
		$active = false;	
	}
}

echo '<div class="tabbable">';
echo '<ul class="nav nav-tabs">';
echo $list;
echo '</ul>';
echo '<div class="tab-content">';
echo $content;
echo $formr::actions($options);						
echo '</div>';			
echo '</div>';
