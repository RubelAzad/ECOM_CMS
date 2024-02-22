<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class eCourierOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['ep_id'] = ['required'];
        $rules['ep_name'] = ['required'];
        $rules['pick_contact_person'] = ['required'];
        $rules['pick_district'] = ['required'];
        $rules['pick_thana'] = ['required'];
        $rules['pick_hub'] = ['required'];
        $rules['pick_union'] = ['required'];
        $rules['pick_mobile'] = ['required'];
        $rules['pick_address'] = ['required'];



        $rules['recipient_name'] = ['required'];
        $rules['recipient_mobile'] = ['required'];
        $rules['recipient_district'] = ['required'];
        $rules['recipient_city'] = ['required'];
        $rules['recipient_thana'] = ['required'];
        $rules['recipient_area'] = ['required'];
        $rules['recipient_union'] = ['required'];
        $rules['package_code'] = ['required'];
        $rules['recipient_address'] = ['required'];
        $rules['parcel_detail'] = ['required'];
        $rules['number_of_item'] = ['required'];
        $rules['product_price'] = ['required'];
        $rules['actual_product_price'] = ['required'];
        $rules['payment_method'] = ['required'];


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
