<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherLesson extends Model
{
    /*protected $table = 'teacher_lessons';
    protected $fillable = [
        'name',
        'title',
        'sub_title',
        'icon',
        'is_recommended',
    ];*/

    public function categories()
    {
        return $this->belongsToMany(
            'App\TeacherLessonCategory',
            'teacher_lessons_categories',
            'lesson_id',
            'category_id'
        );
    }
}
