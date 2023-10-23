<?php

namespace App\Http\Controllers;

use App\Http\Resources\CoursResource;
use App\Http\Resources\SessionResource;
use App\Models\Classe;
use App\Models\Cours;
use App\Models\CoursClasse;
use App\Models\Salle;
use App\Models\SessionCours;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Constraint\Count;

class SessionCoursController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allSession = SessionCours::all();
        return response()->json([
            "data" => SessionResource::collection($allSession)
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

        return DB::transaction(function () use ($request) {
            $date = (explode(" ", $request->hr_debut))[0];
            $current = Carbon::now()->format('Y-m-d H:i:s');
            $currentDay = Carbon::now()->format('Y-m-d');
            if ($date < $currentDay) {
                return response()->json([
                    "message" => "la date ne peut pas etre inferieur à aujourd'hui",
                    "date"=>$date,
                    "current"=>$currentDay
                ], 422);
            }

            foreach ($request->session_Classe as $key) {
                $idClasse[] = $key;
            }
            $coursClasse = CoursClasse::whereIn('classe_id', $idClasse)->pluck('id');
            $sessionClasse = SessionCours::whereIn('cours_classe_id', $coursClasse)
            ->where("hr_debut",'<',$request->hr_fin)
            ->where("hr_fin",'>',$request->hr_debut)
            ->where("date",$date)
            ->first();

            if ($sessionClasse) {
                return response()->json([
                    "message" => "une des classes a déja un cours à cet heure"
                ], 422);
            }
            // return $sessionClasse;

            // foreach ($sessionClasse as $key) {
            //     if ($date == $key->date) {
            //         // $dateS[] = $key;
            //         // foreach ($dateS as $key) {


            //         if (($request->hr_debut >= $key->hr_debut && $request->hr_debut < $key->hr_fin) || ($request->hr_fin <= $key->hr_fin && $request->hr_fin > $key->hr_debut)) {
            //             return response()->json([
            //                 "message" => "une des classes a déja un cours à cet heure"
            //             ],422);
            //         }
            //         // }     # code...
            //     }
            // }
            // VALIDATION CONTENANCE SALLE   

            if ($request->salle_id ) {

                $capacitéSalle = Salle::where('id', $request->salle_id)->first()->nbre_de_place;

                // return $capacitéSalle;
                // return $idClasse;
                $effectifClasse = Classe::whereIn('id', $idClasse)->pluck('nbre_eleves')->toArray();

                $somEffectifClasse = array_sum($effectifClasse);
                if ($capacitéSalle < $somEffectifClasse) {
                    return response()->json([
                        "message" => "cette salle ne peut contenir ce nombre d'elèves"
                    ], 422);
                }



                //  return $cours_classe;
                $date = (explode(" ", $request->hr_debut))[0];
                // DISPONIBILITÉ DE LA SALLE
                // return $salle;
                if ($request->salle_id) {
                    $salle = SessionCours::where("salle_id", $request->salle_id)->get();
                    //     ->where("hr_debut", '>', $request->hr_debut)
                    //     ->where("hr_fin", '>', $request->hr_fin)
                    //     ->where("date", $date)
                    //     ->first();
                    // if ($salle) {
                    //     return response()->json([
                    //         "message" => "cette salle à déja un cours à cet heure"
                    //     ], 422);
                    // }

                    if (count($salle)==0) {
                        return response()->json([
                            "message"=>"erreur de salle !!!"
                        ]);
                    }
                    foreach ($salle as $key) {
                        if ($date == $key->date) {
                            if (($request->hr_debut >= $key->hr_debut && $request->hr_debut < $key->hr_fin) || ($request->hr_fin <= $key->hr_fin && $request->hr_fin > $key->hr_debut)) {
                                return response()->json([
                                    "message" => "cette salle à déja un cours à cet heure"
                                ]);
                            }
                            // }     # code...
                        }
                    }
                }
            }

            $cours_classe = CoursClasse::where("cours_id", $request->cours_classe_id)->get();


            if ($request->hr_debut > $request->hr_fin || strtotime($request->hr_fin) - strtotime($request->hr_debut) < 3600) {
                return response()->json([
                    "message" => "durée de session anormale"
                ], 422);
            }



            $cours = $cours_classe[0]->cours_id;

            $Prof = Cours::where('id', $cours)->first();
            $idProf = $Prof->user_id;

            // validation intervalle cours
            // $session=SessionCours::where('cours_classe_id',$cours)
            //                        ->where("hr_debut",'<',$request->hr_fin)
            //                        ->where("hr_fin",'>',$request->hr_debut)
            //                        ->where("date",$date)
            //                        ->first();
                                   
            //         if ($session) {
            //             return response()->json([
            //                 "message" => "une session est déja programmée pour cette heure"
            //             ], 422);
            //         }
            // getSessionByIdProfs
            $hrDebutSession =  SessionCours::where('user_id', $idProf)
                ->where("hr_debut", $request->hr_debut)
                ->where("hr_fin", $request->hr_fin)
                ->where("date", $date)
                ->first();
            if ($hrDebutSession) {
                return response()->json([
                    "message" => "le prof à déjà un cours à cet heure"
                ], 422);
            }
            //VALIDATION DISPONIBILITE PROFESSEUR
            // if (count($hrDebutSession) != 0) {

            //     foreach ($hrDebutSession as $key) {
            //         if ($date == $key->date) {
            //             $hr[] = $key;
            //             // return $hr;
            //             foreach ($hr as $key) {
            //                 if (($request->hr_debut >= $key->hr_debut && $request->hr_debut < $key->hr_fin) || ($request->hr_fin <= $key->hr_fin && $request->hr_fin > $key->hr_debut)) {
            //                     return response()->json([
            //                         "message" => "le prof  à déjà un cours à cet heure"
            //                     ]);
            //                 }
            //             }
            //         }
            //     }
            // }
            //CALCUL DE LA DURÉE 
            $dureeSeconde = strtotime($request->hr_fin) - strtotime($request->hr_debut);
            $duree = date('H', $dureeSeconde);
            $session = SessionCours::firstOrCreate([
                "cours_classe_id" => $cours_classe[0]->id,
                "date" => $date,
                "hr_debut" => $request->hr_debut,
                "hr_fin" => $request->hr_fin,
                "duree" => $duree,
                "salle_id" => $request->salle_id,
                "etat" => 0,
                "user_id" => $idProf
            ]);
            $nbreHeure=$cours_classe[0]->nbreHeure;
            $idCoursSession=$cours_classe[0]->id;
            $session->classes()->attach($request->session_Classe);
            if ($nbreHeure==0) {
                Cours::where('id',$cours)->update(['etat'=>1]);
                return response()->json([
                   
                    "message" => "le nbre d'heure de cours
                     est épuisé"
                ]);
            }else{

                CoursClasse::where('id',$idCoursSession)->update(['nbreHeure'=>$nbreHeure-$duree]);
            }
            return response()->json([
                "nbre d'heure"=>$idCoursSession,
                "data" => SessionResource::make($session),
                "message" => "session bien ajouté"
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
    public function getCoursByProfName(Request $request)
    {
        $name = $request->name;
        $user = User::where('name', $name)->first();
        $coursProf = Cours::where('user_id', $user->id)->get();
        $cours = CoursResource::collection($coursProf);
        return response()->json([
            "data" => $cours
        ]);
    }
}
