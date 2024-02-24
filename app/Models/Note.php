<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $table = 'gsc_notes';

    public function ClassesCourses(){
       return $this->hasMany('App\Models\ClassesCourses','id_courses');

    }
    public function Student(){
      return  $this->hasMany('App\Models\Student','id_student');

    }
    public function Evaluation(){
       return $this->hasMany('App\Models\Evaluation','id_evaluation');

    }

}
