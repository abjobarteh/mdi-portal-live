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
        Schema::create('admission_codes', function (Blueprint $table) {
            $table->id();
            $table->string('admission_code');
            $table->foreignId('admission_code_location_id')->constrained('admission_code_locations')->onUpdate('cascade')->onDelete('cascade');
            $table->boolean('is_sold')->default(0);
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
        Schema::dropIfExists('admission_codes');
    }
};
