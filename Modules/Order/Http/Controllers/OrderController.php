<?php

namespace Modules\Order\Http\Controllers;
use App\Mail\OrderStatusChanged;
use App\Models\EcourierSetup;
use App\Models\Setting;
use App\Traits\UploadAble;
use Codeboxr\EcourierCourier\Facade\Ecourier;
use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\BaseController;
use Modules\Combo\Entities\Combo;
use Modules\Inventory\Entities\Inventory;
use Modules\Location\Entities\District;
use Modules\Order\Entities\Order;
use Modules\Customers\Entities\Customers;
use Modules\Order\Entities\OrderItem;
use Modules\Order\Entities\Upazila;
use Modules\Order\Http\Requests\eCourierOrderRequest;
use Modules\Order\Http\Requests\OrderFormRequest;
use Illuminate\Support\Facades\Mail;
use Modules\PaymentMethod\Entities\PaymentMethod;
use Modules\Product\Entities\Product;
use DB;
use App\Services\PdfService;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;
use Mpdf\MpdfException;
use App\Helpers\custom;

class OrderController extends BaseController
{
    use UploadAble;

    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (permission('order-access')) {
            $this->setPageData('Order', 'Order', 'fas fa-box');
            $data['payment_methods'] = PaymentMethod::get();
            $data['ecourier_data'] = EcourierSetup::get();
            $data['branches'] = Ecourier::area()->branch();
            $data['packages'] = Ecourier::order()->packageList();
            $data['districts'] = District::get();
            return view('order::index',$data);
        } else {
            return $this->unauthorized_access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if (permission('order-access')) {
            if ($request->ajax()) {
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

                    if (permission('order-view')) {
                        $action .= ' <a class="dropdown-item view_data" data-id="' . $value->id . '"><i class="fas fa-eye text-primary"></i> View</a>';
                    }if (permission('order-edit')) {
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if (permission('order-delete')) {
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->id . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }

                    $row = [];

                    if (permission('order-bulk-delete')) {
                        $row[] = table_checkbox($value->id);
                    }
                    $options = '<select name="order_status_id" id="order_status_id" class="form-control order_status_id" onchange="getOrderStatus(this.value, '.$value->id.')">
                        <option value="">Select Please</option>
                        <option '. ($value->order_status_id == 1 ? 'selected' : '') .' value="1">PENDING</option>
                        <option '. ($value->order_status_id == 2 ? 'selected' : '') .' value="2">PROCESSING</option>
                        <option '. ($value->order_status_id == 3 ? 'selected' : '') .' value="3">SHIPPED</option>
                        <option '. ($value->order_status_id == 4 ? 'selected' : '') .' value="4">DELIVERED</option>
                        <option '. ($value->order_status_id == 5 ? 'selected' : '') .' value="5">CANCELED</option>
                    </select>';

                    $row[] = $no;
                    $row[] = $value->order_date;
                    $row[] = $value->id;
                    $row[] = $value->grand_total;
                    $row[] = $options;
                    $row[] = permission('order-edit') ? change_payment_status($value->id,$value->payment_status_id,$value->payment_status_id) : PAYMENT_STATUS_LABEL[$value->payment_status_id];;
                    $row[] = "<button class='btn btn-light' onclick='ecourierModal($value->id)'><i class='fas fa-truck text-primary'></i> eCourier</button>";

                    $row[] = action_button($action);
                    $data[] = $row;
                }
                return $this->datatable_draw($request->input('draw'), $this->model->count_all(),
                    $this->model->count_filtered(), $data);
            } else {
                $output = $this->access_blocked();
            }

            return response()->json($output);
        }
    }

