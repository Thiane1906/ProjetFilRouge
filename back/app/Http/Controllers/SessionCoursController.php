<?php

namespace App\Http\Controllers;

use App\Http\Resources\SessionResource;
use App\Models\CoursClasse;
use App\Models\SessionCours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SessionCoursController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allSession=SessionCours::all();
        return response()->json([
            "data"=>SessionResource::collection($allSession)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $cours_classe=CoursClasse::where("cours_id",$request->cours_classe_id)->get();
        //  return $cours_classe;
        return DB::transaction(function() use($request,$cours_classe){

            $session=SessionCours::firstOrCreate([
                "cours_classe_id"=>$cours_classe[0]->id,
                "date"=>now(),
                "hr_debut"=>$request->hr_debut,
                "hr_fin"=>$request->hr_fin,
                "duree"=>$request->duree,
                "salle_id"=>$request->salle_id,
                "etat"=>0
            ]);
            $session->classes()->attach($request->session_Classe);
            return response()->json([
                "data"=>$session
            ]);
        });
        
    }

    /**
     * Display the specified resource.
     */
    public function show(SessionCours $sessionCours)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SessionCours $sessionCours)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SessionCours $sessionCours)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SessionCours $sessionCours)
    {
        //
    }
}
