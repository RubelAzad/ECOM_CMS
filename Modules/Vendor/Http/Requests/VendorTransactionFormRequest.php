<?php

namespace Modules\Vendor\Http\Requests;

use App\Http\Requests\FormRequest;

class VendorTransactionFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['voucher_type'] = ['nullable'];
        $rules['voucher_no'] = ['nullable'];
        $rules['payment_type'] = ['required'];
        $rules['cash_person_name'] = ['nullable'];
        $rules['online_mobile'] = ['nullable'];
        $rules['online_transaction_number'] = ['nullable'];
        $rules['bank_name'] = ['nullable'];
        $rules['bank_account'] = ['nullable'];
        $rules['voucher_date'] = ['nullable'];
        $rules['vendor_id'] = ['required'];
        $rules['invoice_id'] = ['nullable'];
        $rules['wallet_amount'] = ['nullable'];
        $rules['payment_amount'] = ['nullable'];
        $rules['remark'] = ['nullable'];

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
