<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->string('name_of_firm');
            $table->string('type_of_firm');
            $table->string('type_of_firm_others')->nullable();
            $table->string('status_of_company');
            $table->string('type_of_service_provide')->nullable();
            $table->string('manpower_provided')->nullable();
            $table->string('address');
            $table->bigInteger('country_id');
            $table->bigInteger('state_id');
            $table->bigInteger('city_id');
            $table->string('pin');
            $table->string('std_code_with_phn_no');
            $table->string('email');
            $table->string('website')->nullable();
            $table->bigInteger('mobile');
            $table->string('name_of_contact_person');
            $table->string('designation_of_contact_person');
            $table->string('description_of_company');
            $table->bigInteger('category_id');
            $table->string('items_for_service');
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
        Schema::dropIfExists('vendors');
    }
}
