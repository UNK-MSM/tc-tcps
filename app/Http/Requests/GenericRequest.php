<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;

class GenericRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $routeNameWithAction = \Request::route()->getName();
        $routeName = explode('.', $routeNameWithAction);
        $routeName = $routeName[sizeof($routeName)-2];
        $className = studly_case($routeName);
        $model = 'App\\'.$className;

        $controls = $model::$fillableFields;
        $rules = array();
        foreach($controls as $controlName => $control)
        {
            if(isset($control['validation']))
            {
                $rules[$controlName] = $control['validation'];
            }
        }
        return $rules;
    }

    /*
    protected function formatErrors(Validator $validator)
    {
        return $validator->errors()->all();
    }*/
}
