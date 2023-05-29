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
            $table->double('balance')->nullable();
            $table->double('remaining')->nullable();  // if the student pays all, deduct every semester to get the remaining.
            $table->boolean('payment_type')->default(0)->comment('0 = partial_payment, 1 = full_payment');
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
            $table->dropColumn('balance');
            $table->dropColumn('payment_type');
            $table->dropColumn('remaining');
        });
    }
};
