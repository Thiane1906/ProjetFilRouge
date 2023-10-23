<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
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
            "date"=>$this->date,
            "hr_debut"=>$this->hr_debut,
            "hr_fin"=>$this->hr_fin,
            "duree"=>$this->duree,
            "Salle"=>SalleResource::make($this->salle),
            "coursClasse"=>CoursResource::make(coursClasseResource::make($this->coursClasse)->cours),
            // "classes"=>CoursResource::make(coursClasseResource::make($this->coursClasse)->Classes)
        ];
    }
}
