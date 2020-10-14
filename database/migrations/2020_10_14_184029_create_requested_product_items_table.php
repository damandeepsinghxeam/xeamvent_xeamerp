<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestedProductItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requested_product_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->string('product_name');
            $table->string('others_product_name')->nullable();
            $table->string('no_of_items_requested');
            $table->string('product_description');
            $table->bigInteger('supervisor_id');
            $table->enum('product_requested_status', ['0','1','2','3'])->comment('0=inprogress, 1=approved, 2=rejected, 3=sendback');
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
        Schema::dropIfExists('requested_product_items');
    }
}
