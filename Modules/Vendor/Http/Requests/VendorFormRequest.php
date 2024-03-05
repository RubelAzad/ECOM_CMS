<?php

namespace Modules\Vendor\Http\Requests;

use App\Http\Requests\FormRequest;

class VendorFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['name'] = ['required','string'];
        $rules['address'] = ['required','string'];
        $rules['phone_number'] = ['required','string','unique:customers,phone_number'];
        $rules['email'] = ['required','string','unique:customers,email'];
          if(request()->update_id){
            $rules['phone_number'] = 'unique:customers,phone_number,'.request()->update_id;
            $rules['email'] = 'unique:customers,email,'.request()->update_id;
        }
      
        $rules['password'] = ['required'];
        $rules['date_of_birth'] = ['nullable'];
        $rules['gender'] = ['nullable'];
       

      
        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
