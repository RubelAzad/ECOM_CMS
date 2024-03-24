<?php

namespace Modules\Vendor\Http\Controllers;


use Modules\Base\Http\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\Vendor\Entities\Vendors;
use Modules\Vendor\Entities\VendorAccount;
use Modules\Vendor\Entities\VendorTransaction;
use Modules\Vendor\Http\Requests\VendorTransactionFormRequest;

class VendorTranscationsController extends BaseController
{
    public function __construct(VendorTransaction $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if(permission('vendor-access')){
            $this->setPageData('Vendors','Vendors','fas fa-th-list');

            $data = [
                'vendors' => Vendors::where('customer_type','1')->get()
            ];
            return view('vendor::vendor-transaction-list',$data);
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if(permission('vendor-access')){
            if($request->ajax()){
                if (!empty($request->name)) {
                    $this->model->setName($request->name);
                }

                $this->set_datatable_default_property($request);
                $list = $this->model->getDatatableList();

                $data = [];
                $no = $request->input('start');
                foreach ($list as $value) {
                    $no++;
                    $action = '';

                    if(permission('vendor-view')){
                        $action .= ' <a class="dropdown-item view_data" data-id="' . $value->id . '"><i class="fas fa-eye text-primary"></i> View</a>';
                    }
                    if(permission('vendor-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }

                    if(permission('vendor-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }

                    $row = [];

                    if(permission('vendor-bulk-delete')){
                        $row[] = table_checkbox($value->id);
                    }
                    $row[] = $value->id;
                    $row[] = $value->vendor->name;
                    $row[] = $value->voucher_type;
                    $row[] = $value->payment_type;
                    $row[] = $value->voucher_date;
                    $row[] = $value->online_mobile;
                    $row[] = $value->online_transaction_number;
                    $row[] = $value->bank_name;
                    $row[] = $value->bank_account;
                    $row[] = $value->payment_amount;
                    $row[] = action_button($action);
                    $data[] = $row;
                }
                return $this->datatable_draw($request->input('draw'),$this->model->count_all(),
                 $this->model->count_filtered(), $data);
            }else{
                $output = $this->access_blocked();
            }

            return response()->json($output);
        }
    }


    public function store_or_update_data(VendorTransactionFormRequest $request)
{
    if ($request->ajax()) {
        if (permission('vendor-edit')) {
            $collection = collect($request->validated());
                $collection = $this->track_data($request->update_id,$collection);
                $result = $this->model->updateOrCreate(['id'=>$request->update_id],$collection->all());
                $voucher_type=$result->voucher_type;
                $vendor=VendorAccount::where('vendor_id', $result->vendor_id)->first();
                $vendorUserAmount=$vendor->vendor_use_amount;
                $paymentAmount=$result->payment_amount;
                if($voucher_type === "Debit"){
                    $finalDebitAmount=$vendorUserAmount - $paymentAmount;
                    VendorAccount::where('vendor_id', $result->vendor_id)->update(['vendor_use_amount' => $finalDebitAmount]);

                }
                if($voucher_type === "Credit"){
                    $finalCreditAmount=$vendorUserAmount + $paymentAmount;
                    VendorAccount::where('vendor_id', $result->vendor_id)->update(['vendor_use_amount' => $finalCreditAmount]);

                }
                $output = $this->store_message($result,$request->update_id);


        } else {
            $output = $this->access_blocked();
        }
        return response()->json($output);
    } else {
        return response()->json($this->access_blocked());
    }
}



    public function edit(Request $request)
    {
        if($request->ajax()){
            if(permission('vendor-edit')){
                $data = $this->model->findOrFail($request->id);
                $vendorAccount = VendorTransaction::where('vendor_id', $data->id)->first(); // Fetch associated vendor account
                $output = [
                    'vendor' => $data,
                    'vendor_account' => $vendorAccount, // Include vendor account data in the output
                ];
                return response()->json($output);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }
    public function view(Request $request)
    {
        if($request->ajax()){
            if(permission('vendor-edit')){
                $data = $this->model->findOrFail($request->id);
                $output = $this->data_message($data);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }

    public function delete(Request $request)
    {
        if($request->ajax()){
            if(permission('vendor-delete')){
                $result = $this->model->find($request->id)->delete();
                $output = $this->delete_message($result);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }

    public function bulk_delete(Request $request)
    {
        if($request->ajax()){
            if(permission('vendor-bulk-delete')){
                $result = $this->model->destroy($request->ids);
                $output = $this->bulk_delete_message($result);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }

    public function getWalletAmount(Request $request)
    {
        $vendorId = $request->input('vendor_id');
        $vendor_use_amount = 0; // Default value

        // Retrieve the vendor from the database
        $VendorAccount = VendorAccount::where('vendor_id',$vendorId)->first();
        // Check if the vendor exists and has a wallet amount
        if ($VendorAccount && $VendorAccount->vendor_use_amount) {
            $vendor_use_amount = $VendorAccount->vendor_use_amount;
        }

        return response()->json(['wallet_amount' => $vendor_use_amount]);
    }
}