    public function store_or_update_data(OrderFormRequest $request)
    {
        if ($request->ajax()) {
            if (permission('order-add') || permission('order-edit')) {
                $collection = collect($request->validated());
                $collection = $this->track_data($request->update_id, $collection);

                //order quantity update product_id
                if(isset($request->product_id)){
                    for($i=0;$i<count($request->product_id);$i++){
                        if(isset($request->update_id,$request->type[$i],$request->product_id[$i],$request->quantity[$i]) && $request->type[$i]=='product'){
                            OrderItem::where('order_id',$request->update_id)->where('inventory_id',$request->product_id[$i])->update(['quantity'=>$request->quantity[$i]]);
                        }
                        else if(isset($request->update_id,$request->type[$i],$request->product_id[$i],$request->quantity[$i]) && $request->type[$i]=='combo'){
                            OrderItem::where('order_id',$request->update_id)->where('combo_id',$request->product_id[$i])->update(['quantity'=>$request->quantity[$i]]);
                        }
                    }
                }
                $result = $this->model->updateOrCreate(['id' => $request->update_id], $collection->all());
                $output = $this->store_message($result, $request->update_id);
            } else {
                $output = $this->access_blocked();
            }
            return response()->json($output);
        } else {
            return response()->json($this->access_blocked());
        }
    }

    public function ecourier_store_or_update_data(eCourierOrderRequest $request)
    {
        if ($request->ajax()) {
            if (permission('ecourier-add') || permission('ecourier-edit')) {
                $collection = collect($request->validated());
                $collection = $this->track_data_ecourier($request->update_id, $collection);
                $result = Ecourier::order()->create($collection->toArray());
                $content = $result->getContent();
                $dataID = json_decode($content);
                if ($dataID && isset($dataID->ID)) {
                    $id = $dataID->ID;
                } else {
                    $id ='N/A';
                }
                if ($content) {
                    $result_json = $content;
                } else {
                    $result_json ='N/A';
                }
                //save ecourier order tracking_id
                Order::where('id', $request->order_id)->update(['ecourier_tracking' => $id,'ecourier_details_json'=>$collection]);

                $output = $this->store_message($result, $request->update_id);
            } else {
                $output = $this->access_blocked();
            }
            return response()->json($output);
        } else {
            return response()->json($this->access_blocked());
        }
    }

    public function view(Request $request)
    {
        if ($request->ajax()) {
            if (permission('order-view')) {
                $data = $this->model->findOrFail($request->id);
                $data->load('orderItems','customer');
                $data['inventories'] = Inventory::get();
                $data['combos'] = Combo::get();
                $data['logo'] = Setting::all();
                $output = $this->data_message($data);
            } else {
                $output = $this->access_blocked();
            }
            return response()->json($output);
        } else {
            return response()->json($this->access_blocked());
        }
    }

    public function invoice_print_pdf($id =null){
        $data['order'] = Order::with('orderItems', 'customer')->find(8);
        $data['setting'] = Setting::all();
       return view('order::invoice_print', $data);
//        $pdf = PDF::loadView('order::invoice_print', $data);
//        return $pdf->stream();
    }

    public function edit(Request $request)
    {
        if ($request->ajax()) {
            if (permission('order-edit')) {
                $data = $this->model->findOrFail($request->id);
                $data->load('orderItems');
                $data['inventories'] = Inventory::get();
                $data['combos'] = Combo::get();
                $output = $this->data_message($data);
            } else {
                $output = $this->access_blocked();
            }
            return response()->json($output);
        } else {
            return response()->json($this->access_blocked());
        }
    }
    public function get_price(Request $request)
    {
        if ($request->ajax()) {
            if (permission('order-edit')) {
                $type = $request->type;
                if($type=='combo'){
                    $products = Combo::findOrFail($request->id);
                }else{
                    $products = Inventory::findOrFail($request->id);
                }
               return $products;
            } else {
                $output = $this->access_blocked();
            }
            return response()->json($output);
        } else {
            return response()->json($this->access_blocked());
        }
    }

    public function delete(Request $request)
    {
        if ($request->ajax()) {
            if (permission('order-delete')) {
                $scategory = $this->model->find($request->id);
                $image = $scategory->image;
                $result = $scategory->delete();
                if ($result) {
                    if (!empty($image)) {
                        $this->delete_file($image, SUB_CATEGORY_IMAGE_PATH);
                    }
                }
                $output = $this->delete_message($result);
            } else {
                $output = $this->access_blocked();
            }
            return response()->json($output);
        } else {
            return response()->json($this->access_blocked());
        }
    }

