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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id('assessment_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('module_no');
            $table->string('file_upload');
            $table->date('submission_date');
            $table->float('marks');
            $table->enum('status', ['submitted', 'graded', 'pending'])->default('pending');
            $table->enum('marking_status', ['marked', 'unmarked'])->default('unmarked');
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
        Schema::dropIfExists('assessments');
    }
};
