<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrderStockItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_stock_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('purchase_order_id');
            $table->foreign('purchase_order_id')->references('id')->on('purchase_order')->onDelete('cascade');
            $table->unsignedBigInteger('stock_item_id');
            $table->foreign('stock_item_id')->references('id')->on('stock_items')->onDelete('cascade');
            $table->bigInteger('quantity');
            $table->bigInteger('approx_price');
            $table->bigInteger('total_price');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_order_stock_items');
    }
}
