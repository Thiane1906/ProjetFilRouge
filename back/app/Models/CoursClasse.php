<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursClasse extends Model
{
    use HasFactory;

    public function cours(){
        return $this->belongsTo(Cours::class);
    }
    public function classes(){
        return $this->belongsTo(Classe::class);
    }
}
