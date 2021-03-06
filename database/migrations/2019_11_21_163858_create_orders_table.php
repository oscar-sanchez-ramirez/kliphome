<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('selected_id')->unsigned();
            $table->string('type_service');
            $table->string('service_date');
            $table->longText('service_description');
            $table->longText('service_image');
            $table->text('address');
            $table->string('state')->default('PENDING');
            $table->integer('is_notified')->default(0);
            $table->string('finished_at')->nullable();
            $table->string('price')->nullable();
            $table->string('pre_coupon')->nullable();
            $table->string('visit_price')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
