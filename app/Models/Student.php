<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'gsc_students';

    public function cycle(){
        return $this->hasOne("App\Models\Cycle",'id_cycle' );

     }

    public function classe(){
        return $this->hasOne("App\Models\Classes",'id_cycle' );

     }

    public function Field(){
        return $this->hasOne("App\Models\Classes",'id_cycle' );

     }
    public function Fees(){
        return $this->hasOne("App\Models\FraisDeScolarite",'student_id' );

     }

     public function Note(){
        return $this->hasOne('App\Models\Note','id_student');

    }



}
