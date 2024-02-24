<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassesCourses extends Model
{
    protected $table = 'gsc_classes_courses';

    public function Note(){
        $this->hasOne('App\Models\Note','id_courses');
    }

    public function User(){
        $this->hasOne('App\Models\User','teacher_id');
    }

}
