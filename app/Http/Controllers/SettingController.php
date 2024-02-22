<?php

namespace App\Http\Controllers;

use App\Http\Requests\EcourierFormRequest;
use App\Http\Requests\GeneralSettingFormRequest;
use App\Http\Requests\MailSettingFormRequest;
use App\Models\Setting;
use App\Traits\UploadAble;
//use Beta\Microsoft\Graph\Model\Request;
use Codeboxr\EcourierCourier\Facade\Ecourier;
use Modules\Location\Entities\District;
use App\Models\EcourierSetup;
use Modules\Order\Entities\Upazila;
use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\BaseController;
use Modules\PaymentMethod\Entities\PaymentMethod;

class SettingController extends BaseController
{
    use UploadAble;

    public function __construct(EcourierSetup $model)
    {
        $this->model = $model;
    }
    public function index(){

        if (permission('setting-access')) {
            $this->setPageData('Setting','Setting','fas fa-cogs');
            $data['districts'] = District::get();
            $data['branches'] = Ecourier::area()->branch();
            $data['payment_methods'] = PaymentMethod::get();
            return view('setting.index',$data);
        } else {
            return $this->unauthorized_access_blocked();
        }

    }
    public function get_datatable_data(Request $request)
    {
        if(permission('ecourier-access')){
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

                    if(permission('ecourier-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('ecourier-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->ep_name . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }


                    $row = [];

                    if(permission('ecourier-bulk-delete')){
                        $row[] = table_checkbox($value->id);
                    }
                    $row[] = $no;
                    $row[] = $value->ep_name;
                    $row[] = $value->pick_contact_person;
                    $row[] = $value->pick_mobile;
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

    public function store_or_update_data(EcourierFormRequest $request)
    {
        if($request->ajax()){
            if(permission('ecourier-add') || permission('ecourier-edit')){
                $collection = collect($request->validated());
                $collection = $this->track_data($request->update_id,$collection);
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

    public function edit(Request $request)
    {
        if($request->ajax()){
            if(permission('ecourier-edit')){
                $data = $this->model->findOrFail($request->id);
                $data->load('getUpazila');
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
            if(permission('ecourier-delete')){
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
            if(permission('ecourier-bulk-delete')){
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
            if (permission('ecourier-edit')) {
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

//   ecourier end

    public function general_seting(GeneralSettingFormRequest $request){
        if($request->ajax())
        {
            try {
                $collection = collect($request->validated())->except(['logo','favicon','footerlogo']);
                foreach ($collection->all() as $key => $value) {
                    Setting::set($key,$value);
                }

                if($request->hasFile('logo')){
                    $logo = $this->upload_file($request->file('logo'),LOGO_PATH);
                    if(!empty($request->old_logo)){
                        $this->delete_file($request->old_logo,LOGO_PATH);
                    }
                    Setting::set('logo',$logo);
                }
                if($request->hasFile('favicon')){
                    $favicon = $this->upload_file($request->file('favicon'),LOGO_PATH);
                    if(!empty($request->old_favicon)){
                        $this->delete_file($request->old_favicon,LOGO_PATH);
                    }
                    Setting::set('favicon',$favicon);
                }
                if($request->hasFile('footerlogo')){
                    $footerlogo = $this->upload_file($request->file('footerlogo'),LOGO_PATH);
                    if(!empty($request->old_footer)){
                        $this->delete_file($request->old_footer,LOGO_PATH);
                    }
                    Setting::set('footerlogo',$footerlogo);
                }

                $output = ['status'=>'success','message'=>'Data Has Been Saved Successfully'];
                return response()->json($output);
            } catch (\Exception $e) {
                $output = ['status'=>'error','message'=> $e->getMessage()];
                return response()->json($output);
            }

        }

    }

    public function mail_setting(MailSettingFormRequest $request){
        if($request->ajax())
        {
            try {
                $collection = collect($request->validated());
                foreach ($collection->all() as $key => $value) {
                    Setting::set($key,$value);
                }

                $this->changeEnvData([
                    'MAIL_MAILER'     => $request->mail_mailer,
                    'MAIL_HOST'       => $request->mail_host,
                    'MAIL_PORT'       => $request->mail_port,
                    'MAIL_USERNAME'   => $request->mail_username,
                    'MAIL_PASSWORD'   => $request->mail_password,
                    'MAIL_ENCRYPTION' => $request->mail_encryption,
                    'MAIL_FROM_NAME'  => $request->mail_from_name
                ]);
                $output = ['status'=>'success','message'=>'Data Has Been Saved Successfully'];
                return response()->json($output);
            } catch (\Exception $e) {
                $output = ['status'=>'error','message'=> $e->getMessage()];
                return response()->json($output);
            }

        }
    }

    protected function changeEnvData(array $data)
    {
        if(count($data) > 0){
            $env = file_get_contents(base_path().'/.env');
            $env = preg_split('/\s+/',$env);

            foreach ($data as $key => $value) {
                foreach ($env as $env_key => $env_value) {
                    $entry = explode("=",$env_value,2);
                    if($entry[0] == $key){
                        $env[$env_key] = $key."=".$value;
                    }else{
                        $env[$env_key] = $env_value;
                    }
                }
            }
            $env = implode("\n",$env);

            file_put_contents(base_path().'/.env',$env);
            return true;
        }else {
            return false;
        }
    }
    public function get_upazillas(Request $request){
        $dc_id=(int)$request->dcId;
        $upazillas=Upazila::where('district_id', $dc_id)->get();
        return response()->json($upazillas);
    }
}
