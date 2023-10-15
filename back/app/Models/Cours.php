<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function classes(){
        return $this->belongsToMany(Classe::class,'cours_classes',"cours_id","classe_id")->withPivot("classe_id","nbreHeure");
    }
    public function Module(){
        return $this->belongsTo(Module::class);
    }
    public function semestre(){
        return $this->belongsTo(semestre::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
