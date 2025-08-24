<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->date('last_change_date')->nullable();
            $table->char('supplier_article', 16)->nullable();
            $table->string('tech_size')->nullable();
            $table->integer('barcode')->nullable();
            $table->unsignedInteger('quantity')->nullable();
            $table->boolean('is_supply')->nullable();
            $table->boolean('is_realization')->nullable();
            $table->unsignedInteger('quantity_full')->nullable();
            $table->string('warehouse_name')->nullable();
            $table->unsignedInteger('in_way_to_client')->nullable();
            $table->unsignedInteger('in_way_from_client')->nullable();
            $table->integer('nm_id')->nullable();
            $table->string('subject')->nullable();
            $table->string('category')->nullable();
            $table->string('brand')->nullable();
            $table->unsignedInteger('sc_code')->nullable();
            $table->unsignedInteger('price')->nullable();
            $table->unsignedTinyInteger('discount')->nullable();
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
        Schema::dropIfExists('stocks');
    }
}
