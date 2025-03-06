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
            $table->boolean('waive')->default(0)->change();
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->boolean('waive')->change(); // Remove default value (if needed)
        });
    }
};
