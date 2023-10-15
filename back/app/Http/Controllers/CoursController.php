<?php

namespace App\Http\Controllers;

use App\Http\Resources\CoursResource;
use App\Models\Cours;
use App\Models\CoursClasse;
use App\Models\Module;
use App\Models\ModuleProfs;
use App\Models\User;
use Illuminate\Http\Request;

use function PHPSTORM_META\map;

class CoursController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            "data" =>  CoursResource::collection(Cours::all())
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
        $cours = Cours::firstOrCreate([
            "semestre_id" => $request->semestre_id,
            "module_id" => $request->module_id,
            "user_id" => $request->user_id,
            "etat" => $request->etat
        ]);
        $cours->classes()->attach($request->classe_cours);
        return response()->json([
            "data"=>[new CoursResource ($cours)]
        ]);
    }

    public function getProfBYModule(Request $request)
    {
        $module= Module::where('id',$request->id)->pluck('id');
       
        if (count($module)!==0) {
            
            $module_id=$module[0];
          
            $user = ModuleProfs::where('module_id', $module_id)->get();
           
            $user_id = $user->map(function($user){
                return $user->id;
            });   
               $prof = User::whereIn('id',$user_id )->get();
                    
            return response()->json([
                "data"=>$prof
            ]);
            
        }else{
            return response()->json([
                "message"=>"id introuvable"
            ]);
        }
      
    }
    public function getClasseByCours(Request $request ){
         $id= $request->id;
        //  $coursClasse= CoursClasse::where('cours_id',$id)->get();
        //  return $coursClasse->map(function($c){
        //     return $c->id;
        //  });
       $data= Cours::where('id',$id)->first();
       if (!$data) {
        return response()->json([
            "data"=>"erreur!!!"
           ]);
    }
    $cours= CoursResource::make($data);
     $classes= $cours->Classes->map(function($c){
      return [
         "id"=>$c->id,
        "libelle"=> $c->libelle
      ];
         
         
    });
    return response()->json([
     "data"=>$classes
    ]);
    }

    public function getIdClasseCoursByCours(Request $request ){
        $id= $request->id;
         $coursClasse= CoursClasse::where('cours_id',$id)->get();
         return $coursClasse->map(function($c){
            return [
                "id"=>$c->id
            ];
         });
    }
    /**
     * Display the specified resource.
     */
    public function show(Cours $cours)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cours $cours)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cours $cours)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cours $cours)
    {
        //
    }
}
