<?php

namespace Modules\UserGroup\Http\Requests;

use App\Http\Requests\FormRequest;

class UserGroupFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['group_name'] = ['required','unique:coupon_user_groups,group_name'];
         if(request()->update_id){
            $rules['group_name'] = 'unique:coupon_user_groups,group_name,'.request()->update_id;
        }
        $rules['group_description'] = ['nullable'];
        $rules['customer_id'] = ['required'];


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
