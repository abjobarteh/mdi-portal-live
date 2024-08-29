<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_location', function (Blueprint $table) {
            $table->id(); // Optional: adds an auto-incrementing id column
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('location_id');
            $table->string('day');
            $table->time('start_time');
            $table->time('end_time');
            
            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('cascade');
            $table->foreign('location_id')->references('location_id')->on('locations')->onDelete('cascade');
            
            $table->timestamps(); // Optional: adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_location');
    }
}

