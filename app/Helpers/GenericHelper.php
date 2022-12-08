<?php
use Illuminate\Support\Str;
if (!function_exists('generateFormFields')) {
	
    function generateFormFields($controls, $values = null, $formVerticalSections = 2)
    {
		$data = array();
		//["customer_no" => ["type" => "text"]]
		$data['enctype'] = 'application/x-www-form-urlencoded';
		$data['rows'] = array();
		$data['rows'][] = array();
		$controlIndex = 0;
		$counter = -1;
		foreach($controls as $controlName => $control)
		{
			if(isset($control['insertable']) && !$control['insertable'])
			{
				continue;
			}
			if($controlIndex%$formVerticalSections == 0){
				$counter++;
			}
			if($control == [] || $control['type'] === 'empty')
			{
				$counter++;
				$controlIndex++;
				continue;
			}

			if($control['type'] === 'range_input')
			{
				if(isset($values[$controlName.'_from']))
				{
					$control['value'] = $values[$controlName.'_from'].';'.$values[$controlName.'_to'];
				}
			}else if($control['type'] === 'range_input_mx')
			{
				if(isset($values[$controlName.'_min']))
				{
					$control['value'] = $values[$controlName.'_min'].';'.$values[$controlName.'_max'];
				}
			}else if($control['type'] === 'multiselect' || (isset($control['multiselect']) && $control['multiselect']))
			{
				if(isset($values[$controlName]))
				{
					$control['value'] = collect($values[$controlName])->pluck(['serial_no'])->toArray();
				}
			}else
			{
				if(isset($values[$controlName]))
				{
					$control['value'] = $values[$controlName];
				}
			}

			$inputGroup = null;
			$validation = null;
			$type = null;
			$list = null;
			$radio_buttons = null;
			$class = null;
			$mask = null;
			$value = null;
			$updatable = null;
			$control_data = null;
			$switch = null;
			$multiselect = null;
			if(isset($control))
			{
				if(isset($control['input-group']))
				{
					$inputGroup = $control['input-group'];
				}
				if(isset($control['type']))
				{
					$type = $control['type'];
				}
				if(isset($control['list']))
				{
					$list = $control['list'];
				}
				if(isset($control['radio_buttons']))
				{
					$radio_buttons = $control['radio_buttons'];
				}
				if(isset($control['validation']))
				{
					$validation = $control['validation'];
				}
				if(isset($control['class']))
				{
					$class = $control['class'];
				}
				if(isset($control['mask']))
				{
					$mask = $control['mask'];
				}
				if(isset($control['value']))
				{
					$value = $control['value'];
				}
				if(isset($control['updatable']))
				{
					$updatable = $control['updatable'];
				}
				if(isset($control['data']))
				{
					$control_data = $control['data'];
				}
				if(isset($control['switch']))
				{
					$switch = $control['switch'];
				}
				if(isset($control['multiselect']))
				{
					$multiselect = $control['multiselect'];
				}
			}
			$field = array();
			$field['type'] = 'text';
			if(isset($type))
			{
				$field['type'] = $type;
				if($type === 'image')
				{
					$data['enctype'] = 'multipart/form-data';
				}
			}
			if(isset($inputGroup))
			{
				$field['input-group'] = $inputGroup;
			}
			if(isset($validation))
			{
				$field['validation'] = $validation;
			}
			if(isset($class))
			{
				$field['class'] = $class;
			}
			if(isset($mask))
			{
				$field['mask'] = $mask;
			}
			if(isset($value))
			{
				$field['value'] = $value;
			}
			if(isset($updatable))
			{
				$field['updatable'] = $updatable;
			}
			if(isset($control_data))
			{
				if(isset($control_data['route']))
				{
					$control_data['url'] = route($control_data['route']);
				}else if(isset($control_data['route_url']))
				{
					$control_data['url'] = url($control_data['route_url']);
				}
				$field['control_data'] = $control_data;
			}
			if(isset($switch))
			{
				$field['switch'] = $switch;
			}
			if(isset($multiselect))
			{
				$field['multiselect'] = $multiselect;
			}

			if(in_array($field['type'], ['list', 'multiselect', 'radio_buttons']))
			{
				if(isset($list))
				{
					$field['list'] = $list;
				}else
				{
					//if(strpos($controlName, 'serial_no') !== FALSE)
					//{
						if(isset($control['list_reference']))
						{
							$modelName = $control['list_reference'];
						}else
						{
							$modelName = str_replace('_serial_no', '', $controlName);
						}
				        $className = studly_case($modelName);
				        $model = 'App\\'.$className;
				        $field['list'] = $model::lists($model::getLabel(), 'serial_no');
					//}
				}
			}else if($field['type'] === 'group_list')
			{
				$field['list'] = array();
				if(isset($control['list_reference']))
				{
					$modelName = $control['list_reference'];
				}else
				{
					$modelName = str_replace('_serial_no', '', $controlName);
				}
		        $className = 'App\\'.studly_case($modelName);
            	$relation = str_plural($modelName);
				$parentModelName = 'App\\'.$control['list_group_parent'];
				foreach($parentModelName::get() as $key => $value)
				{
					$groupValues = $value->$relation()->pluck($className::getLabel(), 'serial_no');
					$field['list'][$value[$parentModelName::getLabel()]] = $groupValues;
				}
			}else if(in_array($field['type'], ['tabular', 'urgent-calculation-tabular', 'membership-plan-instances-tabular']))
			{
				$className = studly_case(str_singular($controlName));
		        $model = 'App\\'.$className;
		        $field['table'] = $model::$fillableFields;
			}
			$data['rows'][$counter][$controlName] = ["width" => "col-md-".(12/$formVerticalSections), "field" => $field];
			$controlIndex++;
		}
		return $data;

    }
}