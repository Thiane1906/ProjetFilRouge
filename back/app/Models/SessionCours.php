<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionCours extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function classes(){
        return $this->belongsToMany(Classe::class,"classe_sessions");
    } 
    public function salle(){
        return $this->belongsTo(Salle::class);
    }
    public function coursClasse(){
        return $this->belongsTo(CoursClasse::class);
    }
}
