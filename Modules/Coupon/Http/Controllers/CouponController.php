<?php

namespace Modules\Coupon\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Modules\Category\Entities\Category;
use Modules\Coupon\Entities\Coupon;
use Modules\Coupon\Entities\CouponUserGroup;
use Illuminate\Routing\Controller;
use Modules\Coupon\Http\Requests\CouponFormRequest;
use Modules\Customers\Entities\Customers;
use Modules\Inventory\Entities\Inventory;
use Modules\Combo\Entities\Combo;

class CouponController  extends BaseController
{
     
    public function __construct(Coupon $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if(permission('coupon-access')){
            $this->setPageData('Manage Coupon','Manage Coupon','fas fa-box');
            $products=Inventory::get(['id','title']);
            $categories=Category::get(['id','name']);
            $customer_group=CouponUserGroup::get(['id','group_name','customer_id']);
            $customers=Customers::get(['id','name']);
            $combos=Combo::get(['id','title']);
            // $data = [
            //     'categories' => Category::all(),
            //     'variants' => Variant::all(),
            //     'segments' => Segment::all(),
            //     'packTypes' => PackType::all(),
            // ];
            return view('coupon::index',compact('products','categories','customers','customer_group','combos'));
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if(permission('coupon-access')){
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

                    if(permission('coupon-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('coupon-view')){
                        $action .= ' <a class="dropdown-item view_data" data-id="' . $value->id . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    if(permission('coupon-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->coupon_code . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }

                    $row = [];

                    if(permission('coupon-bulk-delete')){
                        $row[] = table_checkbox($value->id);
                    }
                    $row[] = $no;
                    // $row[] = $value->id;
                    $row[] = $value->coupon_code;
                   $row[] = $value->coupon_discount_type == 'fixed_product_discount'  ? 'Fixed Product Discount' : ($value->coupon_discount_type == 'percentage_discount'  ? 'Percentage Discount' : ($value->coupon_discount_type == 'fixed_amount_discount' ? 'Fixed Amount Discount'  : 'Unknown Discount Type'));

                    $row[] = $value->coupon_amount;
                    $row[] = $value->coupon_exp_date;
                    $row[] = permission('coupon-edit') ? change_status($value->id,$value->status,$value->coupon_code) : STATUS_LABEL[$value->status];

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

    public function store_or_update_data(CouponFormRequest $request)
     {
        if($request->ajax()){
            if(permission('coupon-add') || permission('coupon-edit')){
             
                $collection = collect($request->validated());
                $collection = $this->track_data($request->update_id,$collection);
               
                 if($request->is_free_delivery ==''){
                    $is_free_delivery = 2;
                    $collection = $collection->merge(compact('is_free_delivery'));
                }
                if($request->is_individual ==''){
                    $is_individual = 2;
                    $collection = $collection->merge(compact('is_individual'));
                }
                if($request->is_exclude_sale ==''){
                    $is_exclude_sale = 2;
                    $collection = $collection->merge(compact('is_exclude_sale'));
                }
                 if($request->product_id ==''){
                    $product_id = [];
                    $collection = $collection->merge(compact('product_id'));
                }
                if($request->exclude_id ==''){
                    $exclude_id = [];
                    $collection = $collection->merge(compact('exclude_id'));
                }
                if($request->category_id ==''){
                    $category_id = [];
                    $collection = $collection->merge(compact('category_id'));
                }
                if($request->exclude_category_id ==''){
                    $exclude_category_id = [];
                    $collection = $collection->merge(compact('exclude_category_id'));
                }
                if($request->combo_id ==''){
                    $combo_id = [];
                    $collection = $collection->merge(compact('combo_id'));
                }
                if($request->exclude_combo_id ==''){
                    $exclude_combo_id = [];
                    $collection = $collection->merge(compact('exclude_combo_id'));
                }
                if($request->customer_id ==''){
                    $customer_id = [];
                    $collection = $collection->merge(compact('customer_id'));
                }
                if($request->include_customer_id ==''){
                    $include_customer_id = [];
                    $collection = $collection->merge(compact('include_customer_id'));
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
            if (permission('coupon-view')) {
                $coupon = $this->model->findOrFail($request->id);

                return view('coupon::details',compact('coupon'))->render();
            }
        }
    }

    public function edit(Request $request)
    {
        if($request->ajax()){
            if(permission('coupon-edit')){
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
            if(permission('coupon-delete')){
                $coupon = $this->model->find($request->id);
                $result = $coupon->delete();
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
            if(permission('coupon-bulk-delete')){
                $coupons = $this->model->toBase()->whereIn('id',$request->ids)->get();
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
            if (permission('coupon-edit')) {
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
