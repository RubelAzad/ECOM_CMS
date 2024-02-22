<?php

namespace Modules\ConditionalDiscount\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Base\Http\Controllers\BaseController;

use Modules\Category\Entities\Category;
use Modules\Coupon\Entities\Coupon;
use Modules\Coupon\Entities\CouponUserGroup;

use Modules\ConditionalDiscount\Http\Requests\ConditionalDiscountFormRequest;

use Modules\ConditionalDiscount\Entities\ConditionalDiscount;
use Modules\Location\Entities\District;

use Modules\Order\Entities\Upazila;

class ConditionalDiscountController extends BaseController
{
     
    public function __construct(ConditionalDiscount $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if(permission('conditional-discount-access')){
            $this->setPageData('Manage Conditional Discounts','Manage Conditional Discounts','fas fa-box');
            $districts=District::get(['id','name']);
            $upazilas=Upazila::get(['id','name']);
            $customer_group=CouponUserGroup::get(['id','group_name','customer_id']);

   
            return view('conditionaldiscount::index',compact('districts','upazilas','customer_group'));
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    public function GetUpazillas($district_id){
       
        $dc_id=(int)$district_id;
        $upazillas=Upazila::where('district_id', $dc_id)->get();
        return response()->json($upazillas);

    }

    public function get_datatable_data(Request $request)
    {
        if(permission('conditional-discount-access')){
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

                    if(permission('conditional-discount-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    // if(permission('conditional-discount-view')){
                    //     $action .= ' <a class="dropdown-item view_data" data-id="' . $value->id . '"><i class="fas fa-eye text-success"></i> View</a>';
                    // }
                    if(permission('conditional-discount-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->condition_name . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }

                    $row = [];

                    if(permission('conditional-discount-bulk-delete')){
                        $row[] = table_checkbox($value->id);
                    }
                    $row[] = $no;
                    // $row[] = $value->id;
                    $row[] = $value->condition_name;
                   $row[] = $value->condition_type == 'free_delivery'  ? 'Free Delivery' : ($value->condition_type == 'fixed_percentage_discount'  ? 'Fixed Percentage Discount' : ($value->condition_type == 'fixed_amount_discount' ? 'Fixed Amount Discount'  : 'Unknown Discount Type'));

                    $row[] = $value->condition_exp_date;
                    $row[] = $value->discount_amount ?? 'N/A';
                    $row[] = permission('conditional-discount-edit') ? change_status($value->id,$value->status,$value->condition_name) : STATUS_LABEL[$value->status];

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

    public function store_or_update_data(ConditionalDiscountFormRequest $request)
     {
        if($request->ajax()){
            if(permission('conditional-discount-add') || permission('conditional-discount-edit')){
             
                $collection = collect($request->validated());
                $collection = $this->track_data($request->update_id,$collection);
               
                if($request->is_exclude_sale ==''){
                    $is_exclude_sale = 2;
                    $collection = $collection->merge(compact('is_exclude_sale'));
                }
                if($request->district_id ==''){
                    $district_id = [];
                    $collection = $collection->merge(compact('district_id'));
                }
                if($request->upazila_id ==''){
                    $upazila_id = [];
                    $collection = $collection->merge(compact('upazila_id'));
                }
                if($request->customer_group ==''){
                    $customer_group = [];
                    $collection = $collection->merge(compact('customer_group'));
                }
                $result = $this->model->updateOrCreate(['id'=>$request->update_id],$collection->all());
                $output = $this->store_message($result,$request->update_id);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
           return response()->json($this->access_blocked());
        }
    }



    public function show(Request $request)
    {
        if($request->ajax()){
            if (permission('conditional-discount-view')) {
                $condition = $this->model->findOrFail($request->id);

                return view('conditionaldiscount::details',compact('condition'))->render();
            }
        }
    }

    public function edit(Request $request)
    {
        if($request->ajax()){
            if(permission('conditional-discount-edit')){
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
            if(permission('conditional-discount-delete')){
                $condition = $this->model->find($request->id);
                $result = $condition->delete();
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
            if(permission('conditional-discount-bulk-delete')){
                $conditions = $this->model->toBase()->whereIn('id',$request->ids)->get();
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

    public function change_status(Request $request)
    {
        if($request->ajax()){
            if (permission('conditional-discount-edit')) {
                $result = $this->model->find($request->id)->update(['status'=>$request->status]);
                $output = $result ? ['status'=>'success','message'=>'Status has been changed successfully']
                : ['status'=>'error','message'=>'Failed to change status'];
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }
}
