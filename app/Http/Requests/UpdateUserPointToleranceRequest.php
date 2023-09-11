<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateUserPointToleranceRequest
 * @package App\Http\Requests
 * @property-read $point_tolerance
 */
class UpdateUserPointToleranceRequest extends FormRequest
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
        return [
 //           'pointtolerance' => 'required|integer',
//            'point_tolerance' => 'required|integer',
            'point_tolerance' => 'required|integer',
        ];
    }
}
