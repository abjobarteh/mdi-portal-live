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
        Schema::table('students', function (Blueprint $table) {
            $table->string('eme_name')->nullable(); // Add eme_name column
            $table->string('eme_numbr')->nullable(); // Add eme_numbr column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            //  Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('eme_name');
            $table->dropColumn('eme_numbr');
      
        });
    }
};