    public function bulk_delete(Request $request)
    {
        if ($request->ajax()) {
            if (permission('order-bulk-delete')) {
                $scategorys = $this->model->toBase()->select('image')->whereIn('id', $request->ids)->get();
                $result = $this->model->destroy($request->ids);
                if ($result) {
                    if (!empty($scategorys)) {
                        foreach ($scategorys as $scategory) {
                            if ($scategory->image) {
                                $this->delete_file($scategory->image, SUB_CATEGORY_IMAGE_PATH);
                            }
                        }
                    }
                }
                $output = $this->bulk_delete_message($result);
            } else {
                $output = $this->access_blocked();
            }
            return response()->json($output);
        } else {
            return response()->json($this->access_blocked());
        }
    }

    public function change_payment_status(Request $request)
    {
        if ($request->ajax()) {
            if (permission('order-edit')) {
                $result = $this->model->find($request->id)->update(['payment_status_id' => $request->status]);
                $order=$this->model->find($request->id);

                $customer=Customers::where('id','=',$order->customer_id)->first();
                // Code to update the order status...

                // Get the email address of the customer (you'll need to customize this according to your database structure)
                $customerName = $customer->name;
                $customerEmail = $customer->email ?? '';
                $customerPhone = $customer->phone_number ?? '';

                $apiEndpoint = env('BANGLALINK_SMS_API_ENDPOINT');
                $username = env('BANGLALINK_SMS_USERNAME');
                $password = env('BANGLALINK_SMS_PASSWORD');
                $apiCode = env('BANGLALINK_SMS_APICODE');
                $cli = env('BANGLALINK_SMS_CLI');


                if($order->payment_status_id == 1){
                    $payment = 'Paid';
                }else{
                    $payment = 'Unpaid';
                }

                if($order->order_status_id == 1){
                    //$order_status = 'PENDING';
                    $order_status = [
                        'status' => 'PENDING',
                        'order_id' => $order->id,
                        'customer_name' => $customerName,
                        'sub_total' => $order->sub_total,
                        'shipping_charge' => $order->shipping_charge,
                        'grand_total' => $order->grand_total,
                        'orderItems' => $order->orderItems,
                        'payment_status' => $payment,

                    ];
                }
                if($order->order_status_id == 2){
                    $order_status = [
                        'status' => 'PROCESSING',
                        'order_id' => $order->id,
                        'customer_name' => $customerName,
                        'sub_total' => $order->sub_total,
                        'shipping_charge' => $order->shipping_charge,
                        'grand_total' => $order->grand_total,
                        'orderItems' => $order->orderItems,
                        'payment_status' => $payment,
                    ];
                }
                if($order->order_status_id == 3){
                    $order_status = [
                        'status' => 'SHIPPED',
                        'order_id' => $order->id,
                        'customer_name' => $customerName,
                        'sub_total' => $order->sub_total,
                        'shipping_charge' => $order->shipping_charge,
                        'grand_total' => $order->grand_total,
                        'orderItems' => $order->orderItems,
                        'payment_status' => $payment,
                    ];
                }
                if($order->order_status_id == 4){
                    $order_status = [
                        'status' => 'DELIVERED',
                        'order_id' => $order->id,
                        'customer_name' => $customerName,
                        'sub_total' => $order->sub_total,
                        'shipping_charge' => $order->shipping_charge,
                        'grand_total' => $order->grand_total,
                        'orderItems' => $order->orderItems,
                        'payment_status' => $payment,
                    ];
                }
                if($order->order_status_id == 5){
                    $order_status = [
                        'status' => 'CANCELED',
                        'order_id' => $order->id,
                        'customer_name' => $customerName,
                        'sub_total' => $order->sub_total,
                        'shipping_charge' => $order->shipping_charge,
                        'grand_total' => $order->grand_total,
                        'orderItems' => $order->orderItems,
                        'payment_status' => $payment,
                    ];
                }

               
               //Send the email
                if($customerEmail){
                    Mail::to($customerEmail)->send(new OrderStatusChanged($order_status));
                }


                 if($customerPhone){

                    $requestData = [
                        'username' => $username,
                        'password' => $password,
                        'apicode' => $apiCode,
                        'msisdn' => [$customerPhone],
                        'countrycode' => '880',
                        'cli' => $cli,
                        'messagetype' => '1',
                        'message' => 'Order ID: ' . $order_status['order_id'] . 
                        ', Order Status: ' . $order_status['status'] .
                        ', Customer Name: ' . $order_status['customer_name'] .
                        ', Grand Total: ' . $order_status['grand_total'] .
                        ', Payment Status: ' . $order_status['payment_status'],
                        'clienttransid' => generateRandomClientTransId(),
                        'bill_msisdn' => $cli,
                        'tran_type' => 'P',
                        'request_type' => 'B',
                        'rn_code' => '91',
                    ];

                $httpResponse = makeHttpRequest('POST', $apiEndpoint, $requestData);
            
        }
                $output = $result ? ['status' => 'success', 'message' => 'Status has been changed successfully']
                    : ['status' => 'error', 'message' => 'Failed to change status'];
            } else {
                $output = $this->access_blocked();
            }
            return response()->json($output);
        } else {
            return response()->json($this->access_blocked());
        }
    }

