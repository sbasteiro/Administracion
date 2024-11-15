<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingAndZoneTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zone', function (Blueprint $table) {
            $table->id();
            $table->string('id_zone');
            $table->string('name');
            $table->longText('points');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });

        Schema::create('shipping', function (Blueprint $table) {
            $table->id();
            $table->integer('id_shipping');
            $table->string('buyer_name');
            $table->string('description');
            $table->string('photo_url');
            $table->string('address');
            $table->bigInteger('zone_id')->unsigned()->index()->nullable();
            $table->foreign('zone_id')->references('id')->on('zone');
            $table->decimal('longitude', 10, 8);
            $table->decimal('latitude', 10, 8);
            $table->enum('status', ['pending', 'delivered', 'deleted'])->default('pending');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping');
        Schema::dropIfExists('zone');
    }
}
