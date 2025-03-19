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
        Schema::table('student_registered_courses', function (Blueprint $table) {
            //
            $table->decimal('grade_point', 3, 1)->nullable();
            $table->string('letter_grade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_registered_courses', function (Blueprint $table) {
            //
            $table->dropColumn('grade_point');
            $table->dropColumn('letter_grade');
        });
    }
};
