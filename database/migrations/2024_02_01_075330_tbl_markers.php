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
        Schema::create('tbl_markers', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('projid');
            $table->string('opid')->nullable();
            $table->string('state')->nullable();
            $table->string('site_id');
            $table->string('lat');
            $table->float('lng');
            $table->float('distance_mapped');
            $table->date('mapped_date');
            $table->integer('mapped_by');
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
        //
    }
};
