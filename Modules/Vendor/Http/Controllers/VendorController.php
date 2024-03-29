<?php

namespace Modules\Vendor\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Base\Http\Controllers\BaseController;
use Modules\Vendor\Http\Requests\VendorFormRequest;
use Modules\Vendor\Entities\Vendors;
use Modules\Vendor\Entities\VendorAccount;
use App\Traits\UploadAble;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class VendorController extends BaseController
{
    use UploadAble;
    public function __construct(Vendors $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if(permission('vendor-access')){
            $this->setPageData('Vendors','Vendors','fas fa-th-list');
            return view('vendor::index');
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
                    $row[] = $value->name;
                    $row[] = $value->email;
                    $row[] = $value->address;;
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


    public function store_or_update_data(VendorFormRequest $request)
{
    if ($request->ajax()) {
        if (permission('vendor-edit')) {
            $customerData = $request->validated();

            // Create or update customer data
            $customerCollection = collect($customerData)->except(['password','vendor_amount','amount_percentage','vendor_use_amount']);
            $password = Hash::make($request->password);
            $customer_type = '1';
            $customerCollection = $customerCollection->merge(compact('password','customer_type'));

            $customerCollection = $this->track_data($request->update_id, $customerCollection);
            $customer = $this->model->updateOrCreate(['id' => $request->update_id], $customerCollection->all());

            $vendor_id = $customer->id ?? null;

            VendorAccount::updateOrCreate(
                ['vendor_id' => $vendor_id],
                [
                    'vendor_amount' => $request->vendor_amount,
                    'amount_percentage' => $request->amount_percentage,
                    'vendor_use_amount' => $request->vendor_use_amount
                ]
            );

            $output = $this->store_message($customer, $request->update_id);
        } else {
            $output = $this->access_blocked();
        }
        return response()->json($output);
    } else {
        return response()->json($this->access_blocked());
    }
}
// public function store_or_update_data(VendorFormRequest $request)
// {
//     if ($request->ajax()) {
//         if (permission('vendor-edit')) {
//             $collection = collect($request->validated())->except(['password','vendor_amount','amount_percentage','vendor_use_amount']);
//             $password = Hash::make($request->password);
//             $collection = $collection->merge(compact('password'));
//             $collection = $this->track_data($request->update_id, $collection);

//             $result = $this->model->updateOrCreate(['id' => $request->update_id], $collection->all());
//             $output = $this->store_message($result, $request->update_id);

//             // Insert data into vendor_accounts table regardless of customer update success
//             if ($result) { // Check if customer update/creation was successful
//                 $customerId = $result->id;

//                 $vendorAccountData = [
//                     'vendor_id' => $customerId,
//                     'vendor_amount' => $request->vendor_amount,
//                     'amount_percentage' => $request->amount_percentage,
//                     'vendor_use_amount' => $request->vendor_use_amount,
//                 ];

//                 VendorAccount::create($vendorAccountData);
//             }
//         } else {
//             $output = $this->access_blocked();
//         }
//         return response()->json($output);
//     } else {
//         return response()->json($this->access_blocked());
//     }
// }



    public function edit(Request $request)
    {
        if($request->ajax()){
            if(permission('vendor-edit')){
                $data = $this->model->findOrFail($request->id);
                $vendorAccount = VendorAccount::where('vendor_id', $data->id)->first(); // Fetch associated vendor account
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
}
