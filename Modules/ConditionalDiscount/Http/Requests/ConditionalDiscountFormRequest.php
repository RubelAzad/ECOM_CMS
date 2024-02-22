<?php

namespace Modules\ConditionalDiscount\Http\Requests;

use App\Http\Requests\FormRequest;

class ConditionalDiscountFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['condition_name'] = ['required'];
        //  if(request()->update_id){
        //     $rules['coupon_code'] = 'unique:coupon,coupon_code,'.request()->update_id;
        // }
        $rules['condition_type'] = ['required'];
        $rules['condition_exp_date'] = ['required'];
        $rules['discount_amount'] = ['nullable'];
        $rules['is_exclude_sale'] = ['nullable'];
        $rules['min_spend']  = ['nullable'];
        $rules['max_spend']  = ['nullable'];
        $rules['district_id']  = ['nullable'];
        $rules['upazila_id']  = ['nullable'];
        $rules['customer_group'] = ['nullable'];




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
