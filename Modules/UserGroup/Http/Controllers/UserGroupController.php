<?php

namespace Modules\UserGroup\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\UserGroup\Http\Requests\UserGroupFormRequest;
use Modules\Customers\Entities\Customers;
use Modules\UserGroup\Entities\UserGroup;
use Illuminate\Routing\Controller;
use Modules\Base\Http\Controllers\BaseController;

class UserGroupController extends BaseController
{
     
    public function __construct(UserGroup $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if(permission('user-group-access')){
            $this->setPageData('Manage User Group','Manage User Group','fas fa-box');
            // $user_groups=UserGroup::get(['id','title']);
            // $categories=Category::get(['id','name']);
            // $customer_group=CouponUserGroup::get(['id','group_name','customer_id']);
            $customers=Customers::get(['id','name']);
            // $combos=Combo::get(['id','title']);
            // $data = [
            //     'categories' => Category::all(),
            //     'variants' => Variant::all(),
            //     'segments' => Segment::all(),
            //     'packTypes' => PackType::all(),
            // ];
            return view('usergroup::index',compact('customers'));
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if(permission('user-group-access')){
            if($request->ajax()){
                if (!empty($request->name)) {
                    $this->model->setName($request->name);
                }
                

                $this->set_datatable_default_property($request);
                $list = $this->model->getDatatableList();

                // dd($customers);

                $data = [];
                $no = $request->input('start');
                foreach ($list as $value) {
                    $no++;
                    $action = '';

                    if(permission('user-group-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    // if(permission('user-group-view')){
                    //     $action .= ' <a class="dropdown-item view_data" data-id="' . $value->id . '"><i class="fas fa-eye text-success"></i> View</a>';
                    // }
                    if(permission('user-group-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->group_name . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }

                    $row = [];

                    if(permission('user-group-bulk-delete')){
                        $row[] = table_checkbox($value->id);
                    }
                    $row[] = $no;
                    // $row[] = $value->id;
                    $row[] = $value->group_name;
                    $row[] = $value->group_description;
                     // Now it executes the query and returns a collection of models

                    $row[] = $value->customer_names;
          
                    $row[] = permission('user-group-edit') ? change_status($value->id,$value->status,$value->group_name) : STATUS_LABEL[$value->status];

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

    public function store_or_update_data(UserGroupFormRequest $request)
     {
        if($request->ajax()){
            if(permission('user-group-add') || permission('user-group-edit')){
             
                $collection = collect($request->validated());
                $collection = $this->track_data($request->update_id,$collection);
               
                 if($request->customer_id ==''){
                    $customer_id = [];
                    $collection = $collection->merge(compact('customer_id'));
                }
                // if($request->is_individual ==''){
                //     $is_individual = 2;
                //     $collection = $collection->merge(compact('is_individual'));
                // }
                // if($request->is_exclude_sale ==''){
                //     $is_exclude_sale = 2;
                //     $collection = $collection->merge(compact('is_exclude_sale'));
                // }
                // if($request->product_id ==''){
                //     $product_id = [];
                //     $collection = $collection->merge(compact('product_id'));
                // }
                // if($request->exclude_id ==''){
                //     $exclude_id = [];
                //     $collection = $collection->merge(compact('exclude_id'));
                // }
                // if($request->category_id ==''){
                //     $category_id = [];
                //     $collection = $collection->merge(compact('category_id'));
                // }
                // if($request->exclude_category_id ==''){
                //     $exclude_category_id = [];
                //     $collection = $collection->merge(compact('exclude_category_id'));
                // }
                // if($request->combo_id ==''){
                //     $combo_id = [];
                //     $collection = $collection->merge(compact('combo_id'));
                // }
                // if($request->exclude_combo_id ==''){
                //     $exclude_combo_id = [];
                //     $collection = $collection->merge(compact('exclude_combo_id'));
                // }
                // if($request->customer_id ==''){
                //     $customer_id = [];
                //     $collection = $collection->merge(compact('customer_id'));
                // }
                // if($request->include_customer_id ==''){
                //     $include_customer_id = '';
                //     $collection = $collection->merge(compact('include_customer_id'));
                // }
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
            if (permission('user-group-view')) {
                $user_group = $this->model->findOrFail($request->id);

                return view('usergroup::details',compact('user_group'))->render();
            }
        }
    }

    public function edit(Request $request)
    {
        if($request->ajax()){
            if(permission('user-group-edit')){
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
            if(permission('user-group-delete')){
                $user_group = $this->model->find($request->id);
                $result = $user_group->delete();
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
            if(permission('user-group-bulk-delete')){
                $user_groups = $this->model->toBase()->whereIn('id',$request->ids)->get();
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
            if (permission('user-group-edit')) {
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
