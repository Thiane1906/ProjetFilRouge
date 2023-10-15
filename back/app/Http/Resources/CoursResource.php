<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CoursResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "module"=>$this->module->libelle,
            "semestre"=>$this->semestre->libelle,
            "professeur"=>$this->user->name,
            "Etat"=>$this->etat,
            "Classes" => $this->classes->map(function($c) {
                return [

                  "classe" => $c->libelle,
                  "nbre_Heure"=>$c->pivot->nbreHeure
                ];
            })
        ];
    }
}
