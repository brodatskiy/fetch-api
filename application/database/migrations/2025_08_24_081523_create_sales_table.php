<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->decimal('g_number', 20, 0)->nullable();
            $table->date('date')->nullable();
            $table->date('last_change_date')->nullable();
            $table->char('supplier_article', 16)->nullable();
            $table->string('tech_size')->nullable();
            $table->integer('barcode')->nullable();
            $table->float('total_price')->nullable();
            $table->unsignedTinyInteger('discount_percent')->nullable();
            $table->boolean('is_supply')->nullable();
            $table->boolean('is_realization')->nullable();
            $table->string('promo_code_discount')->nullable();
            $table->string('warehouse_name')->nullable();
            $table->string('country_name')->nullable();
            $table->string('oblast_okrug_name')->nullable();
            $table->string('region_name')->nullable();
            $table->unsignedInteger('income_id')->nullable();
            $table->string('sale_id')->nullable();
            $table->string('odid')->nullable();
            $table->unsignedTinyInteger('spp')->nullable();
            $table->float('for_pay')->nullable();
            $table->float('finished_price')->nullable();
            $table->float('price_with_disc')->nullable();
            $table->integer('nm_id')->nullable();
            $table->string('subject')->nullable();
            $table->string('category')->nullable();
            $table->string('brand')->nullable();
            $table->boolean('is_storno')->nullable();
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
        Schema::dropIfExists('sales');
    }
}
