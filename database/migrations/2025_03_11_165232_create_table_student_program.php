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
        Schema::create('student_programs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('program_id')->nullable()->constrained('programs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('student_id')->nullable()->constrained('students')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_program');
    }
};
