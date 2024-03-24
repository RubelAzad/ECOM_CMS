<?php

namespace Modules\Vendor\Http\Controllers;
use Modules\Base\Http\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Vendor\Entities\VendorAccount;

class VendorAccountsController extends BaseController
{

    public function __construct(VendorAccount $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if(permission('vendor-access')){
            $this->setPageData('Vendors Amount','Vendors Amount','fas fa-th-list');
            return view('vendor::vendor-amount');
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

                    $row = [];


                    $row[] = $value->id;
                    $row[] = $value->vendor->name;
                    $row[] = $value->vendor_amount;
                    $row[] = $value->amount_percentage;
                    $row[] = $value->vendor_use_amount;
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
}
