<?php

namespace Modules\Vendor\Entities;

use Modules\Base\Entities\BaseModel;
use Modules\Review\Entities\Review;

class Vendors extends BaseModel
{
    protected $table ='customers';
    protected $fillable = ['name','email','address','phone_number','gender','date_of_birth','password','image','customer_type','status','created_at','updated_at'];

    // public function reviews()
    // {
    //     return $this->hasMany(Review::class, 'customer_id', 'id');
    // }

    protected $name;

    public function setName($name)
    {
        $this->name = $name;
    }

    private function get_datatable_query()
    {
        if(permission('ctype-bulk-delete')){
            $this->column_order = [null,'id','name','address',null];
        }else{
            $this->column_order = ['id','name','address',null];
        }

        $query = self::toBase();

        /*****************
         * *Search Data **
         ******************/
        if (!empty($this->name)) {
            $query->where('name', 'like', '%' . $this->name . '%');
        }

        $query->where('customer_type', '1');

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
}
