<?php

namespace Modules\Coupon\Http\Requests;

use App\Http\Requests\FormRequest;

class CouponFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['coupon_code'] = ['required','unique:coupon,coupon_code'];
         if(request()->update_id){
            $rules['coupon_code'] = 'unique:coupon,coupon_code,'.request()->update_id;
        }
        $rules['coupon_description'] = ['nullable'];
        $rules['coupon_discount_type'] = ['required'];
        $rules['coupon_amount'] = ['required'];
        $rules['coupon_exp_date'] = ['required'];
        $rules['is_free_delivery'] = ['nullable'];
        $rules['coupon_min_spend']  = ['nullable'];
        $rules['coupon_max_spend']  = ['nullable'];
        $rules['product_id']  = ['nullable'];
        $rules['exclude_id']  = ['nullable'];
        $rules['category_id'] = ['nullable'];
        $rules['exclude_category_id']  = ['nullable'];
        $rules['customer_id']  = ['nullable'];
        $rules['include_customer_id'] = ['nullable'];
        $rules['is_individual'] = ['nullable'];
        $rules['is_exclude_sale']  = ['nullable'];
        $rules['limit_per_coupon']  = ['nullable'];
        $rules['limit_usage_times']  = ['nullable'];
        $rules['limit_per_user']  = ['nullable'];
        $rules['combo_id']  = ['nullable'];
        $rules['exclude_combo_id']  = ['nullable'];



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
