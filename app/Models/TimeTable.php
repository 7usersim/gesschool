<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeTable extends Model
{
    protected $table = 'gsc_time_tables';

    public function TimeTable(){
        return $this->belongsTo(Classes::class);
    }
}
