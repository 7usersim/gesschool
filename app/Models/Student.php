<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'gsc_students';

    public function cycle(){
       $this->hasOne("App\Models\Cycle",'id_cycle' );

     }

    public function classe(){
       $this->hasOne("App\Models\Classes",'id_cycle' );

     }

    public function Field(){
       $this->hasOne("App\Models\Classes",'id_cycle' );

     }
    public function Fees(){
       $this->hasOne("App\Models\FraisDeScolarite",'student_id' );

     }
}
