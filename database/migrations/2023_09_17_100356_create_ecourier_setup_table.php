<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcourierSetupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ecourier_setup', function (Blueprint $table) {
            $table->id();
            $table->string('ep_name');
            $table->string('pick_contact_person');
            $table->integer('pick_district')->nullable();//id
            $table->integer('pick_thana')->nullable();//id
            $table->integer('pick_hub'); //id
            $table->string('pick_union')->nullable();//keep union id
            $table->text('pick_address');//keep union id
            $table->string('pick_mobile');
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
        Schema::dropIfExists('ecourier_setup');
    }
}
