<?php

namespace App\Models;

use Modules\Base\Entities\BaseModel;
use Modules\Location\Entities\District;
use Modules\Order\Entities\Upazila;

class EcourierSetup extends BaseModel
{
    protected $table = 'ecourier_setup';
    protected $fillable = ['ep_name', 'pick_contact_person', 'pick_district', 'pick_thana','pick_hub','pick_union',
        'pick_address','pick_mobile','created_at','updated_at'];

    protected $name;

    public function setName($name)
    {
        $this->name = $name;
    }

    private function get_datatable_query()
    {
        if(permission('category-bulk-delete')){
            $this->column_order = [null,'id','ep_name', 'pick_contact_person', 'pick_district', 'pick_thana',null];
        }else{
            $this->column_order = ['id','ep_name', 'pick_contact_person', 'pick_district', 'pick_thana',null];
        }

        $query = self::toBase();

        /*****************
         * *Search Data **
         ******************/
        if (!empty($this->name)) {
            $query->where('ep_name', 'like', '%' . $this->name . '%');
        }

        if (isset($this->orderValue) && isset($this->dirValue)) {
            $query->orderBy($this->column_order[$this->orderValue], $this->dirValue);
        } else if (isset($this->order)) {
            $query->orderBy(key($this->order), $this->order[key($this->order)]);
        }
        return $query;
    }

    public function getDatatableList()
    {
        $query = $this->get_datatable_query();
        if ($this->lengthVlaue != -1) {
            $query->offset($this->startVlaue)->limit($this->lengthVlaue);
        }
        return $query->get();
    }

    public function count_filtered()
    {
        $query = $this->get_datatable_query();
        return $query->get()->count();
    }

    public function count_all()
    {
        return self::toBase()->get()->count();
    }
    public function getDistrict(){
        return $this->hasOne(District::class,'id','pick_district');
    }
    public function getUpazilaThana(){
        return $this->hasOne(Upazila::class,'id','pick_thana');
    }

    public function getUpazila(){
        return $this->hasMany(Upazila::class,'district_id','pick_district');
    }
}

