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
        Schema::create('outpasses', function (Blueprint $table) {
            $table->id();
            $table->Date("outpass_date");
            $table->string("outpass_from");
            $table->string("outpass_to");
            $table->string("status");
            $table->unsignedBigInteger("student_id");
            $table->foreign('student_id')->references('id')->on('students')
            ->onDelete('cascade');
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
        Schema::dropIfExists('outpasses');
    }
};
