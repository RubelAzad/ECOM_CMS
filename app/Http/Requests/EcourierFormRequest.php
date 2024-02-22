<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EcourierFormRequest extends FormRequest
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
            'ep_name' => 'required|string',
            'pick_contact_person' => 'required|string',
            'pick_district' => 'nullable',
            'pick_thana' => 'nullable',
            'pick_hub' => 'nullable',
            'pick_union' => 'required',
            'pick_address' => 'nullable',
            'pick_mobile' => 'required',
//            'mobile_no' => 'required|string|max:15|unique:users,mobile_no,'.auth()->user()->id,
        ];
    }
}
