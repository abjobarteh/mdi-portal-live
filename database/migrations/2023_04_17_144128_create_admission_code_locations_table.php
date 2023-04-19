<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admission_code_locations', function (Blueprint $table) {
            $table->id();
            $table->string('location_name');
            $table->string('semester');
            $table->integer('total_number');
            $table->integer('total_sold')->default(0);
            $table->integer('total_remains');
            $table->double('price');
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
        Schema::dropIfExists('admission_code_locations');
    }
};
