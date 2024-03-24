<?php

namespace Modules\Vendor\Entities;

use Modules\Base\Entities\BaseModel;
use Modules\Review\Entities\Review;
use Illuminate\Database\Eloquent\Builder;

class VendorAccount extends BaseModel
{
    protected $table ='vendor_accounts';
    protected $fillable = ['id','vendor_id','vendor_account_type','vendor_amount','amount_percentage','vendor_use_amount','remark','status','created_at','updated_at'];

    // public function reviews()
    // {
    //     return $this->hasMany(Review::class, 'customer_id', 'id');
    // }

    protected $name;

    public function setName($name)
    {
        $this->name = $name;
    }

      public function vendor()
    {
        return $this->belongsTo(Vendors::class, 'vendor_id', 'id');
    }
    private function get_datatable_query()
    {
        if(permission('ctype-bulk-delete')){
            $this->column_order = [null,'id','vendor_id','vendor_account_type',null];
        }else{
            $this->column_order = ['id','vendor_id','vendor_account_type',null];
        }

        $query = self::with('vendor');

        /*****************
         * *Search Data **
         ******************/
        if (!empty($this->name)) {
            $query->where('name', 'like', '%' . $this->name . '%');
        }

         // Add condition to filter by customer_type = 1
         $query->whereHas('vendor', function (Builder $query) {
            $query->where('customer_type', 1);
        });


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
