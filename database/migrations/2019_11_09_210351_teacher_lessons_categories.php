<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TeacherLessonsCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_lessons_categories', function (Blueprint $table) {
            $table->integer('lesson_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->foreign('lesson_id')->references('id')->on('teacher_lessons')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('teacher_lessons_category')->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_lessons_categories');
    }
}
