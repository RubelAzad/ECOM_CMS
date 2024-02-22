<?php

namespace Modules\Coupon\Entities;

use Modules\Base\Entities\BaseModel;
use Modules\Category\Entities\Category;
use Modules\Inventory\Entities\Inventory;
use Modules\PackType\Entities\PackType;
use Modules\ProductImage\Entities\ProductImage;
use Modules\Segment\Entities\Segment;
use Modules\SubCategory\Entities\SubCategory;
use Modules\Variant\Entities\Variant;
use Modules\VariantOption\Entities\VariantOption;


class Coupon extends BaseModel
{

    protected $table='coupon';
    protected $casts = [
    'product_id' => 'json',
    'exclude_id' => 'json',
    'category_id' => 'json',
    'exclude_category_id' => 'json',
    'customer_id' => 'json',
    'include_customer_id' => 'json',
    'combo_id' => 'json',
    'exclude_combo_id' => 'json',
];
    protected $fillable = ['coupon_code', 'coupon_description','coupon_discount_type','coupon_amount','coupon_exp_date', 'is_free_delivery', 'coupon_min_spend', 'coupon_max_spend', 'product_id', 'exclude_id','category_id', 'exclude_category_id', 'customer_id', 'include_customer_id','combo_id','exclude_combo_id',
     'is_individual','is_exclude_sale','limit_per_coupon', 'limit_usage_times', 'limit_per_user', 'status', 'created_by', 'updated_by'];

    // public function inventory(){
    //     return $this->hasMany(Inventory::class,'id','product_id');
    // }
    // public function category()
    // {
    //     return $this->belongsTo(Category::class,'category_id','id');
    // }
    // public function subCategory()
    // {
    //     return $this->belongsTo(SubCategory::class,'sub_category_id','id');
    // }
    // public function variant()
    // {
    //     return $this->belongsTo(Variant::class,'variant_id','id');
    // }
    // public function variantOption()
    // {
    //     return $this->belongsTo(VariantOption::class,'variant_option_id','id');
    // }
    // public function segment()
    // {
    //     return $this->belongsTo(Segment::class,'segment_id','id');
    // }
    // public function pack()
    // {
    //     return $this->belongsTo(PackType::class,'pack_id','id');
    // }
    // public function productImages()
    // {
    //     return $this->hasMany(ProductImage::class,'product_id','id');
    // }


    public function setName($name)
    {
        $this->name = $name;
    }
    public function setSubCategory($sub_category_id)
    {
        $this->sub_category_id = $sub_category_id;
    }
    public function setCategory($category_id)
    {
        $this->category_id = $category_id;
    }

    private function get_datatable_query()
    {
        if(permission('product-bulk-delete')){
            $this->column_order = [null,'id','coupon_code','coupon_discount_type','coupon_amount','coupon_exp_date','status',null];
     
        }else{
            $this->column_order = ['id','coupon_code','coupon_discount_type','coupon_amount','coupon_exp_date', 'status',null];
        }

    //      if(permission('product-bulk-delete')){
    //         $this->column_order = [null,'id','coupon_code', 'coupon_description','coupon_discount_type','coupon_amount','coupon_exp_date', 'is_free_delivery', 'coupon_min_spend', 'coupon_max_spend', 'product_id', 'exclude_id','category_id', 'exclude_category_id', 'customer_id', 'include_customer_id',
    //  'is_individual','is_exclude_sale','limit_per_coupon', 'limit_usage_times', 'limit_per_user', 'status',null];
     
    //     }else{
    //         $this->column_order = ['id','coupon_code', 'coupon_description','coupon_discount_type','coupon_amount','coupon_exp_date', 'is_free_delivery', 'coupon_min_spend', 'coupon_max_spend', 'product_id', 'exclude_id','category_id', 'exclude_category_id', 'customer_id', 'include_customer_id',
    //  'is_individual','is_exclude_sale','limit_per_coupon', 'limit_usage_times', 'limit_per_user', 'status',null];
    //     }

        $query = self::toBase();

        /*****************
         * *Search Data **
         ******************/
        if (!empty($this->name)) {
            $query->where('coupon_code', 'like', '%' . $this->name . '%');
        }
        // if (!empty($this->category_id)) {
        //     $query->where('category_id', $this->category_id);
        // }
        // if (!empty($this->sub_category_id)) {
        //     $query->where('sub_category_id', $this->sub_category_id);
        // }


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
