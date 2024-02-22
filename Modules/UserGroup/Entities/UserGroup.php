<?php

namespace Modules\UserGroup\Entities;

use Modules\Base\Entities\BaseModel;
use Modules\Customers\Entities\Customers;
use Modules\Inventory\Entities\Inventory;
use Modules\PackType\Entities\PackType;
use Modules\ProductImage\Entities\ProductImage;
use Modules\Segment\Entities\Segment;
use Modules\SubCategory\Entities\SubCategory;
use Modules\Variant\Entities\Variant;
use Modules\VariantOption\Entities\VariantOption;


class UserGroup extends BaseModel
{

    protected $table='coupon_user_groups';

    protected $casts = [

    'customer_id' => 'json',

];
    protected $fillable = ['group_name', 'group_description', 'customer_id', 'status', 'created_by', 'updated_by'];

    // public function customers()
    // {
    //     return $this->belongsToMany(Customers::class, 'coupon_user_groups', 'customer_id', 'id');
    // }

  


    public function setName($name)
    {
        $this->name = $name;
    }
    // public function setSubCategory($sub_category_id)
    // {
    //     $this->sub_category_id = $sub_category_id;
    // }
    // public function setCategory($category_id)
    // {
    //     $this->category_id = $category_id;
    // }

     public function getCustomerNamesAttribute()
    {
        // Decode the JSON-encoded customer_id to get an array of IDs
        $customerIds = $this->customer_id;
        
        if (is_array($customerIds)) {
            // Fetch the customer names based on the decoded IDs
            $customers = Customers::whereIn('id', $customerIds)->pluck('name');

            // dd($customers);
            
            // Return the names as a comma-separated string
            return implode(', ', $customers->toArray());
        }

        return ''; // Return an empty string if customer_id is not a valid JSON array
    }

    public function get_datatable_query()
    {
        if(permission('product-bulk-delete')){
            $this->column_order = [null,'id','group_name', 'group_description', 'customer_id','status',null];
        } else {
            $this->column_order = ['id','group_name', 'group_description', 'customer_id','status',null];
        }

        $query = self::query(); // Start an Eloquent query

        if (!empty($this->name)) {
            $query->where('group_name', 'like', '%' . $this->name . '%');
        }

        // Ordering logic
        if (isset($this->orderValue) && isset($this->dirValue)) {
            $query->orderBy($this->column_order[$this->orderValue], $this->dirValue);
        } else if (isset($this->order)) {
            $query->orderBy(key($this->order), $this->order[key($this->order)]);
        }

        return $query; // At this point, it's still a query builder instance
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
