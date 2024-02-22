<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon', function (Blueprint $table) {
            $table->id();
            $table->string('coupon_code')->unique();
            $table->text('coupon_description')->nullable();
            $table->string('coupon_discount_type');
            $table->string('coupon_amount');
            $table->date('coupon_exp_date');
            $table->enum('is_free_delivery',['1','2'])->default('2')->nullable()->comment="1=Active,2=Inactive";
            $table->string('coupon_min_spend')->nullable();
            $table->string('coupon_max_spend')->nullable();
            $table->json('product_id')->nullable();
            $table->json('exclude_id')->nullable();
            $table->json('category_id')->nullable();
            $table->json('exclude_category_id')->nullable();
            $table->json('customer_id')->nullable();
            $table->json('include_customer_id')->nullable();
            $table->enum('is_individual',['1','2'])->default('2')->nullable()->comment="1=Active,2=Inactive";
            $table->enum('is_exclude_sale',['1','2'])->default('2')->nullable()->comment="1=Active,2=Inactive";
            $table->integer('limit_per_coupon')->nullable();
            $table->integer('limit_usage_times')->nullable();
            $table->integer('limit_per_user')->nullable();
            $table->enum('status',['1','2'])->default('1')->comment="1=Active,2=Inactive";
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupon');
    }
}