    public function change_order_status(Request $request){
        if ($request->ajax()) {
            if (permission('order-edit')) {
                $result = $this->model->find($request->id)->update(['order_status_id' => $request->order_id]);

                $order=$this->model->find($request->id);

                $customer=Customers::where('id','=',$order->customer_id)->first();
                // Code to update the order status...

                // Get the email address of the customer (you'll need to customize this according to your database structure)
                $customerName = $customer->name;
                $customerEmail = $customer->email ?? '';
                $customerPhone = $customer->phone_number ?? '';

           

                $apiEndpoint = env('BANGLALINK_SMS_API_ENDPOINT');
                $username = env('BANGLALINK_SMS_USERNAME');
                $password = env('BANGLALINK_SMS_PASSWORD');
                $apiCode = env('BANGLALINK_SMS_APICODE');
                $cli = env('BANGLALINK_SMS_CLI');




            //request body

         

                if($order->payment_status_id == 1){
                    $payment = 'Paid';
                }else{
                    $payment = 'Unpaid';
                }

                if($order->order_status_id == 1){
                    //$order_status = 'PENDING';
                    $order_status = [
                        'status' => 'PENDING',
                        'order_id' => $order->id,
                        'customer_name' => $customerName,
                        'sub_total' => $order->sub_total,
                        'shipping_charge' => $order->shipping_charge,
                        'grand_total' => $order->grand_total,
                        'orderItems' => $order->orderItems,
                        'payment_status' => $payment,

                    ];
                }
                if($order->order_status_id == 2){
                    $order_status = [
                        'status' => 'PROCESSING',
                        'order_id' => $order->id,
                        'customer_name' => $customerName,
                        'sub_total' => $order->sub_total,
                        'shipping_charge' => $order->shipping_charge,
                        'grand_total' => $order->grand_total,
                        'orderItems' => $order->orderItems,
                        'payment_status' => $payment,
                    ];
                }
                if($order->order_status_id == 3){
                    $order_status = [
                        'status' => 'SHIPPED',
                        'order_id' => $order->id,
                        'customer_name' => $customerName,
                        'sub_total' => $order->sub_total,
                        'shipping_charge' => $order->shipping_charge,
                        'grand_total' => $order->grand_total,
                        'orderItems' => $order->orderItems,
                        'payment_status' => $payment,
                    ];
                }
                if($order->order_status_id == 4){
                    $order_status = [
                        'status' => 'DELIVERED',
                        'order_id' => $order->id,
                        'customer_name' => $customerName,
                        'sub_total' => $order->sub_total,
                        'shipping_charge' => $order->shipping_charge,
                        'grand_total' => $order->grand_total,
                        'orderItems' => $order->orderItems,
                        'payment_status' => $payment,
                    ];
                }
                if($order->order_status_id == 5){
                    $order_status = [
                        'status' => 'CANCELED',
                        'order_id' => $order->id,
                        'customer_name' => $customerName,
                        'sub_total' => $order->sub_total,
                        'shipping_charge' => $order->shipping_charge,
                        'grand_total' => $order->grand_total,
                        'orderItems' => $order->orderItems,
                        'payment_status' => $payment,
                    ];
                }

                // Send the email
                if($customerEmail){
                    Mail::to($customerEmail)->send(new OrderStatusChanged($order_status));
                }

                if($customerPhone){

                    $requestData = [
                        'username' => $username,
                        'password' => $password,
                        'apicode' => $apiCode,
                        'msisdn' => [$customerPhone],
                        'countrycode' => '880',
                        'cli' => $cli,
                        'messagetype' => '1',
                        'message' => 'Order ID: ' . $order_status['order_id'] . 
                        ', Order Status: ' . $order_status['status'] .
                        ', Customer Name: ' . $order_status['customer_name'] .
                        ', Grand Total: ' . $order_status['grand_total'] .
                        ', Payment Status: ' . $order_status['payment_status'],
                        'clienttransid' => generateRandomClientTransId(),
                        'bill_msisdn' => $cli,
                        'tran_type' => 'P',
                        'request_type' => 'B',
                        'rn_code' => '91',
                    ];

                $httpResponse = makeHttpRequest('POST', $apiEndpoint, $requestData);
            
        }

           

                // Return response or redirect...
                $output = $result ? ['status' => 'success', 'message' => 'Status has been changed successfully']
                    : ['status' => 'error', 'message' => 'Failed to change status'];
                return response()->json($output);
            }else{
                return response()->json($this->access_blocked());
            }
        }
    }

