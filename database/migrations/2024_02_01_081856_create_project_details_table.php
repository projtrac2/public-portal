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
        Schema::create('tbl_project_details', function (Blueprint $table) {
            $table->id();
            $table->string('unique_key');
            $table->unsignedBigInteger('progid');
            $table->unsignedBigInteger('projid');
            $table->unsignedBigInteger('outputid');
            $table->unsignedBigInteger('indicator');
            $table->integer('output_start_year');
            $table->integer('duration');
            $table->integer('budget');
            $table->bigInteger('total_target');
            $table->integer('workplan_interval');
            $table->integer('unit_type');
            $table->integer('status');
            $table->integer('progress');
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
        Schema::dropIfExists('project_details');
    }
};
