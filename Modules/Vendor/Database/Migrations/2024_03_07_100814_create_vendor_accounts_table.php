<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id')->nullable();
         
            $table->string('vendor_account_type')->nullable();
            $table->double('vendor_amount')->nullable();
            $table->bigInteger('amount_percentage')->nullable();
            $table->double('vendor_use_amount')->nullable();
            $table->text('remark')->nullable();
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
        Schema::dropIfExists('vendor_accounts');
    }
}