    public function get_all_district(Request $request){
        try{
            $data['orders'] = Order::find($request->id);

            if (isset($data['orders']->shipping_address_json) && $data['orders']->shipping_address_json !== '') {
                $decodedData = $data['orders']->shipping_address_json;

                if (isset($decodedData->district->name)) {
                    $data['districtCityName'] = $decodedData->district->name;
                } else {
                    $data['districtCityName'] = ''; // Handle the case when district->name is not present
                }

                $districts_id = $decodedData->district->id??0;
                $data['thana_upozillas'] = Upazila::where('district_id',$districts_id)->get();
                return response()->json(['data'=>$data]);
            }


            else{
                $data['districtCityName'] = '';
                $data['address'] = [];
                $data['thana_upozillas'] = [];
            }
            $data['districts'] = District::get();
            return response()->json(['data'=>$data,'status'=>'success']);
        }catch(\Exception $e){
            return response()->json(['status'=>'error','message'=>$e->getMessage()]);
        }
    }

    public function get_upozilaor_thana(Request $request){
        try{
            //districtid
            $districts_id = $request->id;
            $data['thana_upozillas'] = Upazila::where('district_id',$districts_id)->get();
            return response()->json(['data'=>$data,'status'=>'success']);
        }catch(\Exception $e){
            return response()->json(['status'=>'error','message'=>$e->getMessage()]);
        }
    }

    public function get_ecommerce_pick_info(Request $request){
        try{
            $courier_id = $request->courier_id;
            $result = EcourierSetup::with('getDistrict','getUpazilaThana')->where('id', $courier_id)->firstOrFail();
            $branches = Ecourier::area()->branch();
            return response()->json(['courier_info'=>$result,'branches'=>$branches,'status'=>'success']);
        }catch(\Exception $e){
            return response()->json(['status'=>'error','message'=>$e->getMessage()]);
        }
    }

    public function get_order_info(Request $request){
        $order_id = $request->id;
        $order_info = Order::with('customer','orderItems')->find($order_id);

        return $order_info;
    }
}

