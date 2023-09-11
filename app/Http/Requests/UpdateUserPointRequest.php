<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateUserPointRequest
 * @package App\Http\Requests
 * @property-read $point
 * @property-read $type
 */
class UpdateUserPointRequest extends FormRequest
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
            'point' => 'required|integer',
            'type' => 'required|in:addition,subtraction',

            'chargereason' => 'nullable|string',    // by ohneta 2022.08.25

            'reason' => 'nullable|string|max:200'
        ];
    }
}
