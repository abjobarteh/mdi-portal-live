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
        // Rename matriculation_status to matriculation_statuses
     //   Schema::rename('matriculation_status', 'matriculation_statuses');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Rename matriculation_statuses back to matriculation_status
      //  Schema::rename('matriculation_statuses', 'matriculation_status');
    }
};
