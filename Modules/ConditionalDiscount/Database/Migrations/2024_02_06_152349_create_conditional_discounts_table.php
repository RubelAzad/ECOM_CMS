<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConditionalDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conditional_discounts', function (Blueprint $table) {
            $table->id();
            $table->string('condition_name');
            $table->string('condition_type');
           
            // $table->text('condition_description')->nullable();
            // $table->string('coupon_discount_type');
            $table->string('discount_amount')->nullable();
            $table->date('condition_exp_date');
            // $table->enum('is_free_delivery',['1','2'])->default('2')->nullable()->comment="1=Active,2=Inactive";
            $table->string('min_spend')->nullable();
            $table->string('max_spend')->nullable();
            $table->json('district_id')->nullable();
            $table->json('upazila_id')->nullable();
            $table->json('customer_group')->nullable();

            $table->enum('is_exclude_sale',['1','2'])->default('2')->nullable()->comment="1=Active,2=Inactive";

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
        Schema::dropIfExists('conditional_discounts');
    }
}
